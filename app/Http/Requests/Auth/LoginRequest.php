<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

/**
 * Login: validação de campos, tentativa de autenticação e limite de taxa por e-mail+IP.
 */
class LoginRequest extends FormRequest
{
    /**
     * Qualquer visitante pode tentar autenticar (a rota já é guest).
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Regras mínimas para e-mail e senha antes de chamar {@see authenticate()}.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Passo a passo:
     * 1. Garantir que não estamos em lockout por excesso de tentativas.
     * 2. Tentar `Auth::attempt` com lembrar-me opcional.
     * 3. Em falha, registrar tentativa e mensagem genérica; em sucesso, limpar contador.
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Se o limite de tentativas foi excedido, bloqueia com mensagem contendo tempo restante.
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $segundosRestantes = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $segundosRestantes,
                'minutes' => ceil($segundosRestantes / 60),
            ]),
        ]);
    }

    /**
     * Chave única para rate limit: e-mail normalizado + IP.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
