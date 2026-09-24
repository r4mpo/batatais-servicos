<?php

namespace App\Services\Dashboard;

use App\Http\Responses\ResultadoResposta;
use App\Models\Service;
use App\Models\User;

/**
 * Dados da área logada inicial.
 */
class DashboardService
{
    public function montarPainel(mixed $usuario): ResultadoResposta
    {
        $financeSummary = null;
        if ($usuario instanceof User && $usuario->isProfessional()) {
            $financeSummary = Service::financeSummaryForProfessionalUser($usuario->id);
        }

        return ResultadoResposta::pagina('dashboard', [
            'financeSummary' => $financeSummary,
        ]);
    }
}
