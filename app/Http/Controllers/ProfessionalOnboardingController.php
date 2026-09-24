<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfessionalOnboardingRequest;
use App\Services\Professional\ProfessionalOnboardingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Controlador HTTP do fluxo de cadastro/edição do perfil profissional.
 *
 * Delega regras de negócio ao {@see ProfessionalOnboardingService}.
 */
class ProfessionalOnboardingController extends Controller
{
    public function __construct(
        private readonly ProfessionalOnboardingService $onboardingService,
    ) {}

    /**
     * Exibe o formulário de setup (criação ou edição), quando o usuário é profissional.
     */
    public function edit(Request $requisicao): RedirectResponse|View
    {
        return $this->responder($this->onboardingService->montarModeloDaViewDeCadastro($requisicao->user()));
    }

    /**
     * Processa o envio do formulário de setup já validado pelo {@see ProfessionalOnboardingRequest}.
     */
    public function store(ProfessionalOnboardingRequest $requisicao): RedirectResponse
    {
        return $this->responder($this->onboardingService->persistirAPartirDoValidado(
            $requisicao->user(),
            $requisicao->validated()
        ));
    }
}
