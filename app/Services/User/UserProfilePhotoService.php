<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Regras para expor a foto de perfil sem colocar o id numérico do usuário na URL.
 *
 * O token é o id criptografado (Laravel); caracteres problemáticos na URL são normalizados.
 */
class UserProfilePhotoService
{
    /**
     * Monta a URL da rota `profile.photo` quando existe `users.profile_photo`.
     *
     * Passo a passo:
     * 1. Se não houver nome de arquivo cadastrado, retornar null.
     * 2. Gerar token a partir do `users.id`.
     * 3. Montar URL nomeada com o token no path.
     */
    public function urlPublica(User $usuario): ?string
    {
        if ($usuario->profile_photo === null || $usuario->profile_photo === '') {
            return null;
        }

        $token = $this->codificarIdUsuario($usuario->id);

        return route('profile.photo', ['token' => $token]);
    }

    /**
     * Localiza o usuário pelo token, valida arquivo em disco e devolve a imagem.
     *
     * Passo a passo:
     * 1. Decodificar token para id; se inválido, 404.
     * 2. Buscar usuário e conferir coluna `profile_photo`.
     * 3. Verificar arquivo em `public/` + `PROFILE_PHOTO_PUBLIC_DIR`.
     * 4. Responder com `response()->file` e cache privado curto.
     */
    public function entregarArquivo(string $token): BinaryFileResponse
    {
        $idUsuario = $this->decodificarToken($token);
        if ($idUsuario === null) {
            throw new NotFoundHttpException;
        }

        $usuario = User::query()->find($idUsuario);
        if ($usuario === null || $usuario->profile_photo === null || $usuario->profile_photo === '') {
            throw new NotFoundHttpException;
        }

        $caminhoFisico = public_path(User::PROFILE_PHOTO_PUBLIC_DIR.DIRECTORY_SEPARATOR.$usuario->profile_photo);
        if (! is_file($caminhoFisico)) {
            throw new NotFoundHttpException;
        }

        return response()->file($caminhoFisico, [
            'Content-Disposition' => 'inline; filename="profile"',
            'Cache-Control' => 'private, max-age=3600',
        ]);
    }

    /**
     * Gera o segmento de URL a partir do id (criptografia + troca de `+` e `/`).
     *
     * Passo a passo:
     * 1. Criptografar o id como string.
     * 2. Substituir caracteres que atrapalham em segmentos de rota.
     */
    public function codificarIdUsuario(int $idUsuario): string
    {
        $carga = Crypt::encryptString((string) $idUsuario);

        return strtr($carga, ['+' => '-', '/' => '_']);
    }

    /**
     * Recupera o id a partir do token recebido na requisição.
     *
     * Passo a passo:
     * 1. Reverter a troca de caracteres.
     * 2. Descriptografar; em falha, retornar null.
     */
    public function decodificarToken(string $token): ?int
    {
        $carga = strtr($token, ['-' => '+', '_' => '/']);
        try {
            return (int) Crypt::decryptString($carga);
        } catch (\Throwable) {
            return null;
        }
    }
}
