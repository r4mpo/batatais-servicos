<x-guest-layout>
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    @endpush

    <div class="auth-container">

        <div class="auth-left">
            <a href="{{ route('login') }}" class="back-link">
                <i class="fas fa-arrow-left"></i>
                <span>{{ __('labels.back') }}</span>
            </a>

            <div class="auth-left-content">
                <img src="{{ asset('img/logo.png') }}" alt="Batatais Serviços">

                <h2>{{ __('labels.recover_password_title') }}</h2>

                <p>{{ __('labels.recover_password_description') }}</p>

                <div class="auth-features">

                    <div class="feature-item">
                        <i class="fas fa-envelope"></i>
                        <span>{{ __('labels.feature_email_link') }}</span>
                    </div>

                    <div class="feature-item">
                        <i class="fas fa-shield-alt"></i>
                        <span>{{ __('labels.feature_secure_process') }}</span>
                    </div>

                    <div class="feature-item">
                        <i class="fas fa-user-lock"></i>
                        <span>{{ __('labels.feature_account_protection') }}</span>
                    </div>

                </div>

            </div>
        </div>

        <div class="auth-right">
            <div class="auth-form-wrapper">

                <div class="auth-form-header">
                    <h3>{{ __('labels.form_title') }}</h3>
                    <p>{{ __('labels.form_description') }}</p>
                </div>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('password.email') }}" class="form-content">
                    @csrf

                    <div class="form-group-custom">

                        <label for="email" class="form-label-custom">
                            {{ __('labels.email_label') }}
                        </label>

                        <input
                            id="email"
                            type="email"
                            name="email"
                            class="form-control-custom"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            placeholder="{{ __('labels.email_placeholder') }}"
                        >

                        <x-input-error :messages="$errors->get('email')" class="mt-2" />

                    </div>

                    <button type="submit" class="btn-primary">
                        <i class="fas fa-paper-plane me-1"></i>
                        {{ __('labels.send_reset_link') }}
                    </button>

                </form>

            </div>
        </div>

    </div>
</x-guest-layout>
