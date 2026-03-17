<x-guest-layout>
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    @endpush

    <div class="auth-container">
        <div class="auth-left">

            <a href="{{ route('dashboard') }}" class="back-link">
                <i class="fas fa-arrow-left"></i>
                <span>{{ __('labels.back') }}</span>
            </a>

            <div class="auth-left-content">
                <img src="{{ asset('img/logo.png') }}" alt="Batatais Serviços">
                <h2>{{ __('labels.secure_area') }}</h2>

                <p>
                    {{ __('labels.confirm_password_description') }}
                </p>

                <div class="auth-features">
                    <div class="feature-item">
                        <i class="fas fa-lock"></i>
                        <span>{{ __('labels.features.data_protection') }}</span>
                    </div>

                    <div class="feature-item">
                        <i class="fas fa-shield-alt"></i>
                        <span>{{ __('labels.features.secure_environment') }}</span>
                    </div>

                    <div class="feature-item">
                        <i class="fas fa-user-shield"></i>
                        <span>{{ __('labels.features.identity_verification') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="auth-right">
            <div class="auth-form-wrapper">

                <div class="auth-form-header">
                    <h3>{{ __('labels.confirm_password_title') }}</h3>
                    <p>{{ __('labels.confirm_password_subtitle') }}</p>
                </div>

                <form method="POST" action="{{ route('password.confirm') }}" class="form-content">
                    @csrf

                    <div class="form-group-custom">
                        <label class="form-label-custom" for="password">
                            {{ __('labels.password_label') }}
                        </label>

                        <div class="input-group-custom">
                            <input
                                id="password"
                                type="password"
                                name="password"
                                class="form-control-custom"
                                required
                                autocomplete="current-password"
                                placeholder="{{ __('labels.password_placeholder') }}"
                            >

                            <i class="fas fa-eye password-toggle" id="togglePassword"></i>
                        </div>

                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <button type="submit" class="btn-primary">
                        <i class="fas fa-check me-1"></i>
                        {{ __('labels.confirm_password_button') }}
                    </button>

                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
