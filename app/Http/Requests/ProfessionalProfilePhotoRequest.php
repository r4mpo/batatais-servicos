<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfessionalProfilePhotoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, array<int, string|\Illuminate\Validation\Rules\File>>
     */
    public function rules(): array
    {
        return [
            'photo' => ['required', 'image', 'max:5120', 'mimes:jpeg,jpg,png,webp,gif'],
        ];
    }
}
