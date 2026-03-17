<section id="sobre" class="py-5 bg-white">
    <div class="container">
        <div class="row align-items-center g-5">

            <div class="col-lg-6">

                <h2 class="fw-bold mb-4">
                    {{ __('labels.about_title_prefix') }}
                    <span class="text-gradient">{{ __('labels.about_title_brand') }}</span>
                </h2>

                <div class="space-y-3">

                    <div class="d-flex gap-3 mb-4">
                        <div style="font-size: 1.5rem; color: var(--secondary-color);">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold">{{ __('labels.verified_professionals_title') }}</h5>
                            <p class="text-muted">
                                {{ __('labels.verified_professionals_description') }}
                            </p>
                        </div>
                    </div>

                    <div class="d-flex gap-3 mb-4">
                        <div style="font-size: 1.5rem; color: var(--secondary-color);">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold">{{ __('labels.security_title') }}</h5>
                            <p class="text-muted">
                                {{ __('labels.security_description') }}
                            </p>
                        </div>
                    </div>

                    <div class="d-flex gap-3 mb-4">
                        <div style="font-size: 1.5rem; color: var(--secondary-color);">
                            <i class="fas fa-comments"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold">{{ __('labels.communication_title') }}</h5>
                            <p class="text-muted">
                                {{ __('labels.communication_description') }}
                            </p>
                        </div>
                    </div>

                    <div class="d-flex gap-3 mb-4">
                        <div style="font-size: 1.5rem; color: var(--secondary-color);">
                            <i class="fas fa-history"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold">{{ __('labels.history_title') }}</h5>
                            <p class="text-muted">
                                {{ __('labels.history_description') }}
                            </p>
                        </div>
                    </div>

                    <div class="d-flex gap-3">
                        <div style="font-size: 1.5rem; color: var(--secondary-color);">
                            <i class="fas fa-headset"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold">{{ __('labels.support_title') }}</h5>
                            <p class="text-muted">
                                {{ __('labels.support_description') }}
                            </p>
                        </div>
                    </div>

                </div>

            </div>

            <div class="col-lg-6 text-center">
                <div class="glass-effect p-5" style="animation: scaleIn 0.8s ease;">
                    <img src="{{ asset('img/logo.png') }}" alt="Batatais Serviços"
                        style="max-width: 300px; width: 100%;">
                </div>
            </div>

        </div>
    </div>
</section>
