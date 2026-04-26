<?php

namespace App\Repositories;

use App\Models\ProfessionalVerificationRequest;
use Illuminate\Database\Eloquent\Collection;

/**
 * Acesso a dados de solicitações de verificação vinculadas a {@see \App\Models\User} (conta profissional).
 */
class ProfessionalVerificationRequestRepository
{
    public function possuiAprovadaParaUserId(int $idUsuario): bool
    {
        return ProfessionalVerificationRequest::query()
            ->where('user_id', $idUsuario)
            ->where('approved', true)
            ->exists();
    }

    public function possuiPendenteParaUserId(int $idUsuario): bool
    {
        return ProfessionalVerificationRequest::query()
            ->where('user_id', $idUsuario)
            ->whereNull('approved')
            ->exists();
    }

    /**
     * Inclui quem decidiu (se houver) para exibir nome na tela.
     *
     * @return Collection<int, ProfessionalVerificationRequest>
     */
    public function listarPorUserIdMaisRecentePrimeiro(int $idUsuario): Collection
    {
        return ProfessionalVerificationRequest::query()
            ->where('user_id', $idUsuario)
            ->orderByDesc('id')
            ->with('decididoPor')
            ->get();
    }

    public function inserirPendente(int $idUsuario): ProfessionalVerificationRequest
    {
        return ProfessionalVerificationRequest::query()->create([
            'user_id' => $idUsuario,
            'decided_by_user_id' => null,
            'decided_at' => null,
            'approved' => null,
            'notes' => null,
        ]);
    }
}
