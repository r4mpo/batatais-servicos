<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfessionalProfilePhotoRequest;
use App\Http\Requests\ProfessionalPublicPhotosRequest;
use App\Http\Requests\ProfessionalVerificationDocumentsRequest;
use App\Models\ProfessionalFile;
use App\Services\Professional\ProfessionalProfileFilesService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Controller: autenticação, autorização e delegação ao {@see ProfessionalProfileFilesService}.
 * Regras de negócio ficam no service.
 */
class ProfessionalProfileFilesController extends Controller
{
    public function __construct(
        private readonly ProfessionalProfileFilesService $filesService,
    ) {}

    public function edit(Request $request): RedirectResponse|View
    {
        return $this->responder($this->filesService->montarPagina($request->user()));
    }

    public function atualizarFotoPerfil(ProfessionalProfilePhotoRequest $request): RedirectResponse
    {
        return $this->responder(
            $this->filesService->substituirFotoERedirecionar($request->user(), $request->file('photo'))
        );
    }

    public function excluirFotoPerfil(Request $request): RedirectResponse
    {
        return $this->responder($this->filesService->limparFotoERedirecionar($request->user()));
    }

    public function armazenarDocumentosVerificacao(ProfessionalVerificationDocumentsRequest $request): RedirectResponse
    {
        return $this->responder($this->filesService->adicionarDocumentosERedirecionar(
            $request->user(),
            $request->file('documents', []),
            $request->validated('document_type'),
        ));
    }

    public function armazenarFotosPublicas(ProfessionalPublicPhotosRequest $request): RedirectResponse
    {
        return $this->responder($this->filesService->adicionarFotosPublicasERedirecionar(
            $request->user(),
            $request->file('photos', []),
        ));
    }

    public function excluirArquivo(Request $request, ProfessionalFile $professional_file): RedirectResponse
    {
        return $this->responder(
            $this->filesService->excluirArquivoERedirecionar($request->user(), $professional_file)
        );
    }

    public function exibirDocumentoVerificacao(Request $request, ProfessionalFile $professional_file): BinaryFileResponse
    {
        return $this->responder(
            $this->filesService->transmitirDocumentoVerificacao($request->user(), $professional_file)
        );
    }
}
