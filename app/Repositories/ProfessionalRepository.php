<?php

namespace App\Repositories;

use App\Models\Professional;
use App\Models\ProfessionalFile;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * Repositório de acesso a dados da listagem e do cadastro de profissionais.
 *
 * Todas as consultas à tabela `professionals` (e joins necessários) passam por aqui.
 */
class ProfessionalRepository
{
    /** Subconsulta reutilizada para média de avaliações na listagem. */
    private const AVG_RATING_SUBQUERY = '(SELECT AVG(rating) FROM professional_reviews WHERE professional_reviews.professional_id = professionals.id)';

    /**
     * Lista paginada de profissionais com filtros, ordenação e eager loads usados na página pública.
     *
     * @param  list<int>  $professionIds  IDs de categorias selecionadas (filtro).
     * @param  list<int>  $weekDayOfWeeks  Dias da semana (0–6) para filtro “disponível nesta semana”.
     * @return LengthAwarePaginator<int, Professional> Página de modelos {@see Professional} com query string preservada.
     */
    public function paginateForListing(
        int $perPage,
        string $q,
        array $professionIds,
        ?float $minAvgRating,
        int $maxHourlyRateCents,
        bool $availToday,
        bool $availWeek,
        bool $avail24h,
        int $todayDayOfWeek,
        array $weekDayOfWeeks,
        string $sort,
    ): LengthAwarePaginator {
        $query = Professional::query()
            ->with(['user', 'profession'])
            // SQL Server não aceita EXISTS(...) na lista do SELECT (withExists). O count mantém o mesmo atributo.
            ->withCount('solicitacoesVerificacaoAprovadas as solicitacoes_verificacao_aprovadas_exists')
            ->withAvg('reviews', 'rating')
            ->withCount('reviews');

        $this->applyListingFilters(
            $query,
            $q,
            $professionIds,
            $minAvgRating,
            $maxHourlyRateCents,
            $availToday,
            $availWeek,
            $avail24h,
            $todayDayOfWeek,
            $weekDayOfWeeks
        );

        $this->applyListingSort($query, $sort, $q);
        $query->orderBy('professionals.id');

        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * Aplica filtros de busca textual, categoria, nota, preço e disponibilidade ao query builder da listagem.
     *
     * @param  list<int>  $professionIds
     * @param  list<int>  $weekDayOfWeeks
     */
    private function applyListingFilters(
        Builder $query,
        string $q,
        array $professionIds,
        ?float $minAvgRating,
        int $maxHourlyRateCents,
        bool $availToday,
        bool $availWeek,
        bool $avail24h,
        int $todayDayOfWeek,
        array $weekDayOfWeeks,
    ): void {
        if ($q !== '') {
            $padraoLike = '%'.$q.'%';
            $query->where(function (Builder $consultaExterna) use ($padraoLike) {
                $consultaExterna->where('professionals.title', 'like', $padraoLike)
                    ->orWhere('professionals.description', 'like', $padraoLike)
                    ->orWhereHas('user', function (Builder $subConsultaUsuario) use ($padraoLike) {
                        $subConsultaUsuario->where('name', 'like', $padraoLike);
                    });
            });
        }

        if ($professionIds !== []) {
            $query->whereIn('profession_id', $professionIds);
        }

        if ($minAvgRating !== null) {
            $query->whereRaw(
                self::AVG_RATING_SUBQUERY.' >= ?',
                [$minAvgRating]
            );
        }

        $query->where('hourly_rate_cents', '<=', $maxHourlyRateCents);

        if ($availToday) {
            $query->whereHas('availabilities', function (Builder $subConsultaDisponibilidade) use ($todayDayOfWeek) {
                $subConsultaDisponibilidade->where('day_of_week', $todayDayOfWeek);
            });
        }

        if ($availWeek) {
            $query->whereHas('availabilities', function (Builder $subConsultaDisponibilidade) use ($weekDayOfWeeks) {
                $subConsultaDisponibilidade->whereIn('day_of_week', $weekDayOfWeeks);
            });
        }

        if ($avail24h) {
            $query->whereHas('availabilities', function (Builder $subConsultaDisponibilidade) {
                $subConsultaDisponibilidade->where('is_full_day', true);
            });
        }
    }

    /**
     * Define a ordenação da listagem conforme o critério escolhido (relevância, nota, preço, etc.).
     */
    private function applyListingSort(Builder $query, string $sort, string $q): void
    {
        $avgSub = self::AVG_RATING_SUBQUERY;

        switch ($sort) {
            case 'rating':
                $query->orderByRaw('CASE WHEN '.$avgSub.' IS NULL THEN 1 ELSE 0 END')
                    ->orderByRaw($avgSub.' DESC');
                break;
            case 'price_asc':
                $query->orderBy('hourly_rate_cents', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('hourly_rate_cents', 'desc');
                break;
            case 'recent':
                $query->orderByDesc('professionals.created_at');
                break;
            case 'relevance':
            default:
                if ($q !== '') {
                    $padraoLike = '%'.$q.'%';
                    $query->orderByRaw(
                        '(CASE WHEN professionals.title LIKE ? OR professionals.description LIKE ? THEN 0 ELSE 1 END)',
                        [$padraoLike, $padraoLike]
                    );
                }
                $query->orderByDesc('professionals.updated_at');
                break;
        }
    }

    /**
     * Indica se já existe ao menos um profissional vinculado ao usuário informado.
     */
    public function existsForUserId(int $userId): bool
    {
        return Professional::query()->where('user_id', $userId)->exists();
    }

    /**
     * Obtém o primeiro registro de profissional do usuário (cadastro único por conta).
     */
    public function findFirstForUserId(int $userId): ?Professional
    {
        return Professional::query()
            ->where('user_id', $userId)
            ->orderBy('id')
            ->first();
    }

    /**
     * Insere um novo registro em `professionals`.
     *
     * @param  array<string, mixed>  $attributes  Inclui `user_id`, `profession_id`, documentos, título, etc.
     */
    public function create(array $attributes): Professional
    {
        return Professional::query()->create($attributes);
    }

    /**
     * Atualiza atributos persistíveis de um profissional já existente.
     *
     * @param  array<string, mixed>  $attributes  Campos a sobrescrever (não deve incluir `user_id` em fluxos normais).
     */
    public function update(Professional $professional, array $attributes): void
    {
        $professional->update($attributes);
    }

    /**
     * Carrega bio, profissão, agenda, avaliações, fotos da vitrine e o selo.
     *
     * O count do selo usa alias no lugar de withExists: o SQL Server rejeita EXISTS na lista do SELECT.
     */
    public function loadPublicProfile(Professional $professional): Professional
    {
        $professional->load([
            'user',
            'profession',
            'availabilities' => function ($query) {
                $query->orderBy('day_of_week')->orderBy('starts_at');
            },
            'reviews' => function ($query) {
                $query->with('user:id,name')->latest();
            },
            'profileFiles' => function ($query) {
                $query->where('kind', ProfessionalFile::KIND_PUBLIC_PHOTO)
                    ->where(function ($photos) {
                        $photos->whereNull('file_type')
                            ->orWhere('file_type', ProfessionalFile::FILE_TYPE_CODE_SHOWCASE);
                    })
                    ->orderBy('sort_order');
            },
        ]);

        $professional->loadCount([
            'reviews',
            'solicitacoesVerificacaoAprovadas as solicitacoes_verificacao_aprovadas_exists',
        ]);
        $professional->loadAvg('reviews', 'rating');

        return $professional;
    }

    /**
     * @return Collection<int, Professional>
     */
    public function searchByNameOrProfession(string $termo, int $limit = 8): Collection
    {
        $like = '%'.$termo.'%';

        return Professional::query()
            ->with(['user:id,name', 'profession:id,title'])
            ->where(function (Builder $query) use ($like) {
                $query->whereHas('user', function (Builder $user) use ($like) {
                    $user->where('name', 'like', $like);
                })->orWhereHas('profession', function (Builder $profession) use ($like) {
                    $profession->where('title', 'like', $like);
                });
            })
            ->limit($limit)
            ->get();
    }

    public function findIdByUserId(int $userId): ?int
    {
        $id = Professional::query()->where('user_id', $userId)->value('id');

        return $id !== null ? (int) $id : null;
    }

    public function findWithUser(int $id): ?Professional
    {
        $professional = Professional::query()->with('user')->find($id);

        return $professional instanceof Professional ? $professional : null;
    }

    public function findWithUserAndProfession(int $id): ?Professional
    {
        $professional = Professional::query()->with(['user', 'profession'])->find($id);

        return $professional instanceof Professional ? $professional : null;
    }
}
