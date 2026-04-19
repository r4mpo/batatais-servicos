<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

/**
 * Área logada: edição de dados básicos da conta e exclusão da conta.
 */
class ProfileController extends Controller
{
    /**
     * Exibe o formulário de perfil (nome, e-mail).
     */
    public function edit(Request $requisicao): View
    {
        return view('profile.edit', [
            'user' => $requisicao->user(),
        ]);
    }

    /**
     * Atualiza nome/e-mail; se o e-mail mudar, invalida a verificação.
     */
    public function update(ProfileUpdateRequest $requisicao): RedirectResponse
    {
        $requisicao->user()->fill($requisicao->validated());

        if ($requisicao->user()->isDirty('email')) {
            $requisicao->user()->email_verified_at = null;
        }

        $requisicao->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Remove a conta após confirmar a senha atual; encerra sessão e invalida token CSRF.
     */
    public function destroy(Request $requisicao): RedirectResponse
    {
        $requisicao->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $usuario = $requisicao->user();

        Auth::logout();

        $usuario->delete();

        $requisicao->session()->invalidate();
        $requisicao->session()->regenerateToken();

        return Redirect::to('/');
    }
}
