<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

/**
 * Confirma o link assinado enviado por e-mail e marca o endereço como verificado.
 */
class VerifyEmailController extends Controller
{
    /**
     * Se ainda não estiver verificado, marca e dispara {@see Verified}; depois redireciona ao dashboard.
     */
    public function __invoke(EmailVerificationRequest $requisicao): RedirectResponse
    {
        if ($requisicao->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
        }

        if ($requisicao->user()->markEmailAsVerified()) {
            event(new Verified($requisicao->user()));
        }

        return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
    }
}
