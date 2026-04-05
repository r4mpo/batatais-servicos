<?php

namespace App\Services\Professional;

use App\Models\User;
use App\Repositories\ProfessionRepository;
use App\Repositories\ProfessionalRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * Orquestra o cadastro e a edição do perfil profissional (dados em `professionals` + senha opcional em `users`).
 *
 * Contém a regra de negócio de criar vs. atualizar registro e a transação associada.
 */
class ProfessionalOnboardingService
{
    public function __construct(
        private readonly ProfessionRepository $professionRepository,
        private readonly ProfessionalRepository $professionalRepository,
        private readonly UserRepository $userRepository,
    ) {}

    /**
     * Monta os dados necessários para renderizar a tela de setup (lista de categorias + profissional atual, se existir).
     *
     * @param  User|null  $user  Usuário autenticado; fora do perfil profissional não há dados para a view.
     * @return array{professions: \Illuminate\Database\Eloquent\Collection, professional: \App\Models\Professional|null}|null  Payload da view ou `null` quando o acesso não deve ser concedido.
     */
    public function buildSetupViewModel(?User $user): ?array
    {
        if ($user === null || ! $user->isProfessional()) {
            return null;
        }

        return [
            'professions' => $this->professionRepository->orderedForProfessionalsFilter(),
            'professional' => $this->professionalRepository->findFirstForUserId($user->id),
        ];
    }

    /**
     * Aplica o formulário validado: grava ou atualiza `professionals` e, se informada, a nova senha em `users`.
     *
     * @param  User  $user  Dono do cadastro profissional.
     * @param  array<string, mixed>  $validated  Saída de {@see \App\Http\Requests\ProfessionalOnboardingRequest::validated()}.
     * @return string  Chave de status para flash (`professional-onboarding-complete` ou `professional-profile-updated`).
     */
    public function persistFromValidated(User $user, array $validated): string
    {
        $payload = $this->mapValidatedToProfessionalPayload($validated);
        $existing = $this->professionalRepository->findFirstForUserId($user->id);

        DB::transaction(function () use ($user, $validated, $payload, $existing) {
            if ($existing !== null) {
                $this->professionalRepository->update($existing, $payload);
            } else {
                $this->professionalRepository->create(array_merge(
                    ['user_id' => $user->id],
                    $payload
                ));
            }

            if (! empty($validated['password'])) {
                $this->userRepository->updatePasswordHash(
                    $user,
                    Hash::make($validated['password'])
                );
            }
        });

        return $existing !== null
            ? 'professional-profile-updated'
            : 'professional-onboarding-complete';
    }

    /**
     * Converte o array validado do request no formato persistido na tabela `professionals`.
     *
     * @param  array<string, mixed>  $validated  Campos já validados (CPF/CNPJ só dígitos, preço em reais decimal).
     * @return array<string, mixed>  Atributos aceitos pelo repositório (inclui `hourly_rate_cents`).
     */
    private function mapValidatedToProfessionalPayload(array $validated): array
    {
        return [
            'profession_id' => (int) $validated['profession_id'],
            'rg' => $validated['rg'],
            'cpf' => $validated['cpf'],
            'cnpj' => $validated['cnpj'],
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'hourly_rate_cents' => (int) round((float) $validated['hourly_rate_reais'] * 100),
        ];
    }
}
