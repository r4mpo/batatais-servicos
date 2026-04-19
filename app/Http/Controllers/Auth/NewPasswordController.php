<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * Define nova senha a partir do token recebido por e-mail (reset completo).
 */
class NewPasswordController extends Controller
{
    /**
     * Formulário de nova senha (token e e-mail vêm da query string).
     */
    public function create(Request $requisicao): View
    {
        return view('auth.reset-password', ['request' => $requisicao]);
    }

    /**
     * Valida token+e-mail+senha e aplica {@see Password::reset}; em sucesso redireciona ao login.
     *
     * @throws ValidationException
     */
    public function store(Request $requisicao): RedirectResponse
    {
        $requisicao->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $status = Password::reset(
            $requisicao->only('email', 'password', 'password_confirmation', 'token'),
            function (User $usuario) use ($requisicao) {
                $usuario->forceFill([
                    'password' => Hash::make($requisicao->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($usuario));
            }
        );

        return $status == Password::PASSWORD_RESET
                    ? redirect()->route('login')->with('status', __($status))
                    : back()->withInput($requisicao->only('email'))
                        ->withErrors(['email' => __($status)]);
    }
}
