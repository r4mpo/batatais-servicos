<?php

namespace App\Services\Professional;

use App\Enums\ServiceStatus;
use App\Http\Responses\ResultadoResposta;
use App\Models\User;
use App\Repositories\ServiceRepository;
use Illuminate\Http\Request;

/**
 * Histórico de serviços prestados pelo profissional autenticado.
 */
class ProfessionalServiceHistoryService
{
    public function __construct(
        private readonly ServiceRepository $serviceRepository,
    ) {}

    public function montarHistorico(mixed $usuario, Request $request): ResultadoResposta
    {
        if (! $usuario instanceof User || ! $usuario->isProfessional()) {
            return ResultadoResposta::erroHttp(403);
        }

        $filters = $this->filtros($request);

        $services = $this->serviceRepository->paginateForOwner(
            'professional_user_id',
            (int) $usuario->id,
            $filters,
            'contractor',
            ['contractor:id,name,email'],
        );

        return ResultadoResposta::pagina('professional.service-history', [
            'services' => $services,
            'filters' => $filters,
            'filtersActive' => $filters['q'] !== '' || $filters['status'] !== '' || $filters['sort'] !== 'recent',
            'statuses' => ServiceStatus::cases(),
        ]);
    }

    /**
     * @return array{q: string, status: string, sort: string}
     */
    private function filtros(Request $request): array
    {
        $status = ServiceStatus::tryFrom((int) $request->query('status', 0));
        $sort = (string) $request->query('sort', 'recent');
        if (! in_array($sort, ['recent', 'oldest', 'value_desc', 'value_asc'], true)) {
            $sort = 'recent';
        }

        return [
            'q' => trim((string) $request->query('q', '')),
            'status' => $status?->value !== null ? (string) $status->value : '',
            'sort' => $sort,
        ];
    }
}
