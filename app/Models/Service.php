<?php

namespace App\Models;

use App\Enums\ServiceStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'contractor_user_id',
        'professional_user_id',
        'title',
        'description',
        'address_postal_code',
        'address_street',
        'address_number',
        'address_complement',
        'address_neighborhood',
        'address_city',
        'address_state',
        'scheduled_start_date',
        'scheduled_end_date',
        'scheduled_start_time',
        'scheduled_end_time',
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
            'contractor_user_id' => 'integer',
            'professional_user_id' => 'integer',
            'service_value_cents' => 'integer',
            'value_withdrawn' => 'boolean',
            'scheduled_start_date' => 'date',
            'scheduled_end_date' => 'date',
        ];
    }

    /**
     * Endereço do serviço em uma linha (partes vazias ignoradas).
     */
    public function formattedAddress(): string
    {
        $bits = [];

        $street = trim((string) ($this->address_street ?? ''));
        if ($street !== '') {
            $num = trim((string) ($this->address_number ?? ''));
            $bits[] = $num !== '' ? $street.', nº '.$num : $street;
        }

        $comp = trim((string) ($this->address_complement ?? ''));
        if ($comp !== '') {
            $bits[] = $comp;
        }

        $nb = trim((string) ($this->address_neighborhood ?? ''));
        $city = trim((string) ($this->address_city ?? ''));
        $st = strtoupper(trim((string) ($this->address_state ?? '')));
        $cityState = $city;
        if ($st !== '') {
            $cityState .= ($cityState !== '' ? '/' : '').$st;
        }
        $loc = trim(implode(' — ', array_filter([$nb, $cityState !== '' ? $cityState : null])));
        if ($loc !== '') {
            $bits[] = $loc;
        }

        $cepDigits = preg_replace('/\D/', '', (string) ($this->address_postal_code ?? ''));
        if (strlen($cepDigits) === 8) {
            $bits[] = 'CEP '.substr($cepDigits, 0, 5).'-'.substr($cepDigits, 5, 3);
        } elseif (trim((string) $this->address_postal_code) !== '') {
            $bits[] = 'CEP '.trim((string) $this->address_postal_code);
        }

        return implode(' · ', $bits);
    }

    /**
     * Intervalo de datas do agendamento (uma data se início e fim forem o mesmo dia).
     */
    public function formattedDateRange(): ?string
    {
        $start = $this->scheduled_start_date;
        $end = $this->scheduled_end_date;
        if ($start === null && $end === null) {
            return null;
        }
        if ($start instanceof Carbon && $end instanceof Carbon && $start->isSameDay($end)) {
            return $start->format('d/m/Y');
        }

        return implode(' — ', array_filter([
            $start instanceof Carbon ? $start->format('d/m/Y') : null,
            $end instanceof Carbon ? $end->format('d/m/Y') : null,
        ]));
    }

    /**
     * Horário de início e fim (HH:mm).
     */
    public function formattedTimeRange(): ?string
    {
        $rawStart = $this->scheduled_start_time;
        $rawEnd = $this->scheduled_end_time;
        if ($rawStart === null && $rawEnd === null) {
            return null;
        }
        $start = $rawStart !== null ? substr((string) $rawStart, 0, 5) : '—';
        $end = $rawEnd !== null ? substr((string) $rawEnd, 0, 5) : '—';

        return $start.' — '.$end;
    }

    public function contractor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'contractor_user_id');
    }

    public function professionalUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'professional_user_id');
    }
}
