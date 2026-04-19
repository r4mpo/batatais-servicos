<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Exposição da foto de perfil via URL com identificador criptografado (sem expor o id do usuário).
 */
class UserProfilePhotoService
{
    public function publicUrl(User $user): ?string
    {
        if ($user->profile_photo === null || $user->profile_photo === '') {
            return null;
        }

        $token = $this->encodeUserId($user->id);

        return route('profile.photo', ['token' => $token]);
    }

    public function serve(string $token): BinaryFileResponse
    {
        $userId = $this->decodeToken($token);
        if ($userId === null) {
            throw new NotFoundHttpException;
        }

        $user = User::query()->find($userId);
        if ($user === null || $user->profile_photo === null || $user->profile_photo === '') {
            throw new NotFoundHttpException;
        }

        $path = public_path(User::PROFILE_PHOTO_PUBLIC_DIR.DIRECTORY_SEPARATOR.$user->profile_photo);
        if (! is_file($path)) {
            throw new NotFoundHttpException;
        }

        return response()->file($path, [
            'Content-Disposition' => 'inline; filename="profile"',
            'Cache-Control' => 'private, max-age=3600',
        ]);
    }

    /**
     * Token para o segmento da URL (+ e / trocados para evitar conflito com rotas).
     */
    public function encodeUserId(int $userId): string
    {
        $payload = Crypt::encryptString((string) $userId);

        return strtr($payload, ['+' => '-', '/' => '_']);
    }

    public function decodeToken(string $token): ?int
    {
        $payload = strtr($token, ['-' => '+', '_' => '/']);
        try {
            return (int) Crypt::decryptString($payload);
        } catch (\Throwable) {
            return null;
        }
    }
}
