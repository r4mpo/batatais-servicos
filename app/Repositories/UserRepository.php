<?php

namespace App\Repositories;

use App\Models\User;

/**
 * Repositório de persistência para o modelo {@see User}.
 *
 * Centraliza operações de escrita na tabela `users`.
 */
class UserRepository
{
    /**
     * Persiste o hash da senha do usuário (valor já processado por {@see \Illuminate\Support\Facades\Hash}).
     *
     * @param  User  $user  Registro do usuário autenticado a atualizar.
     * @param  string  $hashedPassword  Senha já hasheada (nunca texto puro).
     */
    public function updatePasswordHash(User $user, string $hashedPassword): void
    {
        $user->forceFill(['password' => $hashedPassword])->save();
    }
}
