<?php

namespace App\Services\Dashboard;

use App\Http\Responses\ResultadoResposta;
use App\Models\User;
use App\Repositories\ServiceRepository;

/**
 * Dados da área logada inicial.
 */
class DashboardService
{
    public function __construct(
        private readonly ServiceRepository $serviceRepository,
    ) {}

    public function montarPainel(mixed $usuario): ResultadoResposta
    {
        $financeSummary = null;
        $contractorSummary = null;
        if ($usuario instanceof User && $usuario->isProfessional()) {
            $somas = $this->serviceRepository->grossCentsForProfessional((int) $usuario->id);
            $financeSummary = [
                'available_withdrawal_cents' => $somas['available_gross_cents'],
                'net_available_cents' => (int) round($somas['available_gross_cents'] * 0.9),
                'total_withdrawn_cents' => $somas['withdrawn_gross_cents'],
                'net_withdrawn_cents' => (int) round($somas['withdrawn_gross_cents'] * 0.9),
            ];
        }
        if ($usuario instanceof User && $usuario->isContractor()) {
            $somas = $this->serviceRepository->spendingCentsForContractor((int) $usuario->id);
            $contractorSummary = [
                'total_cents' => $somas['pending_cents'] + $somas['in_progress_cents'] + $somas['concluded_cents'],
                'pending_cents' => $somas['pending_cents'],
                'in_progress_cents' => $somas['in_progress_cents'],
                'concluded_cents' => $somas['concluded_cents'],
            ];
        }

        return ResultadoResposta::pagina('dashboard', [
            'financeSummary' => $financeSummary,
            'contractorSummary' => $contractorSummary,
        ]);
    }
}
