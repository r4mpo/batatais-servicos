<x-app-layout>

    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    @endpush

    <div class="py-4 dashboard-page">
        <div class="container-fluid px-0 px-sm-3" style="max-width: 1200px; margin: 0 auto;">
            @if (session('status') === 'professional-onboarding-complete')
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    {{ __('labels.professional_onboarding_success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                        aria-label="{{ __('labels.dashboard_alert_dismiss') }}"></button>
                </div>
            @endif
            @if (session('status') === 'professional-profile-updated')
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    {{ __('labels.professional_profile_updated_success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                        aria-label="{{ __('labels.dashboard_alert_dismiss') }}"></button>
                </div>
            @endif

            @if (auth()->user()?->isProfessional())
                <div class="card shadow-sm dashboard-finance-card bg-success bg-opacity-10 mb-4">
                    <div class="card-body">
                        <h2 class="h5 fw-bold mb-2 text-success">
                            <i class="fas fa-wallet me-2" aria-hidden="true"></i>{{ __('labels.dashboard_finance_title') }}
                        </h2>
                        <p class="small text-muted mb-3">{{ __('labels.dashboard_finance_disclaimer') }}</p>
                        <div class="row g-3 text-center text-md-start">
                            <div class="col-6 col-md-3">
                                <span class="text-muted small d-block">{{ __('labels.dashboard_finance_available_withdrawal') }}</span>
                                <strong class="fs-5 d-block">{{ __('labels.dashboard_finance_sample_available') }}</strong>
                            </div>
                            <div class="col-6 col-md-3">
                                <span class="text-muted small d-block">{{ __('labels.dashboard_finance_net_available') }}</span>
                                <strong class="fs-5 d-block">{{ __('labels.dashboard_finance_sample_net_available') }}</strong>
                            </div>
                            <div class="col-6 col-md-3">
                                <span class="text-muted small d-block">{{ __('labels.dashboard_finance_total_withdrawn') }}</span>
                                <strong class="fs-5 d-block">{{ __('labels.dashboard_finance_sample_total_withdrawn') }}</strong>
                            </div>
                            <div class="col-6 col-md-3">
                                <span class="text-muted small d-block">{{ __('labels.dashboard_finance_net_withdrawn') }}</span>
                                <strong class="fs-5 d-block">{{ __('labels.dashboard_finance_sample_net_withdrawn') }}</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-md-6 col-xl-4">
                        <div class="card h-100 shadow-sm dashboard-card">
                            <div class="card-body">
                                <h2 class="h5 fw-bold">
                                    <i class="fas fa-id-card me-2 text-primary" aria-hidden="true"></i>
                                    {{ __('labels.dashboard_card_professional_title') }}
                                </h2>
                                <p class="text-muted small mb-0">{{ __('labels.dashboard_card_professional_text') }}</p>
                            </div>
                            <div class="card-footer bg-transparent border-top pt-3">
                                <a href="{{ route('professional.setup') }}"
                                    class="btn btn-outline-primary dashboard-action-btn w-100">
                                    {{ __('labels.dashboard_card_professional_btn') }}
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-xl-4">
                        <div class="card h-100 shadow-sm dashboard-card">
                            <div class="card-body">
                                <h2 class="h5 fw-bold">
                                    <i class="fas fa-user-check me-2 text-success" aria-hidden="true"></i>
                                    {{ __('labels.dashboard_card_certification_title') }}
                                </h2>
                                <p class="text-muted small mb-0">{{ __('labels.dashboard_card_certification_text') }}</p>
                            </div>
                            <div class="card-footer bg-transparent border-top pt-3">
                                <button type="button"
                                    class="btn btn-outline-success dashboard-action-btn w-100">
                                    {{ __('labels.dashboard_card_certification_btn') }}
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-xl-4">
                        <div class="card h-100 shadow-sm dashboard-card">
                            <div class="card-body">
                                <h2 class="h5 fw-bold">
                                    <i class="fas fa-comments me-2 text-secondary" aria-hidden="true"></i>
                                    {{ __('labels.dashboard_card_messages_title') }}
                                </h2>
                                <p class="text-muted small mb-0">{{ __('labels.dashboard_card_messages_text') }}</p>
                            </div>
                            <div class="card-footer bg-transparent border-top pt-3">
                                <button type="button"
                                    class="btn btn-outline-secondary dashboard-action-btn w-100">
                                    {{ __('labels.dashboard_card_messages_btn') }}
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-xl-4">
                        <div class="card h-100 shadow-sm dashboard-card">
                            <div class="card-body">
                                <h2 class="h5 fw-bold">
                                    <i class="fas fa-chart-line me-2 text-dark" aria-hidden="true"></i>
                                    {{ __('labels.dashboard_card_history_title') }}
                                </h2>
                                <p class="text-muted small mb-0">{{ __('labels.dashboard_card_history_text') }}</p>
                            </div>
                            <div class="card-footer bg-transparent border-top pt-3">
                                <button type="button"
                                    class="btn btn-outline-dark dashboard-action-btn w-100">
                                    {{ __('labels.dashboard_card_history_btn') }}
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-xl-4">
                        <div class="card h-100 shadow-sm dashboard-card">
                            <div class="card-body">
                                <h2 class="h5 fw-bold">
                                    <i class="fas fa-folder-open me-2 text-info" aria-hidden="true"></i>
                                    {{ __('labels.dashboard_card_files_title') }}
                                </h2>
                                <p class="text-muted small mb-0">{{ __('labels.dashboard_card_files_text') }}</p>
                            </div>
                            <div class="card-footer bg-transparent border-top pt-3">
                                <button type="button"
                                    class="btn btn-outline-info dashboard-action-btn w-100">
                                    {{ __('labels.dashboard_card_files_btn') }}
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-xl-4">
                        <div class="card h-100 shadow-sm dashboard-card border-danger border-opacity-25">
                            <div class="card-body">
                                <h2 class="h5 fw-bold">
                                    <i class="fas fa-key me-2 text-danger" aria-hidden="true"></i>
                                    {{ __('labels.dashboard_card_password_title') }}
                                </h2>
                                <p class="text-muted small mb-0">{{ __('labels.dashboard_card_password_text') }}</p>
                            </div>
                            <div class="card-footer bg-transparent border-top pt-3">
                                <a href="{{ route('profile.edit') }}#dashboard-anchor-password"
                                    class="btn btn-outline-danger dashboard-action-btn w-100">
                                    {{ __('labels.dashboard_card_password_btn') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="card shadow-sm dashboard-card">
                    <div class="card-body">
                        <h2 class="h5 fw-bold">
                            {{ __('labels.dashboard_contractor_title') }}
                        </h2>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
