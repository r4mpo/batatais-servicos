<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Enviou o formulário de “solicitar verificação” (leitura dos avisos + confirmação).
 */
class SolicitacaoVerificacaoProfissionalRequest extends FormRequest
{
    public function authorize(): bool
    {
        $usuario = $this->user();

        return $usuario !== null && $usuario->isProfessional();
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'confirmo_termos' => ['accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'confirmo_termos.accepted' => 'Confirme o item acima para enviar: a caixa de leitura do aviso é obrigatória.',
        ];
    }
}
