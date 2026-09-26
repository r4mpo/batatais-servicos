<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class ContractorServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        $usuario = $this->user();

        return $usuario instanceof User && $usuario->isContractor();
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $rules = [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'service_value_reais' => ['required', 'numeric', 'min:1', 'max:999999.99'],
            'scheduled_start_date' => ['required', 'date'],
            'scheduled_end_date' => ['required', 'date', 'after_or_equal:scheduled_start_date'],
            'scheduled_start_time' => ['nullable', 'date_format:H:i'],
            'scheduled_end_time' => ['nullable', 'date_format:H:i'],
            'address_postal_code' => ['nullable', 'string', 'max:9'],
            'address_street' => ['nullable', 'string', 'max:255'],
            'address_number' => ['nullable', 'string', 'max:32'],
            'address_complement' => ['nullable', 'string', 'max:128'],
            'address_neighborhood' => ['nullable', 'string', 'max:128'],
            'address_city' => ['nullable', 'string', 'max:128'],
            'address_state' => ['nullable', 'string', 'size:2'],
            'professional_id' => ['nullable', 'integer', Rule::exists('professionals', 'id')],
        ];

        return $rules;
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'scheduled_end_date.after_or_equal' => __('labels.contractor_services_end_before_start'),
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $inicio = (string) $this->input('scheduled_start_date', '');
            $fim = (string) $this->input('scheduled_end_date', '');
            $horaInicio = (string) $this->input('scheduled_start_time', '');
            $horaFim = (string) $this->input('scheduled_end_time', '');

            if ($inicio !== '' && $fim === $inicio && $horaInicio !== '' && $horaFim !== '' && $horaFim < $horaInicio) {
                $validator->errors()->add('scheduled_end_time', __('labels.contractor_services_end_before_start'));
            }
        });
    }

    protected function prepareForValidation(): void
    {
        $valor = $this->input('service_value_reais');
        if (is_string($valor)) {
            $texto = trim($valor);
            if (str_contains($texto, ',')) {
                $texto = str_replace(['.', ' '], '', $texto);
                $texto = str_replace(',', '.', $texto);
            }
            $this->merge(['service_value_reais' => $texto]);
        } elseif (is_numeric($valor)) {
            $this->merge(['service_value_reais' => (string) $valor]);
        }

        $profissional = $this->input('professional_id');
        if ($profissional === '' || $profissional === null) {
            $this->merge(['professional_id' => null]);
        }

        $estado = $this->input('address_state');
        if (is_string($estado)) {
            $this->merge(['address_state' => strtoupper(trim($estado))]);
        }
    }
}
