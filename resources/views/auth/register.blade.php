<x-guest-layout>
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    @endpush

    <div class="auth-container">
        <!-- Lado esquerdo -->
        <div class="auth-left">
            <a href="{{ url('/') }}" class="back-link">
                <i class="fas fa-arrow-left"></i>
                <span>{{ __('labels.back') }}</span>
            </a>

            <div class="auth-left-content">
                <img src="{{ asset('img/logo.png') }}" alt="{{ __('labels.brand') }}">
                <h2>{{ __('labels.join_title') }}</h2>
                <p>{{ __('labels.join_subtitle') }}</p>

                <div class="auth-features">
                    <div class="feature-item">
                        <i class="fas fa-rocket"></i>
                        <span>{{ __('labels.feature_start_fast') }}</span>
                    </div>

                    <div class="feature-item">
                        <i class="fas fa-shield-alt"></i>
                        <span>{{ __('labels.feature_secure_data') }}</span>
                    </div>

                    <div class="feature-item">
                        <i class="fas fa-users"></i>
                        <span>{{ __('labels.feature_community') }}</span>
                    </div>

                    <div class="feature-item">
                        <i class="fas fa-check-circle"></i>
                        <span>{{ __('labels.feature_simple_verification') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lado direito / Formulário -->
        <div class="auth-right">
            <div class="auth-form-wrapper">
                <div class="auth-form-header">
                    <h3>{{ __('labels.create_account_title') }}</h3>
                    <p>{{ __('labels.create_account_description') }}</p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="form-content">
                    @csrf

                    <!-- Seleção de perfil -->
                    <div class="profile-selector">
                        <label class="profile-selector-label">
                            {{ __('labels.profile_question') }}
                        </label>

                        <div class="radio-options">
                            <div class="radio-option">
                                <input type="radio" id="contractor" name="profile" value="000"
                                    {{ request()->query('profile') == 'contractor' ? 'checked' : '' }}>

                                <label for="contractor">
                                    <i class="fas fa-user"></i>
                                    <span>{{ __('labels.profile_contractor') }}</span>
                                </label>
                            </div>

                            <div class="radio-option">
                                <input type="radio" id="professional" name="profile" value="001"
                                    {{ request()->query('profile') == 'professional' ? 'checked' : '' }}>

                                <label for="professional">
                                    <i class="fas fa-briefcase"></i>
                                    <span>{{ __('labels.profile_professional') }}</span>
                                </label>
                            </div>
                        </div>

                        @error('profile')
                            <div class="text-danger small mt-1">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Nome completo -->
                    <div class="form-group-custom">
                        <label class="form-label-custom">{{ __('labels.full_name') }}</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control-custom"
                            placeholder="{{ __('labels.full_name_placeholder') }}" required>
                        @error('name')
                            <div class="text-danger small mt-1">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- E-mail -->
                    <div class="form-group-custom">
                        <label class="form-label-custom">{{ __('labels.email_label') }}</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control-custom"
                            placeholder="{{ __('labels.email_placeholder') }}" required>
                        @error('email')
                            <div class="text-danger small mt-1">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Senha -->
                    <div class="form-group-custom">
                        <label class="form-label-custom">{{ __('labels.password') }}</label>
                        <div class="input-group-custom">
                            <input type="password" name="password" id="password" class="form-control-custom"
                                placeholder="{{ __('labels.password_placeholder') }}" required>
                            <i class="fas fa-eye password-toggle" id="toggle_password_visibility"></i>
                        </div>
                        @error('password')
                            <div class="text-danger small mt-1">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Confirmar senha -->
                    <div class="form-group-custom">
                        <label class="form-label-custom">{{ __('labels.password_confirmation') }}</label>
                        <div class="input-group-custom">
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="form-control-custom" placeholder="{{ __('labels.password_confirm_placeholder') }}" required>
                            <i class="fas fa-eye password-toggle" id="toggle_password_confirmation_visibility"></i>
                        </div>
                    </div>

                    <!-- Termos e política -->
                    <div class="form-check-custom">
                        <input id="terms_privacy_checkbox" type="checkbox" required>
                        <label for="terms_privacy_checkbox">
                            {{ __('labels.accept_terms') }}
                            <a href="#" data-bs-toggle="modal" data-bs-target="#terms_modal">{{ __('labels.terms_of_use') }}</a>
                            e
                            <a href="#" data-bs-toggle="modal" data-bs-target="#privacy_modal">{{ __('labels.privacy_policy') }}</a>
                        </label>
                    </div>

                    <!-- Botão criar conta -->
                    <button disabled type="submit" id="btn-send" class="btn-primary">
                        <i class="fas fa-user-plus me-1"></i> {{ __('labels.create_account_button') }}
                    </button>

                    <!-- Já tem conta -->
                    <div class="divider-custom">
                        <span>{{ __('labels.already_have_account') }}</span>
                    </div>
                    <a href="{{ route('login') }}" class="btn-secondary-link"><i class="fas fa-sign-in-alt"></i> {{ __('labels.login') }}</a>

                    <div class="login-link">
                        <p style="margin:0;color:#666;font-size:0.8rem;">
                            {{ __('labels.back_to_home') }}
                            <a href="{{ url('/') }}">{{ __('labels.home_page') }}</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modais -->
    <div class="modal fade" id="terms_modal" tabindex="-1" aria-labelledby="terms_modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="terms_modalLabel">{{ __('labels.terms_modal_title') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    {!! nl2br(__('labels.terms_modal_body')) !!}
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" data-bs-dismiss="modal">{{ __('labels.understood') }}</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="privacy_modal" tabindex="-1" aria-labelledby="privacy_modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="privacy_modalLabel">{{ __('labels.privacy_modal_title') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    {!! nl2br(__('labels.privacy_modal_body')) !!}
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" data-bs-dismiss="modal">{{ __('labels.understood') }}</button>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
