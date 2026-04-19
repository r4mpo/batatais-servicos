<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Reenvia o e-mail com link de verificação (throttle vem do trait do modelo User).
 */
class EmailVerificationNotificationController extends Controller
{
    /**
     * Ignora se já verificado; caso contrário dispara nova notificação na fila/canal.
     */
    public function store(Request $requisicao): RedirectResponse
    {
        if ($requisicao->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        $requisicao->user()->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }
}
