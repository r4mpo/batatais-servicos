@extends('layouts.guest')

@section('title', $professional->user->name.' | '.config('app.name'))

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/professionals.css') }}">
@endpush

@section('content')
    @php
        $media = $professional->reviews_avg_rating !== null ? round((float) $professional->reviews_avg_rating) : 0;
        $preco = number_format($professional->hourly_rate_cents / 100, 2, ',', '.');
        $iniciais = collect(preg_split('/\s+/', trim($professional->user->name)) ?: [])
            ->filter()
            ->take(2)
            ->map(fn ($parte) => mb_strtoupper(mb_substr($parte, 0, 1)))
            ->implode('');
        $agenda = $professional->availabilities->groupBy('day_of_week');
    @endphp

    <div class="container py-5">
        <p class="mb-3">
            <a href="{{ route('professionals.index') }}" class="text-decoration-none small text-muted">
                <i class="fas fa-arrow-left me-1" aria-hidden="true"></i>{{ __('labels.professional_profile_back') }}
            </a>
        </p>

        <header class="profile-hero p-4 mb-4">
            <div class="d-flex flex-column flex-md-row gap-3 align-items-md-center justify-content-between">
                <div class="d-flex gap-3 align-items-center">
                    @if ($professional->user->profilePhotoUrl())
                        <img src="{{ $professional->user->profilePhotoUrl() }}" alt="" class="profile-avatar">
                    @else
                        <div class="profile-avatar" aria-hidden="true">{{ $iniciais !== '' ? $iniciais : '?' }}</div>
                    @endif
                    <div>
                        <h1 class="h3 mb-1 d-flex align-items-center flex-wrap gap-2">
                            {{ $professional->user->name }}
                            @if (! empty($professional->solicitacoes_verificacao_aprovadas_exists))
                                <i class="fas fa-check-circle" title="{{ __('labels.verificacao_selo_aria') }}" aria-label="{{ __('labels.verificacao_selo_aria') }}"></i>
                            @endif
                        </h1>
                        <div class="opacity-75">{{ $professional->profession->title }}</div>
                        <div class="mt-2">
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="{{ $i <= $media ? 'fas' : 'far' }} fa-star text-warning" aria-hidden="true"></i>
                            @endfor
                            <span class="small ms-1">({{ __('labels.professionals_reviews', ['count' => $professional->reviews_count]) }})</span>
                        </div>
                    </div>
                </div>
                <div class="text-md-end">
                    <div class="fs-3 fw-bold">R$ {{ $preco }}<span class="fs-6 fw-normal">/{{ __('labels.professionals_per_hour') }}</span></div>
                </div>
            </div>
        </header>

        <div class="row g-4">
            <div class="col-lg-8">
                <section class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h2 class="h5">{{ __('labels.professional_profile_bio') }}</h2>
                        @if ($professional->title)
                            <p class="fw-semibold mb-2">{{ $professional->title }}</p>
                        @endif
                        <p class="mb-0 text-body-secondary">{{ $professional->description ?: '—' }}</p>
                    </div>
                </section>

                <section class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h2 class="h5">{{ __('labels.professional_profile_gallery') }}</h2>
                        @if ($professional->profileFiles->isEmpty())
                            <p class="text-muted mb-0">{{ __('labels.professional_profile_gallery_empty') }}</p>
                        @else
                            <div class="row g-3 profile-gallery">
                                @foreach ($professional->profileFiles as $foto)
                                    <div class="col-6 col-md-4">
                                        <img src="{{ asset('storage/'.$foto->path) }}" alt="{{ $foto->original_name }}" loading="lazy">
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </section>

                <section class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h2 class="h5">{{ __('labels.professional_profile_reviews') }}</h2>
                        @if ($professional->reviews->isEmpty())
                            <p class="text-muted mb-0">{{ __('labels.professional_profile_reviews_empty') }}</p>
                        @else
                            <div class="d-flex flex-column gap-3">
                                @foreach ($professional->reviews as $review)
                                    <article>
                                        <div class="fw-semibold">{{ $review->user->name }}</div>
                                        <div class="small text-warning mb-1" aria-label="{{ __('labels.professionals_rating_stars_label', ['n' => $review->rating]) }}">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <i class="{{ $i <= $review->rating ? 'fas' : 'far' }} fa-star" aria-hidden="true"></i>
                                            @endfor
                                        </div>
                                        @if ($review->comment)
                                            <p class="mb-0 text-body-secondary">{{ $review->comment }}</p>
                                        @endif
                                    </article>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </section>
            </div>
            <div class="col-lg-4">
                <section class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h2 class="h5">{{ __('labels.professional_profile_availability') }}</h2>
                        @if ($agenda->isEmpty())
                            <p class="text-muted mb-0">{{ __('labels.professional_profile_availability_empty') }}</p>
                        @else
                            <ul class="list-unstyled mb-0">
                                @foreach ($agenda as $dia => $faixas)
                                    <li class="mb-3">
                                        <div class="fw-semibold">{{ __('labels.weekday_'.$dia) }}</div>
                                        @foreach ($faixas as $faixa)
                                            <div class="small text-body-secondary">
                                                @if ($faixa->is_full_day)
                                                    {{ __('labels.professional_profile_full_day') }}
                                                @else
                                                    {{ \Illuminate\Support\Str::of((string) $faixa->starts_at)->substr(0, 5) }}
                                                    –
                                                    {{ \Illuminate\Support\Str::of((string) $faixa->ends_at)->substr(0, 5) }}
                                                @endif
                                            </div>
                                        @endforeach
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </section>
            </div>
        </div>
    </div>
@endsection
