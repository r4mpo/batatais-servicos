<?php

namespace App\Http\Controllers;

use App\Services\User\UserProfilePhotoService;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Rota pública GET `/media/perfil/{token}`: delega ao {@see UserProfilePhotoService}.
 */
class ProfilePhotoController extends Controller
{
    public function __construct(
        private readonly UserProfilePhotoService $profilePhotoService,
    ) {}

    /** Passo único: entregar arquivo conforme token (regras no service). */
    public function show(string $token): BinaryFileResponse
    {
        return $this->profilePhotoService->entregarArquivo($token);
    }
}
