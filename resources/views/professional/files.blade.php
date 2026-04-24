@php
    /** @var \App\Models\Professional $professional */
    $verificationFiles = $professional->profileFiles->where('kind', \App\Models\ProfessionalFile::KIND_VERIFICATION_DOCUMENT);
    $otherVerificationFiles = $verificationFiles->where('file_type', \App\Models\ProfessionalFile::FILE_TYPE_CODE_OTHER);
    $publicFiles = $professional->profileFiles->filter(
        fn ($f) => $f->isPublicPhoto() && $f->isShowcasePhoto()
    );
    $parts = preg_split('/\s+/', trim($professional->user->name));
    $initials = '';
    foreach (array_slice($parts, 0, 2) as $p) {
        $initials .= mb_strtoupper(mb_substr($p, 0, 1));
    }
    $statusMessages = [
        'professional-profile-photo-updated' => __('labels.professional_files_status_profile_updated'),
        'professional-profile-photo-removed' => __('labels.professional_files_status_profile_removed'),
        'professional-verification-documents-updated' => __('labels.professional_files_status_verification_updated'),
        'professional-public-photos-updated' => __('labels.professional_files_status_public_updated'),
        'professional-file-removed' => __('labels.professional_files_status_file_removed'),
    ];
@endphp

<x-app-layout>
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/professional-files.css') }}">
    @endpush

    <div class="professional-files-page container-fluid px-0 px-sm-3" style="max-width: 920px; margin: 0 auto;">
        <div class="mb-3">
            <a href="{{ route('dashboard') }}" class="text-decoration-none small">
                <i class="fas fa-arrow-left me-1" aria-hidden="true"></i>{{ __('labels.professional_files_back_dashboard') }}
            </a>
        </div>

        <div class="mb-4">
            <h1 class="h4 fw-bold mb-2">{{ __('labels.professional_files_page_title') }}</h1>
            <p class="text-muted small mb-0">{{ __('labels.professional_files_intro') }}</p>
        </div>

        @if (session('status') && isset($statusMessages[session('status')]))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ $statusMessages[session('status')] }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ __('labels.dashboard_alert_dismiss') }}"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Foto de perfil --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <h2 class="h6 fw-bold mb-3">
                    <i class="fas fa-camera me-2 text-primary" aria-hidden="true"></i>{{ __('labels.professional_files_profile_section') }}
                </h2>
                <p class="text-muted small">{{ __('labels.professional_files_profile_help') }}</p>

                <div class="row align-items-start g-4">
                    <div class="col-auto">
                        <div class="professional-files-preview">
                            @if ($professional->user->profilePhotoUrl())
                                <img src="{{ $professional->user->profilePhotoUrl() }}" alt="" width="160" height="160">
                            @else
                                <span class="professional-files-preview-placeholder" aria-hidden="true">{{ $initials !== '' ? $initials : '?' }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col">
                        <form method="post" action="{{ route('professional.files.profile-photo') }}" enctype="multipart/form-data" class="mb-3">
                            @csrf
                            <div class="input-group input-group-sm flex-nowrap mb-2" style="max-width: 100%;">
                                <input type="file" name="photo" class="form-control" accept="image/jpeg,image/png,image/webp,image/gif" required>
                                <button type="submit" class="btn btn-primary">{{ __('labels.professional_files_btn_upload') }}</button>
                            </div>
                            <x-input-error :messages="$errors->get('photo')" class="small" />
                        </form>
                        @if ($professional->user->profile_photo)
                            <form method="post" action="{{ route('professional.files.profile-photo.destroy') }}" data-confirm="{{ __('labels.professional_files_confirm_remove_profile') }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                    <i class="fas fa-trash-alt me-1" aria-hidden="true"></i>{{ __('labels.professional_files_btn_remove_profile') }}
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Documentos de verificação (por tipo) --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <h2 class="h6 fw-bold mb-2">
                    <i class="fas fa-shield-alt me-2 text-success" aria-hidden="true"></i>{{ __('labels.professional_files_verification_section') }}
                </h2>
                <p class="text-muted small mb-4">
                    {{ __('labels.professional_files_verification_help', [
                        'max_total' => \App\Services\Professional\ProfessionalProfileFilesService::MAX_VERIFICATION_FILES,
                        'max_per' => \App\Services\Professional\ProfessionalProfileFilesService::MAX_VERIFICATION_FILES_PER_TYPE,
                    ]) }}
                </p>

                @foreach (\App\Models\ProfessionalFile::verificationDocumentTypes() as $docType)
                    @php
                        $filesForType = $verificationFiles->where('file_type', $docType);
                    @endphp
                    <div class="professional-files-doc-section border rounded-3 p-3 mb-3 bg-body-secondary bg-opacity-25" id="doc-{{ $docType }}">
                        <h3 class="h6 fw-semibold mb-2">
                            {{ __('labels.professional_files_doc_'.\App\Models\ProfessionalFile::fileTypeToTranslationKey($docType)) }}
                        </h3>
                        <p class="text-muted small mb-3">{{ __('labels.professional_files_doc_section_hint') }}</p>

                        @if ($filesForType->isEmpty())
                            <p class="small text-muted fst-italic mb-3">{{ __('labels.professional_files_empty_verification') }}</p>
                        @else
                            <div class="row g-3 mb-3">
                                @foreach ($filesForType as $file)
                                    <div class="col-6 col-sm-4 col-md-3">
                                        <div class="professional-files-thumb border rounded overflow-hidden position-relative">
                                            <a href="{{ route('professional.files.verification.show', $file) }}" target="_blank" rel="noopener noreferrer" class="d-block">
                                                <img src="{{ route('professional.files.verification.show', $file) }}" alt="" loading="lazy" class="w-100" style="height: 120px; object-fit: cover;">
                                            </a>
                                            <form method="post" action="{{ route('professional.files.destroy', $file) }}" class="position-absolute top-0 end-0 p-1" data-confirm="{{ __('labels.professional_files_confirm_remove_image') }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-dark opacity-75 py-0 px-1" title="{{ __('labels.professional_files_btn_remove') }}" aria-label="{{ __('labels.professional_files_btn_remove') }}">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <form method="post" action="{{ route('professional.files.verification') }}" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="document_type" value="{{ $docType }}">
                            <div class="input-group input-group-sm flex-nowrap mb-2">
                                <input type="file" name="documents[]" class="form-control" accept="image/jpeg,image/png,image/webp,image/gif" multiple required>
                                <button type="submit" class="btn btn-success">{{ __('labels.professional_files_btn_upload') }}</button>
                            </div>
                            @if ($errors->has('documents') && old('document_type') !== null && (string) old('document_type') === (string) $docType)
                                <x-input-error :messages="$errors->get('documents')" class="small" />
                            @endif
                            @if ($errors->has('document_type') && old('document_type') !== null && (string) old('document_type') === (string) $docType)
                                <x-input-error :messages="$errors->get('document_type')" class="small" />
                            @endif
                        </form>
                    </div>
                @endforeach

                @if ($otherVerificationFiles->isNotEmpty())
                    <div class="professional-files-doc-section border rounded-3 p-3 mb-0 bg-light" id="doc-{{ \App\Models\ProfessionalFile::FILE_TYPE_CODE_OTHER }}">
                        <h3 class="h6 fw-semibold mb-2">{{ __('labels.professional_files_doc_other') }}</h3>
                        <p class="text-muted small mb-3">{{ __('labels.professional_files_doc_other_hint') }}</p>
                        <div class="row g-3">
                            @foreach ($otherVerificationFiles as $file)
                                <div class="col-6 col-sm-4 col-md-3">
                                    <div class="professional-files-thumb border rounded overflow-hidden position-relative">
                                        <a href="{{ route('professional.files.verification.show', $file) }}" target="_blank" rel="noopener noreferrer" class="d-block">
                                            <img src="{{ route('professional.files.verification.show', $file) }}" alt="" loading="lazy" class="w-100" style="height: 120px; object-fit: cover;">
                                        </a>
                                        <form method="post" action="{{ route('professional.files.destroy', $file) }}" class="position-absolute top-0 end-0 p-1" data-confirm="{{ __('labels.professional_files_confirm_remove_image') }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-dark opacity-75 py-0 px-1" title="{{ __('labels.professional_files_btn_remove') }}" aria-label="{{ __('labels.professional_files_btn_remove') }}">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Fotos públicas --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <h2 class="h6 fw-bold mb-3">
                    <i class="fas fa-images me-2 text-info" aria-hidden="true"></i>{{ __('labels.professional_files_public_section') }}
                </h2>
                <p class="text-muted small">
                    {{ __('labels.professional_files_public_help', ['max' => \App\Services\Professional\ProfessionalProfileFilesService::MAX_PUBLIC_PHOTOS]) }}
                </p>

                @if ($publicFiles->isEmpty())
                    <p class="small text-muted fst-italic mb-3">{{ __('labels.professional_files_empty_public') }}</p>
                @else
                    <div class="row g-3 mb-3">
                        @foreach ($publicFiles as $file)
                            <div class="col-6 col-sm-4 col-md-3">
                                <div class="professional-files-thumb border rounded overflow-hidden position-relative">
                                    <img src="{{ asset('storage/'.$file->path) }}" alt="" loading="lazy" class="w-100" style="height: 120px; object-fit: cover;">
                                    <form method="post" action="{{ route('professional.files.destroy', $file) }}" class="position-absolute top-0 end-0 p-1" data-confirm="{{ __('labels.professional_files_confirm_remove_image') }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-dark opacity-75 py-0 px-1" title="{{ __('labels.professional_files_btn_remove') }}" aria-label="{{ __('labels.professional_files_btn_remove') }}">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <form method="post" action="{{ route('professional.files.public') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="input-group input-group-sm flex-nowrap mb-2">
                        <input type="file" name="photos[]" class="form-control" accept="image/jpeg,image/png,image/webp,image/gif" multiple required>
                        <button type="submit" class="btn btn-info text-white">{{ __('labels.professional_files_btn_upload') }}</button>
                    </div>
                    <x-input-error :messages="$errors->get('photos')" class="small" />
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            (function () {
                // Passo 1: localizar formulários que exigem confirmação antes do envio.
                // Passo 2: no submit, mostrar diálogo; se cancelar, bloquear o envio.
                document.querySelectorAll('form[data-confirm]').forEach(function (formulario) {
                    formulario.addEventListener('submit', function (evento) {
                        var mensagem = formulario.getAttribute('data-confirm');
                        if (mensagem && !window.confirm(mensagem)) {
                            evento.preventDefault();
                        }
                    });
                });
            })();
        </script>
    @endpush
</x-app-layout>
