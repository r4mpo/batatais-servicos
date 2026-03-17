<x-guest-layout>
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    @endpush

    <div class="auth-container">
        <div class="auth-left">
            <a href="{{ url('/') }}" class="back-link">
                <i class="fas fa-arrow-left"></i>
                <span>{{ __('labels.back') }}</span>
            </a>

            <div class="auth-left-content">
                <img src="{{ asset('img/logo.png') }}" alt="Batatais Serviços">

                <h2>{{ __('labels.welcome_back_title') }}</h2>

                <p>{{ __('labels.welcome_back_description') }}</p>

                <div class="auth-features">

                    <div class="feature-item">
                        <i class="fas fa-lock"></i>
                        <span>{{ __('labels.feature_secure_access') }}</span>
                    </div>

                    <div class="feature-item">
                        <i class="fas fa-bell"></i>
                        <span>{{ __('labels.feature_notifications') }}</span>
                    </div>

                    <div class="feature-item">
                        <i class="fas fa-history"></i>
                        <span>{{ __('labels.feature_activity_history') }}</span>
                    </div>

                    <div class="feature-item">
                        <i class="fas fa-headset"></i>
                        <span>{{ __('labels.feature_support') }}</span>
                    </div>

                </div>
            </div>
        </div>

        <div class="auth-right">
            <div class="auth-form-wrapper">

                <div class="auth-form-header">
                    <h3>{{ __('labels.login_title') }}</h3>
                    <p>{{ __('labels.login_description') }}</p>
                </div>

                <x-auth-session-status :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" id="loginForm" class="form-content">
                    @csrf

                    <div class="form-group-custom">

                        <label class="form-label-custom" for="email">
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
                            autocomplete="username"
                            placeholder="{{ __('labels.email_placeholder') }}"
                        >

                        <x-input-error :messages="$errors->get('email')" class="mt-2" />

                    </div>

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

                    <div class="form-check-custom">
                        <input type="checkbox" id="remember_me" name="remember">

                        <label for="remember_me">
                            {{ __('labels.remember_me') }}
                        </label>
                    </div>

                    <button type="submit" class="btn-primary">
                        <i class="fas fa-sign-in-alt me-1"></i>
                        {{ __('labels.login_button') }}
                    </button>

                    <div class="divider-custom">
                        <span>{{ __('labels.no_account') }}</span>
                    </div>

                    <a href="{{ route('register') }}" id="registerContractor" class="btn-secondary-link">
                        <i class="fas fa-user-plus"></i>
                        {{ __('labels.register') }}
                    </a>

                    @if (Route::has('password.request'))
                        <div class="signup-link">
                            <p style="margin:0;color:#666;font-size:0.8rem;">
                                <a href="{{ route('password.request') }}">
                                    {{ __('labels.forgot_password') }}
                                </a>
                            </p>
                        </div>
                    @endif

                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
