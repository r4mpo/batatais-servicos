<?php

namespace App\Services\Professional;

use App\Repositories\ProfessionRepository;
use App\Repositories\ProfessionalRepository;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

/**
 * Regras da listagem pública `/profissionais`: interpretar filtros da query string e montar a página.
 */
class ProfessionalListingService
{
    /** Itens por página na grade. */
    private const PER_PAGE = 12;

    public function __construct(
        private readonly ProfessionRepository $professionRepository,
        private readonly ProfessionalRepository $professionalRepository,
    ) {}

    /**
     * Monta todos os dados da view `professionals.index`.
     *
     * Passo a passo:
     * 1. Carregar profissões para o filtro lateral (ordenadas por título).
     * 2. Ler `profession_id[]`, texto `q`, `sort`, checkboxes de nota, faixa de preço e disponibilidade.
     * 3. Se vier `categoria` (slug) sem ids selecionados, resolver um único id de profissão.
     * 4. Normalizar ordenação para um dos valores permitidos.
     * 5. Converter filtros de nota no piso de média exigido (ou null).
     * 6. Se “nesta semana” estiver ativo, calcular dias da semana de hoje até domingo.
     * 7. Chamar o repositório com preço em centavos e paginar.
     *
     * @return array{
     *     professionals: LengthAwarePaginator,
     *     filterProfessions: \Illuminate\Database\Eloquent\Collection,
     *     selectedProfessionIds: list<int>,
     *     q: string,
     *     sort: string,
     *     rating_5: bool,
     *     rating_4: bool,
     *     max_price_reais: int,
     *     avail_today: bool,
     *     avail_week: bool,
     *     avail_24h: bool
     * }
     */
    public function montarListagem(Request $requisicao): array
    {
        $profissoesParaFiltro = $this->professionRepository->orderedForProfessionalsFilter();

        $idsProfissoesSelecionados = array_values(array_filter(array_map(
            'intval',
            (array) $requisicao->input('profession_id', [])
        )));

        $slugCategoria = $requisicao->string('categoria')->trim()->toString();
        if ($slugCategoria !== '' && $idsProfissoesSelecionados === []) {
            $idPorSlug = $this->professionRepository->findIdBySlug($slugCategoria);
            if ($idPorSlug !== null) {
                $idsProfissoesSelecionados = [$idPorSlug];
            }
        }

        $textoBusca = $requisicao->string('q')->trim()->toString();
        $ordenacao = $requisicao->string('sort')->toString();
        if (! in_array($ordenacao, ['relevance', 'rating', 'price_asc', 'price_desc', 'recent'], true)) {
            $ordenacao = 'relevance';
        }

        $filtroNota5 = $requisicao->boolean('rating_5');
        $filtroNota4 = $requisicao->boolean('rating_4');
        $precoMaxReais = min(500, max(0, (int) $requisicao->input('max_price', 500)));
        $disponivelHoje = $requisicao->boolean('avail_today');
        $disponivelSemana = $requisicao->boolean('avail_week');
        $disponivel24h = $requisicao->boolean('avail_24h');

        $mediaMinima = $this->resolverMediaMinimaPorFiltroDeEstrelas($filtroNota5, $filtroNota4);
        $diasSemanaParaFiltro = $disponivelSemana ? $this->diasDaSemanaDeHojeAteDomingo() : [];

        $profissionais = $this->professionalRepository->paginateForListing(
            self::PER_PAGE,
            $textoBusca,
            $idsProfissoesSelecionados,
            $mediaMinima,
            $precoMaxReais * 100,
            $disponivelHoje,
            $disponivelSemana,
            $disponivel24h,
            (int) now()->dayOfWeek,
            $diasSemanaParaFiltro,
            $ordenacao,
        );

        return [
            'professionals' => $profissionais,
            'filterProfessions' => $profissoesParaFiltro,
            'selectedProfessionIds' => $idsProfissoesSelecionados,
            'q' => $textoBusca,
            'sort' => $ordenacao,
            'rating_5' => $filtroNota5,
            'rating_4' => $filtroNota4,
            'max_price_reais' => $precoMaxReais,
            'avail_today' => $disponivelHoje,
            'avail_week' => $disponivelSemana,
            'avail_24h' => $disponivel24h,
        ];
    }

    /**
     * Passo a passo:
     * 1. Se nenhum filtro de estrela estiver marcado, não exigir média mínima (null).
     * 2. Caso contrário, usar o menor limite entre 4,5 e 4,0 conforme os checkboxes.
     */
    private function resolverMediaMinimaPorFiltroDeEstrelas(bool $nota5, bool $nota4): ?float
    {
        if (! $nota5 && ! $nota4) {
            return null;
        }

        $limites = [];
        if ($nota5) {
            $limites[] = 4.5;
        }
        if ($nota4) {
            $limites[] = 4.0;
        }

        return min($limites);
    }

    /**
     * Passo a passo:
     * 1. Percorrer do dia atual até o domingo (fim de semana Carbon com domingo).
     * 2. Coletar `day_of_week` únicos para o filtro em `professional_availabilities`.
     *
     * @return list<int>
     */
    private function diasDaSemanaDeHojeAteDomingo(): array
    {
        $dias = [];
        $cursor = now()->startOfDay();
        $fim = now()->copy()->endOfWeek(Carbon::SUNDAY)->startOfDay();

        while ($cursor->lte($fim)) {
            $dias[] = (int) $cursor->dayOfWeek;
            $cursor->addDay();
        }

        return array_values(array_unique($dias));
    }
}
