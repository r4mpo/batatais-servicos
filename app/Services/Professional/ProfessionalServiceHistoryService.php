<?php

namespace App\Services\Professional;

use App\Http\Responses\ResultadoResposta;
use App\Models\Service;
use App\Models\User;

/**
 * Histórico de serviços prestados pelo profissional autenticado.
 */
class ProfessionalServiceHistoryService
{
    public function montarHistorico(mixed $usuario): ResultadoResposta
    {
        if (! $usuario instanceof User || ! $usuario->isProfessional()) {
            return ResultadoResposta::erroHttp(403);
        }

        $services = Service::query()
            ->with('contractor:id,name,email')
            ->where('professional_user_id', $usuario->id)
            ->orderByDesc('created_at')
            ->paginate(12);

        return ResultadoResposta::pagina('professional.service-history', [
            'services' => $services,
        ]);
    }
}
