<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profession extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'practice_area_id',
        'title',
        'description',
        'slug',
        'icon',
        'show_on_homepage',
        'is_global_listing',
        'verificacao_exige_cnh',
        'verificacao_exige_certificado',
        'verificacao_exige_diploma',
    ];

    protected function casts(): array
    {
        return [
            'show_on_homepage' => 'boolean',
            'is_global_listing' => 'boolean',
            'verificacao_exige_cnh' => 'boolean',
            'verificacao_exige_certificado' => 'boolean',
            'verificacao_exige_diploma' => 'boolean',
        ];
    }

    public function practiceArea(): BelongsTo
    {
        return $this->belongsTo(PracticeArea::class);
    }

    public function professionals(): HasMany
    {
        return $this->hasMany(Professional::class);
    }
}
