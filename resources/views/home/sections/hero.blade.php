<section class="hero-section">
    <div class="container">

        <div class="row align-items-center">

            <div class="col-lg-6">

                <h1 class="fw-bold mb-4">

                    {{ __('labels.hero_title_part1') }}

                    <span class="text-gradient-secondary">
                        {{ __('labels.hero_title_highlight') }}
                    </span>

                    {{ __('labels.hero_title_part2') }}

                </h1>

                <p class="lead mb-4">
                    {{ __('labels.hero_description') }}
                </p>

                <div class="d-flex gap-3 flex-wrap">

                    <a href="{{ route('register') . '?profile=contractor' }}" class="btn btn-light btn-lg">
                        <i class="fas fa-arrow-right me-2"></i>
                        {{ __('labels.hero_contractor') }}
                    </a>

                    <a href="{{ route('register') . '?profile=professional' }}" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-briefcase me-2"></i>
                        {{ __('labels.hero_professional') }}
                    </a>

                </div>

            </div>

        </div>

    </div>
</section>
