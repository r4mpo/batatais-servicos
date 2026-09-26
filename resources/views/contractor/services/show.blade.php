@php
    use App\Enums\ServiceStatus;
    use App\Support\BrazilianDocuments;
@endphp

<x-app-layout>
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/service-history.css') }}">
    @endpush

    <div class="py-4">
        <div class="container" style="max-width: 760px;">
            <p class="mb-3">
                <a href="{{ route('contractor.services.index') }}" class="text-decoration-none small text-muted">
                    <i class="fas fa-arrow-left me-1" aria-hidden="true"></i>{{ __('labels.contractor_services_back') }}
                </a>
            </p>

            @if (session('status') === 'contractor-service-created')
                <div class="alert alert-success">{{ __('labels.contractor_services_created') }}</div>
            @elseif (session('status') === 'contractor-service-updated')
                <div class="alert alert-success">{{ __('labels.contractor_services_updated') }}</div>
            @elseif (session('status') === 'contractor-service-paid')
                <div class="alert alert-success">{{ __('labels.contractor_services_paid') }}</div>
            @endif

            <article class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between gap-2 flex-wrap">
                        <h1 class="h4">{{ $service->title ?: __('labels.service_history_untitled') }}</h1>
                        <span class="{{ $service->status->pillClass() }}">
                            <i class="fas {{ $service->status->icon() }}" aria-hidden="true"></i>
                            {{ $service->status->label() }}
                        </span>
                    </div>
                    <p class="text-muted mb-2">{{ __('labels.contractor_services_professional') }}: {{ $service->professionalUser->name ?? __('labels.contractor_services_professional_pending') }}</p>
                    @if ($service->description)
                        <p>{{ $service->description }}</p>
                    @endif
                    <p class="fs-4 text-success fw-bold">R$ {{ BrazilianDocuments::formatHourlyReaisFromCents($service->service_value_cents) }}</p>
                    <p class="mb-1"><strong>{{ __('labels.service_history_schedule') }}:</strong> {{ $service->formattedDateRange() ?: '—' }}</p>
                    <p class="mb-1"><strong>{{ __('labels.service_history_hours') }}:</strong> {{ $service->formattedTimeRange() ?: '—' }}</p>
                    <p><strong>{{ __('labels.service_history_address') }}:</strong> {{ $service->formattedAddress() !== '' ? $service->formattedAddress() : '—' }}</p>

                    @if ($service->status === ServiceStatus::PaymentPending)
                        <p class="small text-muted">{{ __('labels.contractor_services_pay_hint') }}</p>
                        <div class="d-flex flex-wrap gap-2">
                            <form method="post" action="{{ route('contractor.services.pay', $service) }}">
                                @csrf
                                <button type="submit" class="btn btn-success">{{ __('labels.contractor_services_pay') }}</button>
                            </form>
                            <a href="{{ route('contractor.services.edit', $service) }}" class="btn btn-outline-primary">{{ __('labels.contractor_services_edit') }}</a>
                            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#contractor-delete-modal" data-delete-action="{{ route('contractor.services.destroy', $service) }}" data-delete-name="{{ $service->title ?: __('labels.service_history_untitled') }}">
                                {{ __('labels.contractor_services_delete') }}
                            </button>
                        </div>
                    @endif
                </div>
            </article>
        </div>
    </div>

    @include('contractor.services.delete-modal')
</x-app-layout>
