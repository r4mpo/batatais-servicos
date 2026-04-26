<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Fila e histórico de solicitações de verificação (decisão futura no back-office).
 */
class ProfessionalVerificationRequest extends Model
{
    use SoftDeletes;

    protected $table = 'professional_verification_requests';

    protected $fillable = [
        'user_id',
        'decided_by_user_id',
        'decided_at',
        'approved',
        'notes',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'approved' => 'boolean',
            'decided_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function decididoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'decided_by_user_id');
    }
}
