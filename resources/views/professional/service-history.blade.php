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
                <a href="{{ route('dashboard') }}" class="text-decoration-none small text-muted service-history-back">
                    <i class="fas fa-arrow-left me-1" aria-hidden="true"></i>{{ __('labels.service_history_back_dashboard') }}
                </a>
            </div>

            <header class="service-history-hero mb-4">
                <div class="service-history-hero-inner">
                    <h1 class="h4 fw-bold mb-1 text-white">
                        <i class="fas fa-layer-group me-2 opacity-75" aria-hidden="true"></i>{{ __('labels.service_history_title') }}
                    </h1>
                    <p class="mb-0 small text-white-50">{{ __('labels.service_history_lead') }}</p>
                </div>
            </header>

            <form method="get" action="{{ route('professional.services.history') }}" class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <div class="row g-3 align-items-end">
                        <div class="col-12 col-md-4">
                            <label class="form-label small text-muted mb-1" for="history-q">{{ __('labels.service_history_search_label') }}</label>
                            <input type="search" name="q" id="history-q" value="{{ $filters['q'] }}" class="form-control" placeholder="{{ __('labels.service_history_search_placeholder') }}">
                        </div>
                        <div class="col-6 col-md-3">
                            <label class="form-label small text-muted mb-1" for="history-status">{{ __('labels.service_history_status_label') }}</label>
                            <select name="status" id="history-status" class="form-select">
                                <option value="">{{ __('labels.service_history_status_all') }}</option>
                                @foreach ($statuses as $status)
                                    <option value="{{ $status->value }}" @selected($filters['status'] === (string) $status->value)>{{ $status->label() }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6 col-md-3">
                            <label class="form-label small text-muted mb-1" for="history-sort">{{ __('labels.service_history_sort_label') }}</label>
                            <select name="sort" id="history-sort" class="form-select">
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
                <div class="card shadow-sm border-0 service-history-empty">
                    <div class="card-body text-center py-5 px-4">
                        <div class="service-history-empty-icon mb-3" aria-hidden="true">
                            <i class="fas fa-clipboard-list"></i>
                        </div>
                        <h2 class="h5 fw-semibold">{{ $filtersActive ? __('labels.service_history_no_results_title') : __('labels.service_history_empty_title') }}</h2>
                        <p class="text-muted mb-0 mx-auto" style="max-width: 28rem;">
                            {{ $filtersActive ? __('labels.service_history_no_results_text') : __('labels.service_history_empty_text') }}
                        </p>
                    </div>
                </div>
            @else
                <div class="row g-4">
                    @foreach ($services as $service)
                        <div class="col-12">
                            <article class="card service-history-card shadow-sm border-0 h-100">
                                <div class="card-body p-0">
                                    <div class="service-history-card-top px-4 py-3">
                                        <div class="d-flex flex-wrap align-items-start justify-content-between gap-2">
                                            <div class="flex-grow-1 pe-2">
                                                <span class="text-white-50 small text-uppercase fw-semibold letter-spacing">
                                                    {{ __('labels.service_history_contractor') }}
                                                </span>
                                                <div class="fw-bold text-white fs-6">
                                                    {{ $service->contractor->name }}
                                                </div>
                                                <h2 class="h6 fw-semibold text-white mt-2 mb-0 service-history-card-title">
                                                    {{ $service->title ?: __('labels.service_history_untitled') }}
                                                </h2>
                                            </div>
                                            <div class="text-md-end">
                                                <span
                                                    class="badge rounded-pill px-3 py-2 {{ $service->status->badgeClass() }}">{{ $service->status->label() }}</span>
                                                @if ($service->value_withdrawn && $service->status === ServiceStatus::Concluded)
                                                    <div class="small text-white-50 mt-2">
                                                        <i class="fas fa-check-circle me-1" aria-hidden="true"></i>{{ __('labels.service_history_withdrawn_yes') }}
                                                    </div>
                                                @elseif ($service->status === ServiceStatus::Concluded)
                                                    <div class="small text-white-50 mt-2">
                                                        <i class="fas fa-clock me-1" aria-hidden="true"></i>{{ __('labels.service_history_withdrawn_pending') }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="px-4 py-4">
                                        @if ($service->description)
                                            <div class="service-history-description text-body-secondary small mb-4">
                                                <span class="text-muted text-uppercase fw-semibold small d-block mb-1 letter-spacing">{{ __('labels.service_history_description') }}</span>
                                                <p class="mb-0">{{ $service->description }}</p>
                                            </div>
                                        @endif

                                        <div class="row g-4 mb-2">
                                            @if ($service->formattedDateRange())
                                                <div class="col-sm-6 col-lg-4">
                                                    <span class="text-muted small d-block mb-1">
                                                        <i class="fas fa-calendar-alt me-1" aria-hidden="true"></i>{{ __('labels.service_history_schedule') }}
                                                    </span>
                                                    <strong>{{ $service->formattedDateRange() }}</strong>
                                                </div>
                                            @endif
                                            @if ($service->formattedTimeRange())
                                                <div class="col-sm-6 col-lg-4">
                                                    <span class="text-muted small d-block mb-1">
                                                        <i class="fas fa-clock me-1" aria-hidden="true"></i>{{ __('labels.service_history_hours') }}
                                                    </span>
                                                    <strong>{{ $service->formattedTimeRange() }}</strong>
                                                </div>
                                            @endif
                                            @if ($service->formattedAddress() !== '')
                                                <div class="col-12 col-lg-4">
                                                    <span class="text-muted small d-block mb-1">
                                                        <i class="fas fa-map-marker-alt me-1" aria-hidden="true"></i>{{ __('labels.service_history_address') }}
                                                    </span>
                                                    <strong class="d-block service-history-address-text">{{ $service->formattedAddress() }}</strong>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="row g-4">
                                            <div class="col-sm-4">
                                                <span class="text-muted small d-block mb-1">{{ __('labels.service_history_value') }}</span>
                                                <strong class="fs-5 text-success service-history-value">
                                                    R$ {{ BrazilianDocuments::formatHourlyReaisFromCents($service->service_value_cents) }}
                                                </strong>
                                            </div>
                                            <div class="col-sm-4">
                                                <span class="text-muted small d-block mb-1">{{ __('labels.service_history_date') }}</span>
                                                <strong>{{ $service->created_at->timezone(config('app.timezone'))->format('d/m/Y H:i') }}</strong>
                                            </div>
                                            <div class="col-sm-4">
                                                <span class="text-muted small d-block mb-1">{{ __('labels.service_history_updated') }}</span>
                                                <strong>{{ $service->updated_at->timezone(config('app.timezone'))->format('d/m/Y H:i') }}</strong>
                                            </div>
                                        </div>

                                        @if ($service->contractor_feedback || $service->professional_feedback)
                                            <hr class="my-4 opacity-25">
                                            <div class="row g-3">
                                                @if ($service->contractor_feedback)
                                                    <div class="col-md-6">
                                                        <div class="service-history-feedback p-3 rounded-3 h-100">
                                                            <span class="small fw-semibold text-primary d-block mb-2">
                                                                <i class="fas fa-user me-1" aria-hidden="true"></i>{{ __('labels.service_history_feedback_contractor') }}
                                                            </span>
                                                            <p class="mb-0 small text-body-secondary">{{ $service->contractor_feedback }}</p>
                                                        </div>
                                                    </div>
                                                @endif
                                                @if ($service->professional_feedback)
                                                    <div class="col-md-6">
                                                        <div class="service-history-feedback service-history-feedback-pro p-3 rounded-3 h-100">
                                                            <span class="small fw-semibold d-block mb-2 service-history-feedback-pro-label">
                                                                <i class="fas fa-briefcase me-1" aria-hidden="true"></i>{{ __('labels.service_history_feedback_professional') }}
                                                            </span>
                                                            <p class="mb-0 small text-body-secondary">{{ $service->professional_feedback }}</p>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
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
</x-app-layout>
