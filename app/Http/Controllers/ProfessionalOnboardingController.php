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
        $payload = $this->onboardingService->montarModeloDaViewDeCadastro($requisicao->user());

        if ($payload === null) {
            return redirect()->route('dashboard');
        }

        return view('professional.setup', $payload);
    }

    /**
     * Processa o envio do formulário de setup já validado pelo {@see ProfessionalOnboardingRequest}.
     */
    public function store(ProfessionalOnboardingRequest $requisicao): RedirectResponse
    {
        $status = $this->onboardingService->persistirAPartirDoValidado(
            $requisicao->user(),
            $requisicao->validated()
        );

        return redirect()
            ->route('dashboard')
            ->with('status', $status);
    }
}
