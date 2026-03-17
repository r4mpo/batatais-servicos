<section class="py-5" style="background: var(--gradient-primary); color: white; position: relative; overflow: hidden;">
    <div
        style="position: absolute; top: -50%; right: -10%; width: 400px; height: 400px; background: rgba(230, 0, 0, 0.1); border-radius: 50%;">
    </div>

    <div
        style="position: absolute; bottom: -30%; left: -5%; width: 300px; height: 300px; background: rgba(255, 255, 255, 0.05); border-radius: 50%;">
    </div>

    <div class="container text-center position-relative" style="z-index: 1;">

        <h2 class="fw-bold mb-4">
            {{ __('labels.cta_title') }}
        </h2>

        <p class="lead mb-5">
            {{ __('labels.cta_description') }}
        </p>

        <div class="row g-3 justify-content-center">

            <div class="col-md-4">
                <a href="{{ route('register') . '?profile=contractor' }}" class="btn btn-light btn-lg">
                    <i class="fas fa-user-plus me-2"></i>
                    {{ __('labels.cta_contractor') }}
                </a>
            </div>

            <div class="col-md-4">
                <a href="{{ route('register') . '?profile=professional' }}" class="btn btn-outline-light btn-lg">
                    <i class="fas fa-briefcase me-2"></i>
                    {{ __('labels.cta_professional') }}
                </a>
            </div>

        </div>

    </div>

</section>
