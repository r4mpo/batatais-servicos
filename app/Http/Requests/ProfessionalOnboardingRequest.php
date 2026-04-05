<?php

namespace App\Http\Requests;

use App\Models\Professional;
use App\Support\BrazilianDocuments;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ProfessionalOnboardingRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();

        return $user !== null && $user->isProfessional();
    }

    /**
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
                    $digits = is_string($value) ? $value : '';
                    if (! BrazilianDocuments::isValidCpf($digits)) {
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

    protected function prepareForValidation(): void
    {
        $hourly = $this->input('hourly_rate_reais');
        if (is_string($hourly)) {
            $t = trim($hourly);
            if (str_contains($t, ',')) {
                $t = str_replace(['.', ' '], '', $t);
                $t = str_replace(',', '.', $t);
            }
            $this->merge(['hourly_rate_reais' => $t]);
        } elseif (is_numeric($hourly)) {
            $this->merge(['hourly_rate_reais' => (string) $hourly]);
        }

        $cpf = BrazilianDocuments::onlyDigits((string) $this->input('cpf', ''));
        $cnpjDigits = BrazilianDocuments::onlyDigits((string) $this->input('cnpj', ''));

        $pw = $this->input('password');
        if ($pw === '' || $pw === null) {
            $this->merge([
                'password' => null,
                'password_confirmation' => null,
            ]);
        }

        $this->merge([
            'cpf' => $cpf,
            'cnpj' => $cnpjDigits !== '' ? $cnpjDigits : null,
            'rg' => trim((string) $this->input('rg', '')),
        ]);
    }

    private function currentProfessional(): ?Professional
    {
        return $this->user()?->professionals()->first();
    }
}
