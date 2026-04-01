<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProfessionalAvailability extends Model
{
    protected $fillable = [
        'professional_id',
        'day_of_week',
        'starts_at',
        'ends_at',
        'is_full_day',
    ];

    protected function casts(): array
    {
        return [
            'day_of_week' => 'integer',
            'is_full_day' => 'boolean',
        ];
    }

    public function professional(): BelongsTo
    {
        return $this->belongsTo(Professional::class);
    }
}
