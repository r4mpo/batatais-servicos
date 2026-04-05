<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    const PROFILE_CONTRACTOR = '000';
    const PROFILE_PROFESSIONAL = '001';

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Perfis de profissional vinculados à conta (normalmente um por usuário no fluxo atual).
     */
    public function professionals(): HasMany
    {
        return $this->hasMany(Professional::class);
    }

    /**
     * Avaliações que este usuário deixou em perfis de profissionais.
     */
    public function professionalReviews(): HasMany
    {
        return $this->hasMany(ProfessionalReview::class);
    }

    /**
     * Indica se o campo `profile` corresponde ao código de usuário prestador de serviços.
     */
    public function isProfessional(): bool
    {
        return $this->profile === self::PROFILE_PROFESSIONAL;
    }
}
