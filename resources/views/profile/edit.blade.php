<x-app-layout>
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
    @endpush

    <div class="py-4 profile-page">
        <div class="container-fluid px-0 px-sm-3" style="max-width: 1200px; margin: 0 auto;">
            <div class="row g-4 align-items-start">
                <div class="col-12 col-lg-7">
                    <div class="card border-0 shadow-sm profile-section-card mb-4">
                        <div class="card-body p-4 p-lg-5">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>

                    <div id="dashboard-anchor-password" class="card border-0 shadow-sm profile-section-card" tabindex="-1">
                        <div class="card-body p-4 p-lg-5">
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-5">
                    <div class="card border-0 shadow-sm profile-section-card profile-delete-card">
                        <div class="card-body p-4 p-lg-5">
                            @include('profile.partials.delete-user-form')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
