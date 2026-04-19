<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class ProfessionalFile extends Model
{
    use SoftDeletes;
    public const KIND_VERIFICATION_DOCUMENT = 'verification_document';

    public const KIND_PUBLIC_PHOTO = 'public_photo';

    /** Fotos de vitrine / página pública (kind = {@see KIND_PUBLIC_PHOTO}). */
    public const FILE_TYPE_SHOWCASE = 'showcase';

    /** Documento legado sem classificação (migração). */
    public const DOCUMENT_TYPE_OTHER = 'other';

    public const DOCUMENT_TYPE_RG = 'rg';

    public const DOCUMENT_TYPE_CPF = 'cpf';

    public const DOCUMENT_TYPE_CERTIFICATE = 'certificate';

    public const DOCUMENT_TYPE_DIPLOMA = 'diploma';

    public const DOCUMENT_TYPE_CNH = 'cnh';

    protected $fillable = [
        'professional_id',
        'kind',
        'file_type',
        'disk',
        'path',
        'original_name',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }

    /**
     * Em exclusão definitiva (`forceDelete`): remove bytes do storage conforme colunas `disk` e `path`.
     */
    protected static function booted(): void
    {
        static::forceDeleting(function (ProfessionalFile $arquivo): void {
            Storage::disk($arquivo->disk)->delete($arquivo->path);
        });
    }

    public function professional(): BelongsTo
    {
        return $this->belongsTo(Professional::class);
    }

    /**
     * Tipos de documento de verificação que o profissional pode enviar em seções separadas.
     *
     * @return list<string>
     */
    public static function verificationDocumentTypes(): array
    {
        return [
            self::DOCUMENT_TYPE_RG,
            self::DOCUMENT_TYPE_CPF,
            self::DOCUMENT_TYPE_CERTIFICATE,
            self::DOCUMENT_TYPE_DIPLOMA,
            self::DOCUMENT_TYPE_CNH,
        ];
    }

    public function isVerificationDocument(): bool
    {
        return $this->kind === self::KIND_VERIFICATION_DOCUMENT;
    }

    public function isPublicPhoto(): bool
    {
        return $this->kind === self::KIND_PUBLIC_PHOTO;
    }

    public function isShowcasePhoto(): bool
    {
        return $this->kind === self::KIND_PUBLIC_PHOTO
            && ($this->file_type === self::FILE_TYPE_SHOWCASE || $this->file_type === null);
    }
}
