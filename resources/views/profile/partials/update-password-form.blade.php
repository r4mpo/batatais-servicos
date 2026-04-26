<section class="profile-section">
    <header>
        <h2 class="h5 fw-bold mb-2">
            {{ __('Update Password') }}
        </h2>

        <p class="text-muted mb-0">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-4 profile-form">
        @csrf
        @method('put')

        <div class="mb-3">
            <x-input-label for="update_password_current_password" :value="__('Current Password')" class="form-label mb-1" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="form-control profile-input" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2 small" />
        </div>

        <div class="mb-3">
            <x-input-label for="update_password_password" :value="__('New Password')" class="form-label mb-1" />
            <x-text-input id="update_password_password" name="password" type="password" class="form-control profile-input" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2 small" />
        </div>

        <div class="mb-3">
            <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" class="form-label mb-1" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="form-control profile-input" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2 small" />
        </div>

        <div class="d-flex align-items-center gap-3 pt-2">
            <button type="submit" class="btn btn-primary px-4">
                {{ __('Save') }}
            </button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="small text-success mb-0"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
