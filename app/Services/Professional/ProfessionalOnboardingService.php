<?php

namespace App\Services\Professional;

use App\Models\User;
use App\Repositories\ProfessionRepository;
use App\Repositories\ProfessionalRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * Regras do fluxo de cadastro/edição do profissional (`professionals` + senha opcional em `users`).
 */
class ProfessionalOnboardingService
{
    public function __construct(
        private readonly ProfessionRepository $professionRepository,
        private readonly ProfessionalRepository $professionalRepository,
        private readonly UserRepository $userRepository,
    ) {}

    /**
     * Dados para a view de setup (categorias + registro atual, se existir).
     *
     * Passo a passo:
     * 1. Garantir usuário autenticado com perfil profissional.
     * 2. Carregar lista de profissões e o primeiro `professionals` da conta.
     *
     * @return array{professions: \Illuminate\Database\Eloquent\Collection, professional: \App\Models\Professional|null}|null
     */
    public function montarModeloDaViewDeCadastro(?User $usuario): ?array
    {
        if ($usuario === null || ! $usuario->isProfessional()) {
            return null;
        }

        return [
            'professions' => $this->professionRepository->orderedForProfessionalsFilter(),
            'professional' => $this->professionalRepository->findFirstForUserId($usuario->id),
        ];
    }

    /**
     * Persiste formulário validado: cria ou atualiza `professionals` e, se houver, nova senha.
     *
     * Passo a passo:
     * 1. Mapear payload validado para colunas de `professionals` (inclui centavos da hora).
     * 2. Descobrir se já existe linha do usuário em `professionals`.
     * 3. Em transação: atualizar ou inserir; se veio senha, atualizar hash em `users`.
     * 4. Retornar chave de flash conforme criação ou edição.
     *
     * @param  array<string, mixed>  $validado
     */
    public function persistirAPartirDoValidado(User $usuario, array $validado): string
    {
        $dadosProfissional = $this->mapearValidadoParaProfissional($validado);
        $existente = $this->professionalRepository->findFirstForUserId($usuario->id);

        DB::transaction(function () use ($usuario, $validado, $dadosProfissional, $existente) {
            if ($existente !== null) {
                $this->professionalRepository->update($existente, $dadosProfissional);
            } else {
                $this->professionalRepository->create(array_merge(
                    ['user_id' => $usuario->id],
                    $dadosProfissional
                ));
            }

            if (! empty($validado['password'])) {
                $this->userRepository->updatePasswordHash(
                    $usuario,
                    Hash::make($validado['password'])
                );
            }
        });

        return $existente !== null
            ? 'professional-profile-updated'
            : 'professional-onboarding-complete';
    }

    /**
     * Passo a passo:
     * 1. Copiar ids e textos já normalizados pelo request.
     * 2. Converter preço em reais (decimal) para `hourly_rate_cents` (inteiro).
     *
     * @param  array<string, mixed>  $validado
     * @return array<string, mixed>
     */
    private function mapearValidadoParaProfissional(array $validado): array
    {
        return [
            'profession_id' => (int) $validado['profession_id'],
            'rg' => $validado['rg'],
            'cpf' => $validado['cpf'],
            'cnpj' => $validado['cnpj'],
            'title' => $validado['title'],
            'description' => $validado['description'] ?? null,
            'hourly_rate_cents' => (int) round((float) $validado['hourly_rate_reais'] * 100),
        ];
    }
}
