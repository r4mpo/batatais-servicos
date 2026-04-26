<section class="profile-section">
    <header>
        <h2 class="h5 fw-bold mb-2">
            {{ __('Profile Information') }}
        </h2>

        <p class="text-muted mb-0">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-4 profile-form">
        @csrf
        @method('patch')

        <div class="mb-3">
            <x-input-label for="name" :value="__('Name')" class="form-label mb-1" />
            <x-text-input id="name" name="name" type="text" class="form-control profile-input" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2 small" :messages="$errors->get('name')" />
        </div>

        <div class="mb-3">
            <x-input-label for="email" :value="__('Email')" class="form-label mb-1" />
            <x-text-input id="email" name="email" type="email" class="form-control profile-input" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2 small" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2">
                    <p class="small text-body-secondary mb-2">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" type="submit" class="btn btn-link p-0 align-baseline text-decoration-underline small">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="small text-success fw-semibold mb-0">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="d-flex align-items-center gap-3 pt-2">
            <button type="submit" class="btn btn-primary px-4">
                {{ __('Save') }}
            </button>

            @if (session('status') === 'profile-updated')
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
