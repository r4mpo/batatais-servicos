<?php

namespace App\Models;

use App\Enums\ServiceStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'contractor_user_id',
        'professional_user_id',
        'status',
        'service_value_cents',
        'contractor_feedback',
        'professional_feedback',
        'value_withdrawn',
    ];

    protected function casts(): array
    {
        return [
            'status' => ServiceStatus::class,
            'service_value_cents' => 'integer',
            'value_withdrawn' => 'boolean',
        ];
    }

    public function contractor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'contractor_user_id');
    }

    public function professionalUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'professional_user_id');
    }

    /**
     * @return array{
     *     available_withdrawal_cents: int,
     *     net_available_cents: int,
     *     total_withdrawn_cents: int,
     *     net_withdrawn_cents: int
     * }
     */
    public static function financeSummaryForProfessionalUser(int $userId): array
    {
        $query = static::query()->where('professional_user_id', $userId);

        $availableGross = (clone $query)
            ->where('status', ServiceStatus::Concluded)
            ->where('value_withdrawn', false)
            ->sum('service_value_cents');

        $withdrawnGross = (clone $query)
            ->where('status', ServiceStatus::Concluded)
            ->where('value_withdrawn', true)
            ->sum('service_value_cents');

        $availableGross = (int) $availableGross;
        $withdrawnGross = (int) $withdrawnGross;

        return [
            'available_withdrawal_cents' => $availableGross,
            'net_available_cents' => (int) round($availableGross * 0.9),
            'total_withdrawn_cents' => $withdrawnGross,
            'net_withdrawn_cents' => (int) round($withdrawnGross * 0.9),
        ];
    }
}
