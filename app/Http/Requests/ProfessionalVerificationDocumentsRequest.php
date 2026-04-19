<?php

namespace App\Http\Requests;

use App\Models\ProfessionalFile;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfessionalVerificationDocumentsRequest extends FormRequest
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
            'document_type' => ['required', 'string', Rule::in(ProfessionalFile::verificationDocumentTypes())],
            'documents' => ['required', 'array', 'min:1', 'max:10'],
            'documents.*' => ['required', 'image', 'max:10240', 'mimes:jpeg,jpg,png,webp,gif'],
        ];
    }
}
