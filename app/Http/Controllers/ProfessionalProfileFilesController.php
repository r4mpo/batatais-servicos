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
use App\Services\Professional\ProfessionalVerificationService;
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
        private readonly ProfessionalRepository $professionalRepository,
        private readonly ProfessionalProfileFilesService $filesService,
        private readonly ProfessionalVerificationService $verificacaoService,
    ) {}

    public function edit(Request $request): RedirectResponse|View
    {
        // Passo 1: garantir usuário profissional autenticado.
        $usuario = $request->user();
        if (! $usuario instanceof User || ! $usuario->isProfessional()) {
            return redirect()->route('dashboard');
        }

        // Passo 2: carregar cadastro em `professionals` ou redirecionar ao setup.
        $profissional = $this->professionalRepository->findFirstForUserId($usuario->id);
        if ($profissional === null) {
            return redirect()->route('professional.setup');
        }

        $profissional->load([
            'user',
            'profileFiles' => function ($consulta) {
                $consulta->orderBy('kind')->orderBy('file_type')->orderBy('sort_order')->orderBy('id');
            },
        ]);

        return view('professional.files', [
            'professional' => $profissional,
            'possuiVerificacaoAprovada' => $this->verificacaoService->possuiVerificacaoAprovada($usuario->id),
        ]);
    }

    public function atualizarFotoPerfil(ProfessionalProfilePhotoRequest $request): RedirectResponse
    {
        $this->resolverProfissionalOuAbortar($request);
        $usuario = $request->user();
        abort_unless($usuario instanceof User, 403);

        $this->filesService->substituirFotoPerfil($usuario, $request->file('photo'));

        return redirect()
            ->route('professional.files')
            ->with('status', 'professional-profile-photo-updated');
    }

    public function excluirFotoPerfil(Request $request): RedirectResponse
    {
        $this->resolverProfissionalOuAbortar($request);
        $usuario = $request->user();
        abort_unless($usuario instanceof User, 403);

        $this->filesService->limparFotoPerfil($usuario);

        return redirect()
            ->route('professional.files')
            ->with('status', 'professional-profile-photo-removed');
    }

    public function armazenarDocumentosVerificacao(ProfessionalVerificationDocumentsRequest $request): RedirectResponse
    {
        $profissional = $this->resolverProfissionalOuAbortar($request);
        $tipoDocumento = $request->validated('document_type');
        $documentos = $request->file('documents', []);

        $redirecionamento = $this->filesService->redirecionarSeLimitesDeVerificacaoExcedidos(
            $profissional,
            $tipoDocumento,
            count($documentos),
            $request
        );
        if ($redirecionamento !== null) {
            return $redirecionamento;
        }

        $this->filesService->adicionarDocumentosVerificacao($profissional, $documentos, $tipoDocumento);

        return redirect()
            ->route('professional.files')
            ->withFragment('doc-'.$tipoDocumento)
            ->with('status', 'professional-verification-documents-updated');
    }

    public function armazenarFotosPublicas(ProfessionalPublicPhotosRequest $request): RedirectResponse
    {
        $profissional = $this->resolverProfissionalOuAbortar($request);
        $fotos = $request->file('photos', []);

        $redirecionamento = $this->filesService->redirecionarSeLimiteDeFotosPublicasExcedido($profissional, count($fotos));
        if ($redirecionamento !== null) {
            return $redirecionamento;
        }

        $this->filesService->adicionarFotosPublicas($profissional, $fotos);

        return redirect()
            ->route('professional.files')
            ->with('status', 'professional-public-photos-updated');
    }

    public function excluirArquivo(Request $request, ProfessionalFile $professional_file): RedirectResponse
    {
        $profissional = $this->resolverProfissionalOuAbortar($request);
        abort_unless($professional_file->professional_id === $profissional->id, 403);

        $this->filesService->excluirArquivo($professional_file);

        return redirect()
            ->route('professional.files')
            ->with('status', 'professional-file-removed');
    }

    public function exibirDocumentoVerificacao(Request $request, ProfessionalFile $professional_file): BinaryFileResponse
    {
        $profissional = $this->resolverProfissionalOuAbortar($request);
        abort_unless($professional_file->professional_id === $profissional->id, 403);
        abort_unless($professional_file->isVerificationDocument(), 404);

        return $this->filesService->transmitirDocumentoVerificacao($professional_file);
    }

    /**
     * Garante conta profissional com linha em `professionals` ou encerra com 403/404.
     *
     * Passo a passo:
     * 1. Exigir usuário autenticado com perfil profissional.
     * 2. Buscar o primeiro `professionals` da conta.
     */
    private function resolverProfissionalOuAbortar(Request $request): Professional
    {
        $usuario = $request->user();
        abort_unless($usuario instanceof User && $usuario->isProfessional(), 403);

        $profissional = $this->professionalRepository->findFirstForUserId($usuario->id);
        abort_if($profissional === null, 404);

        return $profissional;
    }
}
