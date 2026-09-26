<?php

namespace App\Repositories;

use App\Enums\ServiceStatus;
use App\Models\Service;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

/**
 * Persistência e consultas da tabela `services`.
 */
class ServiceRepository
{
    /**
     * Lista paginada dos serviços de um contratante ou de um profissional.
     *
     * @param  'contractor_user_id'|'professional_user_id'  $ownerColumn
     * @param  array{q: string, status: string, sort: string}  $filters
     * @param  'contractor'|'professionalUser'  $personRelation
     * @param  list<string>  $with
     * @return LengthAwarePaginator<int, Service>
     */
    public function paginateForOwner(
        string $ownerColumn,
        int $userId,
        array $filters,
        string $personRelation,
        array $with,
        int $perPage = 12,
    ): LengthAwarePaginator {
        $query = Service::query()
            ->with($with)
            ->where($ownerColumn, $userId);

        $this->aplicarFiltros($query, $filters, $personRelation);

        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): Service
    {
        return Service::query()->create($attributes);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(Service $service, array $attributes): void
    {
        $service->update($attributes);
    }

    public function delete(Service $service): void
    {
        $service->delete();
    }

    public function loadProfessionalUser(Service $service): void
    {
        $service->load('professionalUser:id,name');
    }

    /**
     * Bruto concluído ainda não sacado e bruto já sacado, em centavos.
     *
     * @return array{available_gross_cents: int, withdrawn_gross_cents: int}
     */
    public function grossCentsForProfessional(int $userId): array
    {
        $query = Service::query()->where('professional_user_id', $userId);

        return [
            'available_gross_cents' => (int) (clone $query)
                ->where('status', ServiceStatus::Concluded)
                ->where('value_withdrawn', false)
                ->sum('service_value_cents'),
            'withdrawn_gross_cents' => (int) (clone $query)
                ->where('status', ServiceStatus::Concluded)
                ->where('value_withdrawn', true)
                ->sum('service_value_cents'),
        ];
    }

    /**
     * Somas do contratante por grupo de status, em centavos.
     *
     * @return array{pending_cents: int, in_progress_cents: int, concluded_cents: int}
     */
    public function spendingCentsForContractor(int $userId): array
    {
        $query = Service::query()->where('contractor_user_id', $userId);

        return [
            'pending_cents' => (int) (clone $query)
                ->where('status', ServiceStatus::PaymentPending)
                ->sum('service_value_cents'),
            'in_progress_cents' => (int) (clone $query)->whereIn('status', [
                ServiceStatus::UnderReview,
                ServiceStatus::AcceptedByProfessional,
                ServiceStatus::InProgress,
                ServiceStatus::Maintenance,
                ServiceStatus::Support,
            ])->sum('service_value_cents'),
            'concluded_cents' => (int) (clone $query)
                ->where('status', ServiceStatus::Concluded)
                ->sum('service_value_cents'),
        ];
    }

    /**
     * @param  array{q: string, status: string, sort: string}  $filters
     * @param  'contractor'|'professionalUser'  $personRelation
     */
    private function aplicarFiltros(Builder $query, array $filters, string $personRelation): void
    {
        if ($filters['status'] !== '') {
            $query->where('status', (int) $filters['status']);
        }

        if ($filters['q'] !== '') {
            $like = '%'.$filters['q'].'%';
            $query->where(function (Builder $sub) use ($like, $personRelation) {
                $sub->where('title', 'like', $like)
                    ->orWhereHas($personRelation, function (Builder $person) use ($like) {
                        $person->where('name', 'like', $like);
                    });
            });
        }

        match ($filters['sort']) {
            'oldest' => $query->orderBy('created_at')->orderBy('id'),
            'value_desc' => $query->orderByDesc('service_value_cents')->orderByDesc('id'),
            'value_asc' => $query->orderBy('service_value_cents')->orderBy('id'),
            default => $query->orderByDesc('created_at')->orderByDesc('id'),
        };
    }
}
