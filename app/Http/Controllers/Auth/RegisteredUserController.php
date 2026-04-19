<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * Cadastro público de nova conta (contratante ou profissional).
 */
class RegisteredUserController extends Controller
{
    /**
     * Exibe o formulário de registro.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Valida e cria o usuário, dispara evento de registro e autentica na sequência.
     *
     * @throws ValidationException
     */
    public function store(Request $requisicao): RedirectResponse
    {
        $requisicao->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'profile' => ['required', 'string', 'in:'.User::PROFILE_CONTRACTOR.','.User::PROFILE_PROFESSIONAL],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $usuario = User::create([
            'name' => $requisicao->name,
            'email' => $requisicao->email,
            'profile' => $requisicao->profile,
            'password' => Hash::make($requisicao->password),
        ]);

        event(new Registered($usuario));

        Auth::login($usuario);

        return redirect(route('dashboard', absolute: false));
    }
}
