<?php

namespace App\Http\Controllers;

use App\Models\Professional;
use App\Services\Professional\ProfessionalListingService;
use App\Services\Professional\ProfessionalProfileService;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Listagem pública de profissionais com filtros na query string.
 */
class ProfessionalController extends Controller
{
    public function __construct(
        private readonly ProfessionalListingService $listingService,
        private readonly ProfessionalProfileService $profileService,
    ) {}

    /**
     * Exibe a grade paginada de profissionais conforme filtros do {@see Request}.
     */
    public function index(Request $requisicao): View
    {
        return $this->responder($this->listingService->montarListagem($requisicao));
    }

    /**
     * Exibe o portfólio público de um profissional.
     */
    public function show(Professional $professional): View
    {
        return $this->responder($this->profileService->montarPerfil($professional));
    }
}
