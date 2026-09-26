@php
    use App\Support\BrazilianDocuments;
    $editando = $service !== null;
    $valor = old('service_value_reais', $editando ? BrazilianDocuments::formatHourlyReaisFromCents($service->service_value_cents) : '');
@endphp

<x-app-layout>
    <div class="py-4">
        <div class="container" style="max-width: 760px;">
            <p class="mb-3">
                <a href="{{ route('contractor.services.index') }}" class="text-decoration-none small text-muted">
                    <i class="fas fa-arrow-left me-1" aria-hidden="true"></i>{{ __('labels.contractor_services_back') }}
                </a>
            </p>
            <h1 class="h4 mb-3">{{ $editando ? __('labels.contractor_services_edit_title') : __('labels.contractor_services_create_title') }}</h1>

            <form method="post" action="{{ $editando ? route('contractor.services.update', $service) : route('contractor.services.store') }}" class="card shadow-sm border-0">
                @csrf
                @if ($editando)
                    @method('PUT')
                @endif
                <div class="card-body">
                    <div class="mb-3 position-relative">
                        <label class="form-label" for="professional-search">{{ __('labels.contractor_services_professional') }}</label>
                        <input type="hidden" name="professional_id" id="professional_id" value="{{ $selectedProfessional?->id }}">
                        <input type="search" id="professional-search" class="form-control @error('professional_id') is-invalid @enderror" autocomplete="off" placeholder="{{ __('labels.contractor_services_professional_search') }}" data-url="{{ route('contractor.professionals.search') }}">
                        <div id="professional-results" class="list-group position-absolute w-100 shadow-sm" style="z-index: 5;"></div>
                        <div id="professional-selected" class="form-text {{ $selectedProfessional ? '' : 'd-none' }}">
                            <span id="professional-selected-label">{{ $selectedProfessional ? $selectedProfessional->user->name.' — '.$selectedProfessional->profession->title : '' }}</span>
                            <button type="button" id="professional-clear" class="btn btn-link btn-sm p-0 ms-2">{{ __('labels.contractor_services_professional_later') }}</button>
                        </div>
                        <div class="form-text">{{ __('labels.contractor_services_professional_hint') }}</div>
                        @error('professional_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="title">{{ __('labels.contractor_services_field_title') }}</label>
                        <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $service?->title ?? '') }}" required>
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="description">{{ __('labels.contractor_services_field_description') }}</label>
                        <textarea name="description" id="description" rows="4" class="form-control @error('description') is-invalid @enderror">{{ old('description', $service?->description ?? '') }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="service_value_reais">{{ __('labels.contractor_services_field_value') }}</label>
                        <input type="text" inputmode="decimal" name="service_value_reais" id="service_value_reais" class="form-control @error('service_value_reais') is-invalid @enderror" value="{{ $valor }}" required>
                        @error('service_value_reais')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-sm-6">
                            <label class="form-label" for="scheduled_start_date">{{ __('labels.contractor_services_field_start_date') }}</label>
                            <input type="date" name="scheduled_start_date" id="scheduled_start_date" class="form-control @error('scheduled_start_date') is-invalid @enderror" value="{{ old('scheduled_start_date', isset($service->scheduled_start_date) ? $service->scheduled_start_date->format('Y-m-d') : '') }}" required>
                            @error('scheduled_start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="scheduled_end_date">{{ __('labels.contractor_services_field_end_date') }}</label>
                            <input type="date" name="scheduled_end_date" id="scheduled_end_date" class="form-control @error('scheduled_end_date') is-invalid @enderror" value="{{ old('scheduled_end_date', isset($service->scheduled_end_date) ? $service->scheduled_end_date->format('Y-m-d') : '') }}" required>
                            @error('scheduled_end_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="scheduled_start_time">{{ __('labels.contractor_services_field_start_time') }}</label>
                            <input type="time" name="scheduled_start_time" id="scheduled_start_time" class="form-control" value="{{ old('scheduled_start_time', $service && $service->scheduled_start_time ? substr((string) $service->scheduled_start_time, 0, 5) : '') }}">
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="scheduled_end_time">{{ __('labels.contractor_services_field_end_time') }}</label>
                            <input type="time" name="scheduled_end_time" id="scheduled_end_time" class="form-control @error('scheduled_end_time') is-invalid @enderror" value="{{ old('scheduled_end_time', $service && $service->scheduled_end_time ? substr((string) $service->scheduled_end_time, 0, 5) : '') }}">
                            @error('scheduled_end_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-sm-4">
                            <label class="form-label" for="address_postal_code">{{ __('labels.contractor_services_field_postal_code') }}</label>
                            <input type="text" name="address_postal_code" id="address_postal_code" class="form-control" value="{{ old('address_postal_code', $service?->address_postal_code ?? '') }}" maxlength="9">
                        </div>
                        <div class="col-sm-8">
                            <label class="form-label" for="address_street">{{ __('labels.contractor_services_field_street') }}</label>
                            <input type="text" name="address_street" id="address_street" class="form-control" value="{{ old('address_street', $service?->address_street ?? '') }}">
                        </div>
                        <div class="col-sm-3">
                            <label class="form-label" for="address_number">{{ __('labels.contractor_services_field_number') }}</label>
                            <input type="text" name="address_number" id="address_number" class="form-control" value="{{ old('address_number', $service?->address_number ?? '') }}">
                        </div>
                        <div class="col-sm-5">
                            <label class="form-label" for="address_neighborhood">{{ __('labels.contractor_services_field_neighborhood') }}</label>
                            <input type="text" name="address_neighborhood" id="address_neighborhood" class="form-control" value="{{ old('address_neighborhood', $service?->address_neighborhood ?? '') }}">
                        </div>
                        <div class="col-sm-4">
                            <label class="form-label" for="address_complement">{{ __('labels.contractor_services_field_complement') }}</label>
                            <input type="text" name="address_complement" id="address_complement" class="form-control" value="{{ old('address_complement', $service?->address_complement ?? '') }}">
                        </div>
                        <div class="col-sm-8">
                            <label class="form-label" for="address_city">{{ __('labels.contractor_services_field_city') }}</label>
                            <input type="text" name="address_city" id="address_city" class="form-control" value="{{ old('address_city', $service?->address_city ?? '') }}">
                        </div>
                        <div class="col-sm-4">
                            <label class="form-label" for="address_state">{{ __('labels.contractor_services_field_state') }}</label>
                            <input type="text" name="address_state" id="address_state" class="form-control @error('address_state') is-invalid @enderror" value="{{ old('address_state', $service?->address_state ?? '') }}" maxlength="2">
                            @error('address_state')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent text-end">
                    <button type="submit" class="btn btn-primary">{{ __('labels.contractor_services_save') }}</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script src="{{ asset('js/contractor-service-form.js') }}"></script>
    @endpush
</x-app-layout>
