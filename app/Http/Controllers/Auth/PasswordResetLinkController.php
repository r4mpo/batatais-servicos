<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * Solicitação de link de redefinição de senha (fluxo “esqueci minha senha”).
 */
class PasswordResetLinkController extends Controller
{
    /**
     * Exibe o formulário com campo de e-mail.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Envia o link via facade `Password`; mensagem de status vem das traduções do framework.
     *
     * @throws ValidationException
     */
    public function store(Request $requisicao): RedirectResponse
    {
        $requisicao->validate([
            'email' => ['required', 'email'],
        ]);

        $status = Password::sendResetLink(
            $requisicao->only('email')
        );

        return $status == Password::RESET_LINK_SENT
                    ? back()->with('status', __($status))
                    : back()->withInput($requisicao->only('email'))
                        ->withErrors(['email' => __($status)]);
    }
}
