<footer class="footer">

    <div class="container">

        <div class="row mb-5">

            <div class="col-md-4 mb-4 mb-md-0">

                <h5>{{ __('labels.title') }}</h5>

                <p>
                    {{ __('labels.description') }}
                </p>

                <div class="d-flex gap-3">
                    <a href="#" class="text-white-50"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="text-white-50"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-white-50"><i class="fab fa-whatsapp"></i></a>
                </div>

            </div>

            <div class="col-md-4 mb-4 mb-md-0">

                <h5>{{ __('labels.quick_links') }}</h5>

                <ul class="list-unstyled">
                    <li><a href="#" class="text-white-50">{{ __('labels.about') }}</a></li>
                    <li><a href="#" class="text-white-50">{{ __('labels.terms') }}</a></li>
                    <li><a href="#" class="text-white-50">{{ __('labels.privacy') }}</a></li>
                    <li><a href="#" class="text-white-50">{{ __('labels.contact') }}</a></li>
                </ul>

            </div>

            <div class="col-md-4">

                <h5>{{ __('labels.contact_title') }}</h5>

                <p class="text-white-50 mb-2">
                    <i class="fas fa-envelope me-2"></i>
                    contato@batataisservicos.com
                </p>

                <p class="text-white-50 mb-2">
                    <i class="fas fa-phone me-2"></i>
                    (16) 3761-0000
                </p>

                <p class="text-white-50">
                    <i class="fas fa-map-marker-alt me-2"></i>
                    Batatais - SP
                </p>

            </div>

        </div>

        <hr class="bg-white-50">

        <div class="text-center text-white-50">
            <p>
                &copy; {{ date('Y') }}
                {{ __('labels.title') }}.
                {{ __('labels.copyright') }}
            </p>
        </div>

    </div>

</footer>
