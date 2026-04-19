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

/**
 * Regras de negócio dos arquivos ligados ao cadastro profissional (exceto roteamento HTTP).
 *
 * Inclui: foto de perfil no {@see User}, documentos de verificação, fotos de vitrine,
 * soft delete de {@see ProfessionalFile} e montagem de resposta para documento privado.
 */
class ProfessionalProfileFilesService
{
    /** Limite total de arquivos de verificação (todos os tipos). */
    public const MAX_VERIFICATION_FILES = 15;

    /** Limite por tipo de documento (RG, CPF, etc.). */
    public const MAX_VERIFICATION_FILES_PER_TYPE = 5;

    public const MAX_PUBLIC_PHOTOS = 24;

    /**
     * Substitui a foto de perfil: grava arquivo em disco público e atualiza `users.profile_photo`.
     *
     * Passo a passo:
     * 1. Resolver o diretório físico e criá-lo se ainda não existir.
     * 2. Se já houver nome salvo no usuário, apagar o arquivo antigo do disco.
     * 3. Gerar nome com hash e mover o upload para o diretório.
     * 4. Persistir somente o nome do arquivo na coluna `profile_photo`.
     */
    public function substituirFotoPerfil(User $usuario, UploadedFile $arquivo): void
    {
        $diretorioPublico = public_path(User::PROFILE_PHOTO_PUBLIC_DIR);
        if (! is_dir($diretorioPublico)) {
            mkdir($diretorioPublico, 0755, true);
        }

        if ($usuario->profile_photo) {
            $caminhoAntigo = $diretorioPublico.DIRECTORY_SEPARATOR.$usuario->profile_photo;
            if (is_file($caminhoAntigo)) {
                @unlink($caminhoAntigo);
            }
        }

        $extensao = strtolower($arquivo->getClientOriginalExtension() ?: 'jpg');
        $nomeArquivo = md5($arquivo->getClientOriginalName().strtotime('now').uniqid('', true).'.'.$extensao).'.'.$extensao;

        $arquivo->move($diretorioPublico, $nomeArquivo);

        $usuario->update(['profile_photo' => $nomeArquivo]);
    }

    /**
     * Remove foto de perfil: apaga arquivo em disco e zera `users.profile_photo`.
     *
     * Passo a passo:
     * 1. Se não houver nome no banco, encerrar.
     * 2. Remover arquivo físico se existir.
     * 3. Atualizar usuário com `profile_photo` nulo.
     */
    public function limparFotoPerfil(User $usuario): void
    {
        if (! $usuario->profile_photo) {
            return;
        }

        $caminhoCompleto = public_path(User::PROFILE_PHOTO_PUBLIC_DIR.DIRECTORY_SEPARATOR.$usuario->profile_photo);
        if (is_file($caminhoCompleto)) {
            @unlink($caminhoCompleto);
        }

        $usuario->update(['profile_photo' => null]);
    }

    /**
     * Grava documentos de verificação no disco local e cria linhas em `professional_files`.
     *
     * Passo a passo:
     * 1. Contar quantos documentos de verificação já existem (total e por `file_type`).
     * 2. Para cada upload: interromper se algum limite global ou por tipo for atingido.
     * 3. Armazenar no subdiretório do profissional/tipo e registrar metadados na tabela.
     *
     * @param  array<int, UploadedFile>  $arquivos
     */
    public function adicionarDocumentosVerificacao(Professional $profissional, array $arquivos, string $tipoDocumento): void
    {
        $totalGeral = $profissional->profileFiles()
            ->where('kind', ProfessionalFile::KIND_VERIFICATION_DOCUMENT)
            ->count();

        $totalNoTipo = $profissional->profileFiles()
            ->where('kind', ProfessionalFile::KIND_VERIFICATION_DOCUMENT)
            ->where('file_type', $tipoDocumento)
            ->count();

        $subdiretorio = 'professional-verification/'.$profissional->id.'/'.$tipoDocumento;

        foreach ($arquivos as $arquivo) {
            if ($totalGeral >= self::MAX_VERIFICATION_FILES) {
                break;
            }
            if ($totalNoTipo >= self::MAX_VERIFICATION_FILES_PER_TYPE) {
                break;
            }

            $caminhoArmazenado = $arquivo->store($subdiretorio, 'local');

            ProfessionalFile::query()->create([
                'professional_id' => $profissional->id,
                'kind' => ProfessionalFile::KIND_VERIFICATION_DOCUMENT,
                'file_type' => $tipoDocumento,
                'disk' => 'local',
                'path' => $caminhoArmazenado,
                'original_name' => $arquivo->getClientOriginalName(),
                'sort_order' => 0,
            ]);

            $totalGeral++;
            $totalNoTipo++;
        }
    }

    /**
     * Grava fotos de vitrine no disco `public` e registra em `professional_files`.
     *
     * Passo a passo:
     * 1. Contar fotos públicas atuais e obter o próximo `sort_order`.
     * 2. Para cada arquivo: parar se o limite máximo for atingido.
     * 3. `store` na pasta da galeria do profissional e criar registro com `file_type` de vitrine.
     *
     * @param  array<int, UploadedFile>  $arquivos
     */
    public function adicionarFotosPublicas(Professional $profissional, array $arquivos): void
    {
        $quantidadeAtual = $profissional->profileFiles()
            ->where('kind', ProfessionalFile::KIND_PUBLIC_PHOTO)
            ->count();

        $pastaGaleria = 'professionals/'.$profissional->id.'/gallery';
        $ordem = (int) $profissional->profileFiles()
            ->where('kind', ProfessionalFile::KIND_PUBLIC_PHOTO)
            ->max('sort_order');

        foreach ($arquivos as $arquivo) {
            if ($quantidadeAtual >= self::MAX_PUBLIC_PHOTOS) {
                break;
            }

            $caminhoArmazenado = $arquivo->store($pastaGaleria, 'public');
            $ordem++;

            ProfessionalFile::query()->create([
                'professional_id' => $profissional->id,
                'kind' => ProfessionalFile::KIND_PUBLIC_PHOTO,
                'file_type' => ProfessionalFile::FILE_TYPE_SHOWCASE,
                'disk' => 'public',
                'path' => $caminhoArmazenado,
                'original_name' => $arquivo->getClientOriginalName(),
                'sort_order' => $ordem,
            ]);

            $quantidadeAtual++;
        }
    }

    /**
     * Exclusão lógica do registro; o arquivo em disco só some no `forceDelete` do modelo.
     *
     * Passo a passo:
     * 1. Executar soft delete (preenche `deleted_at`).
     */
    public function excluirArquivo(ProfessionalFile $arquivo): void
    {
        $arquivo->delete();
    }

    /**
     * Monta resposta HTTP para exibir um documento de verificação (arquivo no disco privado).
     *
     * Passo a passo:
     * 1. Resolver caminho absoluto com o driver da coluna `disk`.
     * 2. Devolver `response()->file` com disposition inline.
     */
    public function transmitirDocumentoVerificacao(ProfessionalFile $arquivo): BinaryFileResponse
    {
        $caminhoAbsoluto = Storage::disk($arquivo->disk)->path($arquivo->path);

        return response()->file($caminhoAbsoluto, [
            'Content-Disposition' => 'inline; filename="'.basename($arquivo->original_name ?: $arquivo->path).'"',
        ]);
    }

    /**
     * Regra de limite: documentos de verificação (total e por tipo).
     *
     * Passo a passo:
     * 1. Contar existentes (total e por `file_type`).
     * 2. Se a soma com os novos ultrapassar o limite total, montar redirect com erro.
     * 3. Senão, se ultrapassar o limite por tipo, idem.
     * 4. Se estiver dentro dos limites, retornar null para o controller prosseguir.
     *
     * @return RedirectResponse|null
     */
    public function redirecionarSeLimitesDeVerificacaoExcedidos(
        Professional $profissional,
        string $tipoDocumento,
        int $quantidadeNovos,
        Request $requisicao,
    ): ?RedirectResponse {
        $totalExistente = $profissional->profileFiles()
            ->where('kind', ProfessionalFile::KIND_VERIFICATION_DOCUMENT)
            ->count();

        $existenteNoTipo = $profissional->profileFiles()
            ->where('kind', ProfessionalFile::KIND_VERIFICATION_DOCUMENT)
            ->where('file_type', $tipoDocumento)
            ->count();

        if ($totalExistente + $quantidadeNovos > self::MAX_VERIFICATION_FILES) {
            return redirect()
                ->route('professional.files')
                ->withFragment('doc-'.$tipoDocumento)
                ->withErrors([
                    'documents' => __('labels.professional_files_verification_limit', [
                        'max' => self::MAX_VERIFICATION_FILES,
                    ]),
                ])
                ->withInput($requisicao->only('document_type'));
        }

        if ($existenteNoTipo + $quantidadeNovos > self::MAX_VERIFICATION_FILES_PER_TYPE) {
            return redirect()
                ->route('professional.files')
                ->withFragment('doc-'.$tipoDocumento)
                ->withErrors([
                    'documents' => __('labels.professional_files_verification_limit_per_type', [
                        'max' => self::MAX_VERIFICATION_FILES_PER_TYPE,
                    ]),
                ])
                ->withInput($requisicao->only('document_type'));
        }

        return null;
    }

    /**
     * Regra de limite: fotos públicas (vitrine).
     *
     * Passo a passo:
     * 1. Contar registros com `kind` de foto pública.
     * 2. Se existentes + novos excederem o máximo, retornar redirect com erro.
     * 3. Caso contrário, retornar null.
     *
     * @return RedirectResponse|null
     */
    public function redirecionarSeLimiteDeFotosPublicasExcedido(
        Professional $profissional,
        int $quantidadeNovos,
    ): ?RedirectResponse {
        $existentes = $profissional->profileFiles()
            ->where('kind', ProfessionalFile::KIND_PUBLIC_PHOTO)
            ->count();

        if ($existentes + $quantidadeNovos > self::MAX_PUBLIC_PHOTOS) {
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
