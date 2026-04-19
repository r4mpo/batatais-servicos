<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfessionalProfilePhotoRequest;
use App\Http\Requests\ProfessionalPublicPhotosRequest;
use App\Http\Requests\ProfessionalVerificationDocumentsRequest;
use App\Models\Professional;
use App\Models\ProfessionalFile;
use App\Models\User;
use App\Repositories\ProfessionalRepository;
use App\Services\Professional\ProfessionalProfileFilesService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Upload e gestão de fotos do perfil profissional (públicas, verificação e galeria futura).
 */
class ProfessionalProfileFilesController extends Controller
{
    public function __construct(
        private readonly ProfessionalRepository $professionalRepository,
        private readonly ProfessionalProfileFilesService $filesService,
    ) {}

    public function edit(Request $request): RedirectResponse|View
    {
        $user = $request->user();
        if (! $user instanceof User || ! $user->isProfessional()) {
            return redirect()->route('dashboard');
        }

        $professional = $this->professionalRepository->findFirstForUserId($user->id);
        if ($professional === null) {
            return redirect()->route('professional.setup');
        }

        $professional->load([
            'user',
            'profileFiles' => function ($query) {
                $query->orderBy('kind')->orderBy('file_type')->orderBy('sort_order')->orderBy('id');
            },
        ]);

        return view('professional.files', [
            'professional' => $professional,
        ]);
    }

    public function updateProfilePhoto(ProfessionalProfilePhotoRequest $request): RedirectResponse
    {
        $this->resolveProfessionalOrAbort($request);
        $user = $request->user();
        abort_unless($user instanceof User, 403);

        $this->filesService->replaceProfilePhoto($user, $request->file('photo'));

        return redirect()
            ->route('professional.files')
            ->with('status', 'professional-profile-photo-updated');
    }

    public function destroyProfilePhoto(Request $request): RedirectResponse
    {
        $this->resolveProfessionalOrAbort($request);
        $user = $request->user();
        abort_unless($user instanceof User, 403);

        $this->filesService->clearProfilePhoto($user);

        return redirect()
            ->route('professional.files')
            ->with('status', 'professional-profile-photo-removed');
    }

    public function storeVerificationDocuments(ProfessionalVerificationDocumentsRequest $request): RedirectResponse
    {
        $professional = $this->resolveProfessionalOrAbort($request);
        $documentType = $request->validated('document_type');
        $documents = $request->file('documents', []);

        $redirect = $this->filesService->redirectIfVerificationLimitsExceeded(
            $professional,
            $documentType,
            count($documents),
            $request
        );
        if ($redirect !== null) {
            return $redirect;
        }

        $this->filesService->addVerificationDocuments($professional, $documents, $documentType);

        return redirect()
            ->route('professional.files')
            ->withFragment('doc-'.$documentType)
            ->with('status', 'professional-verification-documents-updated');
    }

    public function storePublicPhotos(ProfessionalPublicPhotosRequest $request): RedirectResponse
    {
        $professional = $this->resolveProfessionalOrAbort($request);
        $photos = $request->file('photos', []);

        $redirect = $this->filesService->redirectIfPublicPhotosLimitExceeded($professional, count($photos));
        if ($redirect !== null) {
            return $redirect;
        }

        $this->filesService->addPublicPhotos($professional, $photos);

        return redirect()
            ->route('professional.files')
            ->with('status', 'professional-public-photos-updated');
    }

    public function destroyFile(Request $request, ProfessionalFile $professional_file): RedirectResponse
    {
        $professional = $this->resolveProfessionalOrAbort($request);
        abort_unless($professional_file->professional_id === $professional->id, 403);

        $this->filesService->deleteFile($professional_file);

        return redirect()
            ->route('professional.files')
            ->with('status', 'professional-file-removed');
    }

    public function showVerificationDocument(Request $request, ProfessionalFile $professional_file): BinaryFileResponse
    {
        $professional = $this->resolveProfessionalOrAbort($request);
        abort_unless($professional_file->professional_id === $professional->id, 403);
        abort_unless($professional_file->isVerificationDocument(), 404);

        return $this->filesService->streamVerificationDocument($professional_file);
    }

    private function resolveProfessionalOrAbort(Request $request): Professional
    {
        $user = $request->user();
        abort_unless($user instanceof User && $user->isProfessional(), 403);

        $professional = $this->professionalRepository->findFirstForUserId($user->id);
        abort_if($professional === null, 404);

        return $professional;
    }
}
