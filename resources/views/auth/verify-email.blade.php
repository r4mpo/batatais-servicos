<x-guest-layout>
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    @endpush

    <div class="auth-container">

        {{-- LADO ESQUERDO --}}
        <div class="auth-left">

            <a href="{{ route('dashboard') }}" class="back-link">
                <i class="bi bi-arrow-left"></i>
                {{ __('labels.back_to_site') }}
            </a>

            <div class="auth-left-content">

                <img src="{{ asset('img/logo.png') }}" alt="Logo">

                <h2>{{ __('labels.confirm_email_title') }}</h2>

                <p>
                    {{ __('labels.confirm_email_description') }}
                </p>

                <div class="auth-features">

                    <div class="feature-item">
                        <i class="bi bi-envelope-check"></i>
                        {{ __('labels.feature_fast_verification') }}
                    </div>

                    <div class="feature-item">
                        <i class="bi bi-shield-lock"></i>
                        {{ __('labels.feature_security_protection') }}
                    </div>

                    <div class="feature-item">
                        <i class="bi bi-check-circle"></i>
                        {{ __('labels.feature_activate_account') }}
                    </div>

                </div>

            </div>
        </div>

        {{-- LADO DIREITO --}}
        <div class="auth-right">

            <div class="auth-form-wrapper">

                <div class="auth-form-header">
                    <h3>{{ __('labels.email_verification_title') }}</h3>
                    <p>{{ __('labels.email_verification_description') }}</p>
                </div>

                <div class="auth-alert">
                    <i class="bi bi-info-circle"></i>
                    {{ __('labels.check_inbox_message') }}
                </div>

                @if (session('status') == 'verification-link-sent')
                    <div class="auth-alert">
                        <i class="bi bi-check-circle"></i>
                        {{ __('labels.verification_link_sent') }}
                    </div>
                @endif

                <div class="form-content">

                    {{-- REENVIAR EMAIL --}}
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf

                        <button type="submit" class="btn-primary">
                            <i class="bi bi-envelope"></i>
                            {{ __('labels.resend_verification_email') }}
                        </button>

                    </form>

                    <div class="divider-custom">
                        <span>{{ __('labels.or') }}</span>
                    </div>

                    {{-- LOGOUT --}}
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <button type="submit" class="btn-secondary-link">
                            <i class="bi bi-box-arrow-right"></i>
                            {{ __('labels.logout_account') }}
                        </button>

                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
