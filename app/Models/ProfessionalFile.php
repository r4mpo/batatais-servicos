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

    /**
     * Códigos de `file_type` (3 dígitos) — alinhados à migração
     * `2026_04_24_120000_migrate_professional_files_file_type_to_numeric_codes`.
     * 000: foto de vitrine (galeria pública); 001: verificação legada;
     * 002–006: documentos de verificação padrão.
     */
    public const FILE_TYPE_CODE_SHOWCASE = '000';

    /** Documento de verificação legado, sem seção ativa. */
    public const FILE_TYPE_CODE_OTHER = '001';

    public const FILE_TYPE_CODE_RG = '002';

    public const FILE_TYPE_CODE_CPF = '003';

    public const FILE_TYPE_CODE_CERTIFICATE = '004';

    public const FILE_TYPE_CODE_DIPLOMA = '005';

    public const FILE_TYPE_CODE_CNH = '006';

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
     * Tipos de documento de verificação (códigos) com seção em tela, na ordem de exibição.
     *
     * @return list<string>
     */
    public static function verificationDocumentTypes(): array
    {
        return [
            self::FILE_TYPE_CODE_RG,
            self::FILE_TYPE_CODE_CPF,
            self::FILE_TYPE_CODE_CERTIFICATE,
            self::FILE_TYPE_CODE_DIPLOMA,
            self::FILE_TYPE_CODE_CNH,
        ];
    }

    /**
     * Sufixo de chave em `labels` (`professional_files_doc_{sufixo}`) para o código dado
     * (seções de documento de verificação e bloco "outros"; default evita título vazio se surgir dado inesperado).
     */
    public static function fileTypeToTranslationKey(string $code): string
    {
        return match ($code) {
            self::FILE_TYPE_CODE_RG => 'rg',
            self::FILE_TYPE_CODE_CPF => 'cpf',
            self::FILE_TYPE_CODE_CERTIFICATE => 'certificate',
            self::FILE_TYPE_CODE_DIPLOMA => 'diploma',
            self::FILE_TYPE_CODE_CNH => 'cnh',
            self::FILE_TYPE_CODE_OTHER => 'other',
            default => 'other',
        };
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
            && ($this->file_type === null || $this->file_type === self::FILE_TYPE_CODE_SHOWCASE);
    }
}
