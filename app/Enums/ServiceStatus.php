<?php

namespace App\Enums;

/**
 * Status do fluxo de um serviço na plataforma (valores persistidos em `services.status`).
 *
 * 1 = pagamento pendente, 2 = em análise, 3 = aceito pelo profissional, 4 = em prestação,
 * 5 = manutenção, 6 = suporte, 7 = concluído.
 */
enum ServiceStatus: int
{
    case PaymentPending = 1;
    case UnderReview = 2;
    case AcceptedByProfessional = 3;
    case InProgress = 4;
    case Maintenance = 5;
    case Support = 6;
    case Concluded = 7;

    public function label(): string
    {
        return match ($this) {
            self::PaymentPending => __('labels.service_status_payment_pending'),
            self::UnderReview => __('labels.service_status_under_review'),
            self::AcceptedByProfessional => __('labels.service_status_accepted'),
            self::InProgress => __('labels.service_status_in_progress'),
            self::Maintenance => __('labels.service_status_maintenance'),
            self::Support => __('labels.service_status_support'),
            self::Concluded => __('labels.service_status_concluded'),
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::PaymentPending => 'bg-warning text-dark',
            self::UnderReview => 'bg-secondary',
            self::AcceptedByProfessional => 'bg-info text-dark',
            self::InProgress => 'bg-primary',
            self::Maintenance => 'bg-dark',
            self::Support => 'service-status-badge-support',
            self::Concluded => 'bg-success',
        };
    }
}
