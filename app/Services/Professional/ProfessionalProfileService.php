<?php

namespace App\Services\Professional;

use App\Http\Responses\ResultadoResposta;
use App\Models\Professional;
use App\Repositories\ProfessionalRepository;

/**
 * Página pública do perfil / portfólio de um profissional.
 */
class ProfessionalProfileService
{
    public function __construct(
        private readonly ProfessionalRepository $professionalRepository,
    ) {}

    public function montarPerfil(Professional $professional): ResultadoResposta
    {
        return ResultadoResposta::pagina('professionals.show', [
            'professional' => $this->professionalRepository->loadPublicProfile($professional),
        ]);
    }
}
