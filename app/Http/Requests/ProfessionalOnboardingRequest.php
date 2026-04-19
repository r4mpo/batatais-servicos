<?php

namespace App\Http\Requests;

use App\Models\Professional;
use App\Repositories\ProfessionalRepository;
use App\Support\BrazilianDocuments;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

/**
 * Validação e normalização dos dados do formulário de cadastro/edição de profissional.
 */
class ProfessionalOnboardingRequest extends FormRequest
{
    /**
     * Permite envio apenas para usuários com perfil profissional autenticados.
     */
    public function authorize(): bool
    {
        $usuario = $this->user();

        return $usuario !== null && $usuario->isProfessional();
    }

    /**
     * Regras de validação após {@see prepareForValidation()} (CPF/CNPJ já só com dígitos).
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $editingId = $this->currentProfessional()?->id;

        return [
            'profession_id' => ['required', 'integer', 'exists:professions,id'],
            'rg' => ['required', 'string', 'max:32'],
            'cpf' => [
                'required',
                'string',
                'size:11',
                function (string $attribute, mixed $value, \Closure $fail) {
                    $digitos = is_string($value) ? $value : '';
                    if (! BrazilianDocuments::isValidCpf($digitos)) {
                        $fail(__('validation.custom.cpf.invalid'));
                    }
                },
                Rule::unique('professionals', 'cpf')->ignore($editingId),
            ],
            'cnpj' => [
                'nullable',
                'string',
                function (string $attribute, mixed $value, \Closure $fail) {
                    if ($value === null || $value === '') {
                        return;
                    }
                    if (! is_string($value)) {
                        $fail(__('validation.custom.cnpj.invalid'));

                        return;
                    }
                    if (strlen($value) !== 14) {
                        $fail(__('validation.custom.cnpj.invalid'));

                        return;
                    }
                    if (! BrazilianDocuments::isValidCnpj($value)) {
                        $fail(__('validation.custom.cnpj.invalid'));
                    }
                },
                Rule::unique('professionals', 'cnpj')->ignore($editingId),
            ],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'hourly_rate_reais' => ['required', 'numeric', 'min:1', 'max:500'],
            'password' => ['nullable', 'string', 'confirmed', Password::defaults()],
        ];
    }

    /**
     * Normaliza valores antes das regras: preço em formato BR, documentos só dígitos, senha vazia vira null.
     */
    protected function prepareForValidation(): void
    {
        $valorHora = $this->input('hourly_rate_reais');
        if (is_string($valorHora)) {
            $textoNormalizado = trim($valorHora);
            if (str_contains($textoNormalizado, ',')) {
                $textoNormalizado = str_replace(['.', ' '], '', $textoNormalizado);
                $textoNormalizado = str_replace(',', '.', $textoNormalizado);
            }
            $this->merge(['hourly_rate_reais' => $textoNormalizado]);
        } elseif (is_numeric($valorHora)) {
            $this->merge(['hourly_rate_reais' => (string) $valorHora]);
        }

        $cpfDigitos = BrazilianDocuments::onlyDigits((string) $this->input('cpf', ''));
        $cnpjDigitos = BrazilianDocuments::onlyDigits((string) $this->input('cnpj', ''));

        $senhaInformada = $this->input('password');
        if ($senhaInformada === '' || $senhaInformada === null) {
            $this->merge([
                'password' => null,
                'password_confirmation' => null,
            ]);
        }

        $this->merge([
            'cpf' => $cpfDigitos,
            'cnpj' => $cnpjDigitos !== '' ? $cnpjDigitos : null,
            'rg' => trim((string) $this->input('rg', '')),
        ]);
    }

    /**
     * Profissional em edição (se existir), resolvido pelo {@see ProfessionalRepository} via container da requisição.
     */
    private function currentProfessional(): ?Professional
    {
        $usuario = $this->user();
        if ($usuario === null) {
            return null;
        }

        $repositorio = $this->container->make(ProfessionalRepository::class);

        return $repositorio->findFirstForUserId($usuario->id);
    }
}
