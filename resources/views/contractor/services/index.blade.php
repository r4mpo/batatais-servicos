@php
    use App\Enums\ServiceStatus;
    use App\Support\BrazilianDocuments;
@endphp

<x-app-layout>
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/service-history.css') }}">
    @endpush

    <div class="service-history-page py-2">
        <div class="container-fluid px-0 px-sm-3" style="max-width: 960px; margin: 0 auto;">
            <div class="mb-3">
                <a href="{{ route('dashboard') }}" class="text-decoration-none small text-muted">
                    <i class="fas fa-arrow-left me-1" aria-hidden="true"></i>{{ __('labels.service_history_back_dashboard') }}
                </a>
            </div>

            @if (session('status') === 'contractor-service-deleted')
                <div class="alert alert-success">{{ __('labels.contractor_services_deleted') }}</div>
            @endif

            <header class="service-history-hero mb-4">
                <div class="service-history-hero-inner d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
                    <div>
                        <h1 class="h4 fw-bold mb-1 text-white">{{ __('labels.contractor_services_title') }}</h1>
                        <p class="mb-0 small text-white-50">{{ __('labels.contractor_services_lead') }}</p>
                    </div>
                    <a href="{{ route('contractor.services.create') }}" class="btn btn-light">{{ __('labels.contractor_services_new') }}</a>
                </div>
            </header>

            <form method="get" action="{{ route('contractor.services.index') }}" class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <div class="row g-3 align-items-end">
                        <div class="col-12 col-md-4">
                            <label class="form-label small text-muted mb-1" for="contractor-q">{{ __('labels.service_history_search_label') }}</label>
                            <input type="search" name="q" id="contractor-q" value="{{ $filters['q'] }}" class="form-control" placeholder="{{ __('labels.service_history_search_placeholder') }}">
                        </div>
                        <div class="col-6 col-md-3">
                            <label class="form-label small text-muted mb-1" for="contractor-status">{{ __('labels.service_history_status_label') }}</label>
                            <select name="status" id="contractor-status" class="form-select">
                                <option value="">{{ __('labels.service_history_status_all') }}</option>
                                @foreach ($statuses as $status)
                                    <option value="{{ $status->value }}" @selected($filters['status'] === (string) $status->value)>{{ $status->label() }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6 col-md-3">
                            <label class="form-label small text-muted mb-1" for="contractor-sort">{{ __('labels.service_history_sort_label') }}</label>
                            <select name="sort" id="contractor-sort" class="form-select">
                                <option value="recent" @selected($filters['sort'] === 'recent')>{{ __('labels.service_history_sort_recent') }}</option>
                                <option value="oldest" @selected($filters['sort'] === 'oldest')>{{ __('labels.service_history_sort_oldest') }}</option>
                                <option value="value_desc" @selected($filters['sort'] === 'value_desc')>{{ __('labels.service_history_sort_value_desc') }}</option>
                                <option value="value_asc" @selected($filters['sort'] === 'value_asc')>{{ __('labels.service_history_sort_value_asc') }}</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-2 d-grid">
                            <button type="submit" class="btn btn-primary">{{ __('labels.service_history_filter_submit') }}</button>
                        </div>
                    </div>
                </div>
            </form>

            @if ($services->isEmpty())
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center py-5">
                        <h2 class="h5">{{ $filtersActive ? __('labels.service_history_no_results_title') : __('labels.contractor_services_empty_title') }}</h2>
                        <p class="text-muted mb-0">{{ $filtersActive ? __('labels.service_history_no_results_text') : __('labels.contractor_services_empty_text') }}</p>
                    </div>
                </div>
            @else
                <div class="row g-4">
                    @foreach ($services as $service)
                        <div class="col-12">
                            <article class="card service-history-card shadow-sm border-0">
                                <div class="card-body">
                                    <div class="d-flex flex-wrap justify-content-between gap-2 mb-2">
                                        <div>
                                            <div class="small text-muted">{{ __('labels.contractor_services_professional') }}</div>
                                            <div class="fw-bold">{{ $service->professionalUser->name ?? __('labels.contractor_services_professional_pending') }}</div>
                                            <h2 class="h6 mt-2 mb-0">
                                                <a href="{{ route('contractor.services.show', $service) }}" class="text-decoration-none">{{ $service->title ?: __('labels.service_history_untitled') }}</a>
                                            </h2>
                                        </div>
                                        <span class="{{ $service->status->pillClass() }}">
                                            <i class="fas {{ $service->status->icon() }}" aria-hidden="true"></i>
                                            {{ $service->status->label() }}
                                        </span>
                                    </div>
                                    <div class="fw-semibold text-success mb-3">R$ {{ BrazilianDocuments::formatHourlyReaisFromCents($service->service_value_cents) }}</div>
                                    @if ($service->status === ServiceStatus::PaymentPending)
                                        <div class="d-flex flex-wrap gap-2">
                                            <form method="post" action="{{ route('contractor.services.pay', $service) }}">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm">{{ __('labels.contractor_services_pay') }}</button>
                                            </form>
                                            <a href="{{ route('contractor.services.edit', $service) }}" class="btn btn-outline-primary btn-sm">{{ __('labels.contractor_services_edit') }}</a>
                                            <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#contractor-delete-modal" data-delete-action="{{ route('contractor.services.destroy', $service) }}" data-delete-name="{{ $service->title ?: __('labels.service_history_untitled') }}">
                                                {{ __('labels.contractor_services_delete') }}
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </article>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4">
                    {{ $services->links('pagination.history') }}
                </div>
            @endif
        </div>
    </div>

    @include('contractor.services.delete-modal')
</x-app-layout>
