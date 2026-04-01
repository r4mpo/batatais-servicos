<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Professional extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'profession_id',
        'title',
        'description',
        'hourly_rate_cents',
    ];

    protected function casts(): array
    {
        return [
            'hourly_rate_cents' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function profession(): BelongsTo
    {
        return $this->belongsTo(Profession::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(ProfessionalReview::class);
    }

    public function availabilities(): HasMany
    {
        return $this->hasMany(ProfessionalAvailability::class);
    }
}
