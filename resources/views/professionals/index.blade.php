@extends('layouts.guest')

@section('title', __('labels.professionals_page_title').' | '.config('app.name'))

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tom-select@2.4.1/dist/css/tom-select.bootstrap5.min.css">
    <link rel="stylesheet" href="{{ asset('css/professionals.css') }}">
@endpush

@section('content')
    <div class="container py-5">
        <div class="row">
            <div class="col-md-3">
                <form class="filter-sidebar" method="get" action="{{ route('professionals.index') }}" id="professionals-filters-form">
                    <h5 class="mb-4">
                        <i class="fas fa-filter me-2"></i>{{ __('labels.professionals_filters_heading') }}
                    </h5>

                    <div class="search-box">
                        <label class="form-label small text-muted mb-1" for="searchInput">{{ __('labels.professionals_search_label') }}</label>
                        <input type="text"
                               name="q"
                               value="{{ $q }}"
                               class="form-control"
                               placeholder="{{ __('labels.professionals_search_placeholder') }}"
                               id="searchInput">
                    </div>

                    <div class="sort-options">
                        <h6>{{ __('labels.professionals_sort_heading') }}</h6>
                        <select class="form-select form-select-sm" name="sort" id="sortSelect" aria-label="{{ __('labels.professionals_sort_heading') }}">
                            <option value="relevance" @selected($sort === 'relevance')>{{ __('labels.professionals_sort_relevance') }}</option>
                            <option value="rating" @selected($sort === 'rating')>{{ __('labels.professionals_sort_rating') }}</option>
                            <option value="price_asc" @selected($sort === 'price_asc')>{{ __('labels.professionals_sort_price_asc') }}</option>
                            <option value="price_desc" @selected($sort === 'price_desc')>{{ __('labels.professionals_sort_price_desc') }}</option>
                            <option value="recent" @selected($sort === 'recent')>{{ __('labels.professionals_sort_recent') }}</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <h6>{{ __('labels.professionals_category_heading') }}</h6>
                        <label class="form-label visually-hidden" for="profession-filter-select">{{ __('labels.professionals_category_heading') }}</label>
                        <select name="profession_id[]"
                                id="profession-filter-select"
                                multiple
                                class="form-select form-select-sm"
                                data-placeholder="{{ __('labels.professionals_category_select_placeholder') }}">
                            @foreach ($filterProfessions as $profession)
                                <option value="{{ $profession->id }}" @selected(in_array($profession->id, $selectedProfessionIds, true))>
                                    {{ $profession->title }}
                                </option>
                            @endforeach
                        </select>
                        <p class="form-text small text-muted mb-0 mt-1">{{ __('labels.professionals_category_select_hint') }}</p>
                    </div>

                    <div class="filter-group">
                        <h6>{{ __('labels.professionals_rating_heading') }}</h6>
                        <div class="filter-item">
                            <input type="checkbox" name="rating_5" value="1" id="rating-5" @checked($rating_5)>
                            <label for="rating-5">
                                <i class="fas fa-star" style="color: #ffc107;"></i>
                                <i class="fas fa-star" style="color: #ffc107;"></i>
                                <i class="fas fa-star" style="color: #ffc107;"></i>
                                <i class="fas fa-star" style="color: #ffc107;"></i>
                                <i class="fas fa-star" style="color: #ffc107;"></i>
                                {{ __('labels.professionals_rating_5') }}
                            </label>
                        </div>
                        <div class="filter-item">
                            <input type="checkbox" name="rating_4" value="1" id="rating-4" @checked($rating_4)>
                            <label for="rating-4">
                                <i class="fas fa-star" style="color: #ffc107;"></i>
                                <i class="fas fa-star" style="color: #ffc107;"></i>
                                <i class="fas fa-star" style="color: #ffc107;"></i>
                                <i class="fas fa-star" style="color: #ffc107;"></i>
                                <i class="far fa-star" style="color: #ffc107;"></i>
                                {{ __('labels.professionals_rating_4_plus') }}
                            </label>
                        </div>
                    </div>

                    <div class="filter-group">
                        <h6>{{ __('labels.professionals_price_heading') }}</h6>
                        <input type="range"
                               class="form-range"
                               name="max_price"
                               min="0"
                               max="500"
                               value="{{ $max_price_reais }}"
                               id="priceRange">
                        <small class="text-muted">{{ __('labels.professionals_price_up_to') }}
                            R$ <span id="priceValue">{{ $max_price_reais }}</span></small>
                    </div>

                    <div class="filter-group">
                        <h6>{{ __('labels.professionals_availability_heading') }}</h6>
                        <div class="filter-item">
                            <input type="checkbox" name="avail_today" value="1" id="avail-hoje" @checked($avail_today)>
                            <label for="avail-hoje">{{ __('labels.professionals_avail_today') }}</label>
                        </div>
                        <div class="filter-item">
                            <input type="checkbox" name="avail_week" value="1" id="avail-semana" @checked($avail_week)>
                            <label for="avail-semana">{{ __('labels.professionals_avail_week') }}</label>
                        </div>
                        <div class="filter-item">
                            <input type="checkbox" name="avail_24h" value="1" id="avail-24h" @checked($avail_24h)>
                            <label for="avail-24h">{{ __('labels.professionals_avail_24h') }}</label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-2"></i>{{ __('labels.professionals_apply_filters') }}
                    </button>
                    <a href="{{ route('professionals.index') }}" class="btn btn-outline-secondary w-100 mt-2">
                        <i class="fas fa-redo me-2"></i>{{ __('labels.professionals_clear_filters') }}
                    </a>
                </form>
            </div>

            <div class="col-md-9">
                <div class="professional-count">
                    {{ __('labels.professionals_showing') }} <strong>{{ $professionals->total() }}</strong> {{ __('labels.professionals_total_label') }}
                </div>

                @if ($professionals->isEmpty())
                    <div class="no-results">
                        <i class="fas fa-user-slash d-block"></i>
                        <p class="mb-0">{{ __('labels.professionals_empty') }}</p>
                    </div>
                @else
                    <div class="row g-4">
                        @foreach ($professionals as $professional)
                            @php
                                $partesNome = preg_split('/\s+/', trim($professional->user->name));
                                $iniciais = '';
                                foreach (array_slice($partesNome, 0, 2) as $trechoNome) {
                                    $iniciais .= mb_strtoupper(mb_substr($trechoNome, 0, 1));
                                }
                                $mediaEstrelas = $professional->reviews_avg_rating !== null
                                    ? round((float) $professional->reviews_avg_rating)
                                    : 0;
                                $precoFormatado = number_format($professional->hourly_rate_cents / 100, 2, ',', '.');
                            @endphp
                            <div class="col-md-6 col-lg-4">
                                <div class="card professional-card h-100">
                                    <div class="professional-image" aria-hidden="true">
                                        @if ($professional->user->profilePhotoUrl())
                                            <img src="{{ $professional->user->profilePhotoUrl() }}" alt="" loading="lazy">
                                        @else
                                            {{ $iniciais !== '' ? $iniciais : '?' }}
                                        @endif
                                    </div>
                                    <div class="professional-body">
                                        <div class="professional-name d-flex align-items-center flex-wrap gap-1">
                                            <span>{{ $professional->user->name }}</span>
                                            @if (! empty($professional->solicitacoes_verificacao_aprovadas_exists))
                                                <i class="fas fa-check-circle text-primary" title="{{ __('labels.verificacao_selo_aria') }}" aria-label="{{ __('labels.verificacao_selo_aria') }}"></i>
                                            @endif
                                        </div>
                                        <div class="professional-category">{{ $professional->profession->title }}</div>
                                        <div class="professional-rating">
                                            <div class="stars" aria-label="{{ __('labels.professionals_rating_stars_label', ['n' => $mediaEstrelas]) }}">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i <= $mediaEstrelas)
                                                        <i class="fas fa-star"></i>
                                                    @else
                                                        <i class="far fa-star"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                            <div class="reviews">
                                                ({{ __('labels.professionals_reviews', ['count' => $professional->reviews_count]) }})
                                            </div>
                                        </div>
                                        <div class="professional-price">
                                            R$ {{ $precoFormatado }}<span class="fs-6 fw-normal">/{{ __('labels.professionals_per_hour') }}</span>
                                        </div>
                                        <div class="professional-description">
                                            {{ \Illuminate\Support\Str::limit($professional->description, 160) }}
                                        </div>
                                        <div class="professional-actions">
                                            <button type="button" class="btn btn-primary btn-sm" disabled>
                                                <i class="fas fa-eye me-1"></i>{{ __('labels.professionals_view_profile') }}
                                            </button>
                                            <button type="button" class="btn btn-secondary btn-sm" disabled>
                                                <i class="fas fa-message me-1"></i>{{ __('labels.professionals_contact') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <nav aria-label="{{ __('labels.professionals_pagination_label') }}" class="mt-5">
                        {{ $professionals->links() }}
                    </nav>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.4.1/dist/js/tom-select.complete.min.js"></script>
    <script>
        /**
         * Comportamentos da barra lateral de filtros da listagem de profissionais (preço, ordenação e categorias).
         */
        (function () {
            var faixaPreco = document.getElementById('priceRange');
            var rotuloPreco = document.getElementById('priceValue');
            var selecaoOrdenacao = document.getElementById('sortSelect');
            var formularioFiltros = document.getElementById('professionals-filters-form');
            var selecaoCategorias = document.getElementById('profession-filter-select');

            if (faixaPreco && rotuloPreco) {
                /** Passo: ao mover a faixa, atualizar o número exibido ao lado. */
                faixaPreco.addEventListener('input', function () {
                    rotuloPreco.textContent = faixaPreco.value;
                });
            }
            if (selecaoOrdenacao && formularioFiltros) {
                /** Passo: mudar ordenação dispara novo GET com os mesmos filtros. */
                selecaoOrdenacao.addEventListener('change', function () {
                    formularioFiltros.submit();
                });
            }
            if (selecaoCategorias && typeof TomSelect !== 'undefined') {
                var textoPlaceholder = selecaoCategorias.getAttribute('data-placeholder') || '';
                var quantidadeSelecionadas = Array.prototype.filter.call(selecaoCategorias.options, function (opcao) {
                    return opcao.selected;
                }).length;
                /** Multi-select com busca; placeholder some quando há categorias selecionadas. */
                new TomSelect(selecaoCategorias, {
                    plugins: ['remove_button'],
                    placeholder: quantidadeSelecionadas > 0 ? '' : textoPlaceholder,
                    maxOptions: null,
                    hideSelected: true,
                    dropdownParent: 'body',
                    onChange: function (value) {
                        var empty = value == null || value === '' ||
                            (Array.isArray(value) && value.length === 0);
                        var ph = empty ? textoPlaceholder : '';
                        this.settings.placeholder = ph;
                        if (this.control_input) {
                            this.control_input.placeholder = ph;
                        }
                        this.inputState();
                    },
                });
            }
        })();
    </script>
@endpush
