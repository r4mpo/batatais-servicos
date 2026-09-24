<?php

namespace App\Services\User;

use App\Http\Responses\ResultadoResposta;
use App\Models\User;

/**
 * Atualização e exclusão da conta na área de perfil.
 */
class UserProfileService
{
    /**
     * @param  array<string, mixed>  $validado
     */
    public function atualizar(mixed $usuario, array $validado): ResultadoResposta
    {
        if (! $usuario instanceof User) {
            return ResultadoResposta::erroHttp(403);
        }

        $usuario->fill($validado);

        if ($usuario->isDirty('email')) {
            $usuario->email_verified_at = null;
        }

        $usuario->save();

        return ResultadoResposta::redirecionar('profile.edit', status: 'profile-updated');
    }

    public function excluir(mixed $usuario): ResultadoResposta
    {
        if (! $usuario instanceof User) {
            return ResultadoResposta::erroHttp(403);
        }

        $usuario->delete();

        return ResultadoResposta::redirecionar('home');
    }

    public function montarEdicao(mixed $usuario): ResultadoResposta
    {
        if (! $usuario instanceof User) {
            return ResultadoResposta::erroHttp(403);
        }

        return ResultadoResposta::pagina('profile.edit', [
            'user' => $usuario,
        ]);
    }
}
