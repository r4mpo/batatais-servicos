<x-app-layout>
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/professional-setup.css') }}">
    @endpush

    <div class="row justify-content-center">
        <div class="col-xl-9 col-lg-10">
            <div class="card border-0 shadow-sm professional-setup-card">
                <div class="card-body p-4 p-md-5">
                    <div class="p-4 rounded mb-4 bg-primary bg-opacity-10 border border-primary-subtle">
                        <h5 class="mb-1 fw-bold text-primary">
                            {{ __('labels.professional_onboarding_title') }}
                        </h5>
                        <p class="mb-0 text-muted">
                            {{ __('labels.professional_onboarding_subtitle') }}
                        </p>
                    </div>

                    <form method="post" action="{{ route('professional.setup.store') }}" novalidate autocomplete="off">
                        @csrf

                        {{-- Categoria --}}
                        <div class="mb-4 pb-4 border-bottom border-light-subtle">
                            <label class="form-label fw-semibold" for="onboarding_profession_id">
                                {{ __('labels.professional_onboarding_profession') }}
                            </label>
                            <select id="onboarding_profession_id" name="profession_id" required
                                class="form-select form-select-lg @error('profession_id') is-invalid @enderror"
                                aria-required="true"
                                aria-describedby="onboarding_profession_hint{{ $errors->has('profession_id') ? ' onboarding_profession_error' : '' }}"
                                aria-invalid="{{ $errors->has('profession_id') ? 'true' : 'false' }}">
                                <option value="" disabled @selected(old('profession_id') === null || old('profession_id') === ''))>
                                    {{ __('labels.professional_onboarding_profession_placeholder') }}
                                </option>
                                @foreach ($professions as $profession)
                                    <option value="{{ $profession->id }}" @selected((string) old('profession_id') === (string) $profession->id)>
                                        {{ $profession->title }}
                                    </option>
                                @endforeach
                            </select>
                            @error('profession_id')
                                <div id="onboarding_profession_error" class="invalid-feedback d-block" role="alert">
                                    {{ $message }}
                                </div>
                            @enderror
                            <p id="onboarding_profession_hint" class="form-text mb-0">
                                {{ __('labels.professional_onboarding_profession_hint') }}
                            </p>
                        </div>

                        {{-- Documentos --}}
                        <h2 class="h6 text-uppercase text-secondary fw-bold letter-spacing-sm mb-3 text-center">
                            {{ __('labels.professional_onboarding_documents_section') }}
                        </h2>
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold" for="onboarding_rg">
                                    {{ __('labels.professional_onboarding_rg') }}
                                </label>
                                <input id="onboarding_rg" type="text" name="rg" value="{{ old('rg') }}"
                                    maxlength="32" required
                                    placeholder="{{ __('labels.professional_onboarding_rg_placeholder') }}"
                                    class="form-control @error('rg') is-invalid @enderror" aria-required="true"
                                    aria-describedby="onboarding_rg_hint{{ $errors->has('rg') ? ' onboarding_rg_error' : '' }}"
                                    aria-invalid="{{ $errors->has('rg') ? 'true' : 'false' }}">
                                @error('rg')
                                    <div id="onboarding_rg_error" class="invalid-feedback d-block" role="alert">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <p id="onboarding_rg_hint" class="form-text mb-0">
                                    {{ __('labels.professional_onboarding_rg_hint') }}
                                </p>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold" for="onboarding_cpf">
                                    {{ __('labels.professional_onboarding_cpf') }}
                                </label>
                                <input id="onboarding_cpf" type="text" name="cpf" value="{{ old('cpf') }}"
                                    inputmode="numeric" required
                                    placeholder="{{ __('labels.professional_onboarding_cpf_placeholder') }}"
                                    class="form-control @error('cpf') is-invalid @enderror" aria-required="true"
                                    aria-describedby="onboarding_cpf_hint{{ $errors->has('cpf') ? ' onboarding_cpf_error' : '' }}"
                                    aria-invalid="{{ $errors->has('cpf') ? 'true' : 'false' }}">
                                @error('cpf')
                                    <div id="onboarding_cpf_error" class="invalid-feedback d-block" role="alert">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <p id="onboarding_cpf_hint" class="form-text mb-0">
                                    {{ __('labels.professional_onboarding_cpf_hint') }}
                                </p>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold" for="onboarding_cnpj">
                                    {{ __('labels.professional_onboarding_cnpj') }}
                                </label>
                                <input id="onboarding_cnpj" type="text" name="cnpj" value="{{ old('cnpj') }}"
                                    inputmode="numeric"
                                    placeholder="{{ __('labels.professional_onboarding_cnpj_placeholder') }}"
                                    class="form-control @error('cnpj') is-invalid @enderror"
                                    aria-describedby="onboarding_cnpj_hint{{ $errors->has('cnpj') ? ' onboarding_cnpj_error' : '' }}"
                                    aria-invalid="{{ $errors->has('cnpj') ? 'true' : 'false' }}">
                                @error('cnpj')
                                    <div id="onboarding_cnpj_error" class="invalid-feedback d-block" role="alert">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <p id="onboarding_cnpj_hint" class="form-text mb-0">
                                    {{ __('labels.professional_onboarding_cnpj_hint') }}
                                </p>
                            </div>
                        </div>

                        {{-- Anúncio e preço --}}
                        <h2 class="h6 text-uppercase text-secondary fw-bold letter-spacing-sm mb-3 text-center mt-5">
                            {{ __('labels.professional_onboarding_service_section') }}
                        </h2>
                        <div class="row g-3 mb-4">
                            <div class="col-md-8">
                                <label class="form-label fw-semibold" for="onboarding_title">
                                    {{ __('labels.professional_onboarding_title_field') }}
                                </label>
                                <input id="onboarding_title" type="text" name="title" value="{{ old('title') }}"
                                    maxlength="255" required
                                    placeholder="{{ __('labels.professional_onboarding_title_placeholder') }}"
                                    class="form-control @error('title') is-invalid @enderror" aria-required="true"
                                    aria-describedby="onboarding_title_hint{{ $errors->has('title') ? ' onboarding_title_error' : '' }}"
                                    aria-invalid="{{ $errors->has('title') ? 'true' : 'false' }}">
                                @error('title')
                                    <div id="onboarding_title_error" class="invalid-feedback d-block" role="alert">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <p id="onboarding_title_hint" class="form-text mb-0">
                                    {{ __('labels.professional_onboarding_title_hint') }}
                                </p>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold" for="onboarding_hourly_rate">
                                    {{ __('labels.professional_onboarding_hourly_rate') }}
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text" aria-hidden="true">R$</span>
                                    <input id="onboarding_hourly_rate" type="text" name="hourly_rate_reais"
                                        value="{{ old('hourly_rate_reais') }}" inputmode="decimal" required
                                        placeholder="{{ __('labels.professional_onboarding_hourly_placeholder') }}"
                                        class="form-control @error('hourly_rate_reais') is-invalid @enderror"
                                        aria-required="true"
                                        aria-describedby="onboarding_hourly_hint{{ $errors->has('hourly_rate_reais') ? ' onboarding_hourly_error' : '' }}"
                                        aria-invalid="{{ $errors->has('hourly_rate_reais') ? 'true' : 'false' }}">
                                </div>
                                @error('hourly_rate_reais')
                                    <div id="onboarding_hourly_error" class="invalid-feedback d-block" role="alert">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <p id="onboarding_hourly_hint" class="form-text mb-0">
                                    {{ __('labels.professional_onboarding_hourly_hint') }}
                                </p>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold" for="onboarding_description">
                                    {{ __('labels.professional_onboarding_description') }}
                                </label>
                                <textarea id="onboarding_description" name="description" rows="4" maxlength="5000"
                                    placeholder="{{ __('labels.professional_onboarding_description_placeholder') }}"
                                    class="form-control @error('description') is-invalid @enderror"
                                    aria-describedby="onboarding_description_hint{{ $errors->has('description') ? ' onboarding_description_error' : '' }}"
                                    aria-invalid="{{ $errors->has('description') ? 'true' : 'false' }}">{{ old('description') }}</textarea>
                                @error('description')
                                    <div id="onboarding_description_error" class="invalid-feedback d-block"
                                        role="alert">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <p id="onboarding_description_hint" class="form-text mb-0">
                                    {{ __('labels.professional_onboarding_description_hint') }}
                                </p>
                            </div>
                        </div>

                        <div class="d-flex flex-column flex-sm-row justify-content-sm-end gap-2 pt-2">
                            <button type="submit" class="btn btn-primary btn-lg px-4">
                                <i class="fas fa-check me-2" aria-hidden="true"></i>
                                {{ __('labels.professional_onboarding_submit') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/imask@7.6.1/dist/imask.min.js"></script>
        <script>
            (function() {
                'use strict';

                function initMasks() {
                    if (typeof IMask === 'undefined') {
                        return;
                    }

                    var cpf = document.getElementById('onboarding_cpf');
                    if (cpf) {
                        IMask(cpf, {
                            mask: '000.000.000-00'
                        });
                    }

                    var cnpj = document.getElementById('onboarding_cnpj');
                    if (cnpj) {
                        IMask(cnpj, {
                            mask: '00.000.000/0000-00'
                        });
                    }
                }

                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', initMasks);
                } else {
                    initMasks();
                }
            })();
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // input de hora
                const input = document.getElementById('onboarding_hourly_rate');
                // se não houver input, retorna
                if (!input) return;
                // valor inicial
                input.value = '0,00';

                // adiciona listener de input
                input.addEventListener('input', function(e) {
                    // só números
                    let value = e.target.value.replace(/\D/g, '');
                    // se não houver valor, define como 0
                    if (!value) value = '0';
                    // garante pelo menos 3 dígitos (pra centavos)
                    value = value.padStart(3, '0');
                    // separa reais e centavos
                    let reais = value.slice(0, -2);
                    let centavos = value.slice(-2);
                    // remove zeros à esquerda dos reais
                    reais = reais.replace(/^0+/, '') || '0';
                    // adiciona separador de milhar
                    reais = reais.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                    // adiciona separador de centavos
                    e.target.value = `${reais},${centavos}`;
                });
            });
        </script>
    @endpush
</x-app-layout>
