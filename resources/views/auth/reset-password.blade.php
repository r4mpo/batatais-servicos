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
                <img src="{{ asset('img/logo.png') }}" alt="Logo">

                <h2>{{ __('labels.reset_password_title') }}</h2>

                <p>{{ __('labels.reset_password_description') }}</p>

                <div class="auth-features">

                    <div class="feature-item">
                        <i class="fas fa-lock"></i>
                        <span>{{ __('labels.feature_password_protected') }}</span>
                    </div>

                    <div class="feature-item">
                        <i class="fas fa-shield-alt"></i>
                        <span>{{ __('labels.feature_secure_process') }}</span>
                    </div>

                    <div class="feature-item">
                        <i class="fas fa-user-check"></i>
                        <span>{{ __('labels.feature_access_restored') }}</span>
                    </div>

                </div>

            </div>
        </div>

        <div class="auth-right">

            <div class="auth-form-wrapper">

                <div class="auth-form-header">
                    <h3>{{ __('labels.new_password_title') }}</h3>
                    <p>{{ __('labels.new_password_description') }}</p>
                </div>

                <form method="POST" action="{{ route('password.store') }}" class="form-content">
                    @csrf

                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <div class="form-group-custom">

                        <label class="form-label-custom" for="email">
                            {{ __('labels.email_label') }}
                        </label>

                        <input id="email" type="email" name="email" class="form-control-custom"
                            value="{{ old('email', $request->email) }}" required autofocus autocomplete="username"
                            placeholder="{{ __('labels.email_placeholder') }}">

                        <x-input-error :messages="$errors->get('email')" />

                    </div>

                    <div class="form-group-custom">

                        <label class="form-label-custom" for="password">
                            {{ __('labels.new_password_label') }}
                        </label>

                        <div class="input-group-custom">

                            <input id="password" type="password" name="password" class="form-control-custom" required
                                autocomplete="new-password" placeholder="{{ __('labels.new_password_placeholder') }}">

                            <i class="fas fa-eye password-toggle" id="toggle_password_visibility"></i>

                        </div>

                        <x-input-error :messages="$errors->get('password')" />

                    </div>

                    <div class="form-group-custom">

                        <label class="form-label-custom" for="password_confirmation">
                            {{ __('labels.confirm_password_label') }}
                        </label>

                        <div class="input-group-custom">

                            <input id="password_confirmation" type="password" name="password_confirmation"
                                class="form-control-custom" required autocomplete="new-password"
                                placeholder="{{ __('labels.confirm_password_placeholder') }}">

                            <i class="fas fa-eye password-toggle" id="toggle_password_confirmation_visibility"></i>

                        </div>

                        <x-input-error :messages="$errors->get('password_confirmation')" />

                    </div>

                    <button type="submit" class="btn-primary">
                        <i class="fas fa-key me-1"></i>
                        {{ __('labels.reset_password_button') }}
                    </button>

                </form>

            </div>
        </div>

    </div>
</x-guest-layout>
