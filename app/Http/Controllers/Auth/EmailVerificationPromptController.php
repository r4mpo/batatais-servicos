<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Página intermediária pedindo verificação de e-mail antes de seguir no app.
 */
class EmailVerificationPromptController extends Controller
{
    /**
     * Se já verificado, manda para o destino pretendido; senão mostra a view de aviso.
     */
    public function __invoke(Request $requisicao): RedirectResponse|View
    {
        return $requisicao->user()->hasVerifiedEmail()
                    ? redirect()->intended(route('dashboard', absolute: false))
                    : view('auth.verify-email');
    }
}
