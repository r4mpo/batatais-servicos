<?php

namespace App\Services\Professional;

use App\Models\Professional;
use App\Models\ProfessionalFile;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ProfessionalProfileFilesService
{
    /** Limite total de arquivos de verificação (todos os tipos). */
    public const MAX_VERIFICATION_FILES = 15;

    /** Limite por tipo de documento (RG, CPF, etc.). */
    public const MAX_VERIFICATION_FILES_PER_TYPE = 5;

    public const MAX_PUBLIC_PHOTOS = 24;

    /**
     * Foto de perfil: arquivo em public/{@see User::PROFILE_PHOTO_PUBLIC_DIR} com nome em hash; nome salvo em users.profile_photo.
     */
    public function replaceProfilePhoto(User $user, UploadedFile $file): void
    {
        $dir = public_path(User::PROFILE_PHOTO_PUBLIC_DIR);
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        if ($user->profile_photo) {
            $oldPath = $dir.DIRECTORY_SEPARATOR.$user->profile_photo;
            if (is_file($oldPath)) {
                @unlink($oldPath);
            }
        }

        $extension = strtolower($file->getClientOriginalExtension() ?: 'jpg');
        $filename = md5($file->getClientOriginalName().strtotime('now').uniqid('', true).'.'.$extension).'.'.$extension;

        $file->move($dir, $filename);

        $user->update(['profile_photo' => $filename]);
    }

    public function clearProfilePhoto(User $user): void
    {
        if (! $user->profile_photo) {
            return;
        }

        $path = public_path(User::PROFILE_PHOTO_PUBLIC_DIR.DIRECTORY_SEPARATOR.$user->profile_photo);
        if (is_file($path)) {
            @unlink($path);
        }

        $user->update(['profile_photo' => null]);
    }

    /**
     * @param  array<int, UploadedFile>  $files
     */
    public function addVerificationDocuments(Professional $professional, array $files, string $documentType): void
    {
        $total = $professional->profileFiles()
            ->where('kind', ProfessionalFile::KIND_VERIFICATION_DOCUMENT)
            ->count();

        $forType = $professional->profileFiles()
            ->where('kind', ProfessionalFile::KIND_VERIFICATION_DOCUMENT)
            ->where('file_type', $documentType)
            ->count();

        $subDir = 'professional-verification/'.$professional->id.'/'.$documentType;

        foreach ($files as $file) {
            if ($total >= self::MAX_VERIFICATION_FILES) {
                break;
            }
            if ($forType >= self::MAX_VERIFICATION_FILES_PER_TYPE) {
                break;
            }

            $storedPath = $file->store($subDir, 'local');

            ProfessionalFile::query()->create([
                'professional_id' => $professional->id,
                'kind' => ProfessionalFile::KIND_VERIFICATION_DOCUMENT,
                'file_type' => $documentType,
                'disk' => 'local',
                'path' => $storedPath,
                'original_name' => $file->getClientOriginalName(),
                'sort_order' => 0,
            ]);

            $total++;
            $forType++;
        }
    }

    /**
     * @param  array<int, UploadedFile>  $files
     */
    public function addPublicPhotos(Professional $professional, array $files): void
    {
        $current = $professional->profileFiles()
            ->where('kind', ProfessionalFile::KIND_PUBLIC_PHOTO)
            ->count();

        $baseDir = 'professionals/'.$professional->id.'/gallery';
        $sortOrder = (int) $professional->profileFiles()
            ->where('kind', ProfessionalFile::KIND_PUBLIC_PHOTO)
            ->max('sort_order');

        foreach ($files as $file) {
            if ($current >= self::MAX_PUBLIC_PHOTOS) {
                break;
            }

            $storedPath = $file->store($baseDir, 'public');
            $sortOrder++;

            ProfessionalFile::query()->create([
                'professional_id' => $professional->id,
                'kind' => ProfessionalFile::KIND_PUBLIC_PHOTO,
                'file_type' => ProfessionalFile::FILE_TYPE_SHOWCASE,
                'disk' => 'public',
                'path' => $storedPath,
                'original_name' => $file->getClientOriginalName(),
                'sort_order' => $sortOrder,
            ]);

            $current++;
        }
    }

    /**
     * Remove o registro com soft delete; o arquivo em disco só é apagado em {@see ProfessionalFile} no forceDelete.
     */
    public function deleteFile(ProfessionalFile $file): void
    {
        $file->delete();
    }

    /**
     * Resposta HTTP para visualização de documento de verificação (privado).
     */
    public function streamVerificationDocument(ProfessionalFile $file): BinaryFileResponse
    {
        $absolutePath = Storage::disk($file->disk)->path($file->path);

        return response()->file($absolutePath, [
            'Content-Disposition' => 'inline; filename="'.basename($file->original_name ?: $file->path).'"',
        ]);
    }

    /**
     * @return RedirectResponse|null  Redirecionamento com erro de limite, ou null para seguir com o upload.
     */
    public function redirectIfVerificationLimitsExceeded(
        Professional $professional,
        string $documentType,
        int $incomingCount,
        Request $request,
    ): ?RedirectResponse {
        $existingTotal = $professional->profileFiles()
            ->where('kind', ProfessionalFile::KIND_VERIFICATION_DOCUMENT)
            ->count();

        $existingForType = $professional->profileFiles()
            ->where('kind', ProfessionalFile::KIND_VERIFICATION_DOCUMENT)
            ->where('file_type', $documentType)
            ->count();

        if ($existingTotal + $incomingCount > self::MAX_VERIFICATION_FILES) {
            return redirect()
                ->route('professional.files')
                ->withFragment('doc-'.$documentType)
                ->withErrors([
                    'documents' => __('labels.professional_files_verification_limit', [
                        'max' => self::MAX_VERIFICATION_FILES,
                    ]),
                ])
                ->withInput($request->only('document_type'));
        }

        if ($existingForType + $incomingCount > self::MAX_VERIFICATION_FILES_PER_TYPE) {
            return redirect()
                ->route('professional.files')
                ->withFragment('doc-'.$documentType)
                ->withErrors([
                    'documents' => __('labels.professional_files_verification_limit_per_type', [
                        'max' => self::MAX_VERIFICATION_FILES_PER_TYPE,
                    ]),
                ])
                ->withInput($request->only('document_type'));
        }

        return null;
    }

    /**
     * @return RedirectResponse|null  Redirecionamento com erro de limite, ou null para seguir com o upload.
     */
    public function redirectIfPublicPhotosLimitExceeded(
        Professional $professional,
        int $incomingCount,
    ): ?RedirectResponse {
        $existing = $professional->profileFiles()
            ->where('kind', ProfessionalFile::KIND_PUBLIC_PHOTO)
            ->count();

        if ($existing + $incomingCount > self::MAX_PUBLIC_PHOTOS) {
            return redirect()
                ->route('professional.files')
                ->withErrors([
                    'photos' => __('labels.professional_files_public_limit', [
                        'max' => self::MAX_PUBLIC_PHOTOS,
                    ]),
                ]);
        }

        return null;
    }
}
