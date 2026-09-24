<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileDeletionRequest;
use App\Http\Requests\ProfileUpdateRequest;
use App\Services\User\UserProfileService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * Área logada: edição de dados básicos da conta e exclusão da conta.
 */
class ProfileController extends Controller
{
    public function __construct(
        private readonly UserProfileService $profileService,
    ) {}

    /**
     * Exibe o formulário de perfil (nome, e-mail).
     */
    public function edit(Request $requisicao): View
    {
        return $this->responder($this->profileService->montarEdicao($requisicao->user()));
    }

    /**
     * Atualiza nome/e-mail; se o e-mail mudar, invalida a verificação.
     */
    public function update(ProfileUpdateRequest $requisicao): RedirectResponse
    {
        return $this->responder($this->profileService->atualizar($requisicao->user(), $requisicao->validated()));
    }

    /**
     * Remove a conta após confirmar a senha atual; encerra sessão e invalida token CSRF.
     */
    public function destroy(ProfileDeletionRequest $requisicao): RedirectResponse
    {
        $usuario = $requisicao->user();

        Auth::logout();

        $resultado = $this->profileService->excluir($usuario);

        $requisicao->session()->invalidate();
        $requisicao->session()->regenerateToken();

        return $this->responder($resultado);
    }
}
