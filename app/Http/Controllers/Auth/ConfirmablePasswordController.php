<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * Confirmação de senha para ações sensíveis (middleware `password.confirm`).
 */
class ConfirmablePasswordController extends Controller
{
    /**
     * Exibe o formulário de confirmação de senha.
     */
    public function show(): View
    {
        return view('auth.confirm-password');
    }

    /**
     * Valida senha atual contra o guard web e grava timestamp na sessão.
     */
    public function store(Request $requisicao): RedirectResponse
    {
        if (! Auth::guard('web')->validate([
            'email' => $requisicao->user()->email,
            'password' => $requisicao->password,
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        $requisicao->session()->put('auth.password_confirmed_at', time());

        return redirect()->intended(route('dashboard', absolute: false));
    }
}
