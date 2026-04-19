<?php

namespace App\Http\Controllers;

use App\Services\User\UserProfilePhotoService;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Entrega da foto de perfil pública via token criptografado (sem id na URL).
 */
class ProfilePhotoController extends Controller
{
    public function __construct(
        private readonly UserProfilePhotoService $profilePhotoService,
    ) {}

    public function show(string $token): BinaryFileResponse
    {
        return $this->profilePhotoService->serve($token);
    }
}
