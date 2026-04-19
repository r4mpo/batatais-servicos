<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * Login e logout da área autenticada (sessão web).
 */
class AuthenticatedSessionController extends Controller
{
    /**
     * Exibe o formulário de login.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Autentica credenciais, regenera o ID de sessão e redireciona para a URL pretendida.
     */
    public function store(LoginRequest $requisicao): RedirectResponse
    {
        $requisicao->authenticate();

        $requisicao->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Encerra a sessão atual e invalida o token CSRF.
     */
    public function destroy(Request $requisicao): RedirectResponse
    {
        Auth::guard('web')->logout();

        $requisicao->session()->invalidate();

        $requisicao->session()->regenerateToken();

        return redirect('/');
    }
}
