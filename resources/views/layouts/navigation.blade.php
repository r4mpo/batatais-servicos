<nav class="navbar navbar-expand-lg navbar-dark sticky-top">

    <div class="container">

        <a class="navbar-brand" href="{{ url('/') }}">
            <img src="{{ asset('img/logo.png') }}" height="35">
            <span>{{ __('labels.brand') }}</span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">

            <ul class="navbar-nav ms-auto">

                <li class="nav-item">
                    <a class="nav-link" href="#servicos">
                        {{ __('labels.services') }}
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#sobre">
                        {{ __('labels.about') }}
                    </a>
                </li>

                @guest

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">
                            <i class="fas fa-user me-1"></i>
                            {{ __('labels.contractor') }}
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">
                            <i class="fas fa-briefcase me-1"></i>
                            {{ __('labels.professional') }}
                        </a>
                    </li>

                @endguest


                @auth

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard') }}">
                            <i class="fas fa-solid fa-unlock"></i>
                            {{ __('labels.user_area') }}
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-solid fa-arrow-left"></i>
                            {{ __('labels.logout') }}
                        </a>
                    </li>

                    <form id="logout-form"
                          action="{{ route('logout') }}"
                          method="POST"
                          style="display:none;">
                        @csrf
                    </form>

                @endauth

            </ul>

        </div>

    </div>

</nav>
