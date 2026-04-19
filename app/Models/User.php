<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Services\User\UserProfilePhotoService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    const PROFILE_CONTRACTOR = '000';

    const PROFILE_PROFESSIONAL = '001';

    /**
     * Caminho relativo à pasta public/ onde ficam as fotos de perfil (nome do arquivo em hash no banco).
     */
    public const PROFILE_PHOTO_PUBLIC_DIR = 'img/docs/profile';

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
        'profile',
        'profile_photo',
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

    /**
     * URL pública da foto de perfil (rota com id criptografado, sem expor o arquivo diretamente).
     */
    public function profilePhotoUrl(): ?string
    {
        if ($this->profile_photo === null || $this->profile_photo === '') {
            return null;
        }

        return app(UserProfilePhotoService::class)->publicUrl($this);
    }
}
