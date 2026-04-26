<?php

namespace App\Services\Professional;

use App\Models\Professional;
use App\Models\ProfessionalFile;
use App\Models\ProfessionalVerificationRequest;
use App\Models\User;
use App\Repositories\ProfessionalRepository;
use App\Repositories\ProfessionalVerificationRequestRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

/**
 * Regras de negócio da fila de verificação: checagem de requisitos, registro e consultas para o selo.
 */
class ProfessionalVerificationService
{
    public const TAMANHO_MINIMO_DESCRICAO = 20;

    public const CHAVE_REQUISITO_PROFISSAO = 'requisito_profissao';

    public const CHAVE_REQUISITO_RG_TEXTO = 'requisito_rg_texto';

    public const CHAVE_REQUISITO_CPF_TEXTO = 'requisito_cpf_texto';

    public const CHAVE_REQUISITO_TITULO = 'requisito_titulo';

    public const CHAVE_REQUISITO_DESCRICAO = 'requisito_descricao';

    public const CHAVE_REQUISITO_VALOR_HORA = 'requisito_valor_hora';

    public const CHAVE_REQUISITO_FOTO_PERFIL = 'requisito_foto_perfil';

    public const CHAVE_REQUISITO_ARQUIVO_RG = 'requisito_arquivo_rg';

    public const CHAVE_REQUISITO_ARQUIVO_CPF = 'requisito_arquivo_cpf';

    public const CHAVE_REQUISITO_ARQUIVO_CNH = 'requisito_arquivo_cnh';

    public const CHAVE_REQUISITO_ARQUIVO_CERTIFICADO = 'requisito_arquivo_certificado';

    public const CHAVE_REQUISITO_ARQUIVO_DIPLOMA = 'requisito_arquivo_diploma';

    public function __construct(
        private readonly ProfessionalRepository $repositorioProfissional,
        private readonly ProfessionalVerificationRequestRepository $repositorioSolicitacoes,
    ) {}

    public function possuiVerificacaoAprovada(int $idUsuario): bool
    {
        return $this->repositorioSolicitacoes->possuiAprovadaParaUserId($idUsuario);
    }

    public function possuiSolicitacaoPendente(int $idUsuario): bool
    {
        return $this->repositorioSolicitacoes->possuiPendenteParaUserId($idUsuario);
    }

    /**
     * @return list<string> chaves {@see self::CHAVE_*}; lista vazia = apto a abrir a solicitação
     */
    public function requisitosFaltando(Professional $profissional): array
    {
        $faltas = [];
        $profissao = $profissional->profession;
        if ($profissao === null) {
            $faltas[] = self::CHAVE_REQUISITO_PROFISSAO;
        }

        if (trim((string) $profissional->rg) === '') {
            $faltas[] = self::CHAVE_REQUISITO_RG_TEXTO;
        }

        $cpf = trim((string) $profissional->cpf);
        if ($cpf === '' || strlen($cpf) !== 11) {
            $faltas[] = self::CHAVE_REQUISITO_CPF_TEXTO;
        }

        if (trim((string) $profissional->title) === '') {
            $faltas[] = self::CHAVE_REQUISITO_TITULO;
        }

        if (! $this->descricaoValida($profissional->description)) {
            $faltas[] = self::CHAVE_REQUISITO_DESCRICAO;
        }

        if ((int) $profissional->hourly_rate_cents <= 0) {
            $faltas[] = self::CHAVE_REQUISITO_VALOR_HORA;
        }

        $usuario = $profissional->user;
        if ($usuario === null || $usuario->profile_photo === null || $usuario->profile_photo === '') {
            $faltas[] = self::CHAVE_REQUISITO_FOTO_PERFIL;
        }

        if (! $this->possuiArquivoDeVerificacao($profissional, ProfessionalFile::FILE_TYPE_CODE_RG)) {
            $faltas[] = self::CHAVE_REQUISITO_ARQUIVO_RG;
        }
        if (! $this->possuiArquivoDeVerificacao($profissional, ProfessionalFile::FILE_TYPE_CODE_CPF)) {
            $faltas[] = self::CHAVE_REQUISITO_ARQUIVO_CPF;
        }

        if ($profissao !== null) {
            if ($profissao->verificacao_exige_cnh && ! $this->possuiArquivoDeVerificacao($profissional, ProfessionalFile::FILE_TYPE_CODE_CNH)) {
                $faltas[] = self::CHAVE_REQUISITO_ARQUIVO_CNH;
            }
            if ($profissao->verificacao_exige_certificado && ! $this->possuiArquivoDeVerificacao($profissional, ProfessionalFile::FILE_TYPE_CODE_CERTIFICATE)) {
                $faltas[] = self::CHAVE_REQUISITO_ARQUIVO_CERTIFICADO;
            }
            if ($profissao->verificacao_exige_diploma && ! $this->possuiArquivoDeVerificacao($profissional, ProfessionalFile::FILE_TYPE_CODE_DIPLOMA)) {
                $faltas[] = self::CHAVE_REQUISITO_ARQUIVO_DIPLOMA;
            }
        }

        return $faltas;
    }

    public function listarSolicitacoes(User $usuario): Collection
    {
        return $this->repositorioSolicitacoes->listarPorUserIdMaisRecentePrimeiro($usuario->id);
    }

    /**
     * Tenta gravar uma nova solicitação em análise, exceto se já houver aprovação, pendência ou faltar requisito.
     *
     * @return array{ok: bool, faltas: list<string>, pendente: bool, jaAprovada: bool, criada: ?ProfessionalVerificationRequest}
     */
    public function tentarRegistrarSolicitacao(User $usuario): array
    {
        if (! $usuario->isProfessional()) {
            return ['ok' => false, 'faltas' => [], 'pendente' => false, 'jaAprovada' => false, 'criada' => null];
        }

        $profissional = $this->repositorioProfissional->findFirstForUserId($usuario->id);
        if ($profissional === null) {
            return [
                'ok' => false,
                'faltas' => [self::CHAVE_REQUISITO_PROFISSAO],
                'pendente' => false,
                'jaAprovada' => false,
                'criada' => null,
            ];
        }

        if ($this->repositorioSolicitacoes->possuiAprovadaParaUserId($usuario->id)) {
            return [
                'ok' => false,
                'faltas' => [],
                'pendente' => false,
                'jaAprovada' => true,
                'criada' => null,
            ];
        }

        if ($this->repositorioSolicitacoes->possuiPendenteParaUserId($usuario->id)) {
            return [
                'ok' => false,
                'faltas' => [],
                'pendente' => true,
                'jaAprovada' => false,
                'criada' => null,
            ];
        }

        $faltas = $this->requisitosFaltando($this->garantirProfissaoELegivel($profissional));
        if ($faltas !== []) {
            return [
                'ok' => false,
                'faltas' => $faltas,
                'pendente' => false,
                'jaAprovada' => false,
                'criada' => null,
            ];
        }

        $criada = $this->repositorioSolicitacoes->inserirPendente($usuario->id);

        return [
            'ok' => true,
            'faltas' => [],
            'pendente' => false,
            'jaAprovada' => false,
            'criada' => $criada,
        ];
    }

    /**
     * Garante relação de profissão (flags de documentos) carregada para a checagem.
     */
    public function garantirProfissaoELegivel(Professional $profissional): Professional
    {
        if (! $profissional->relationLoaded('profession')) {
            $profissional->load('profession');
        }

        return $profissional;
    }

    public function chaveMensagemFlashSucesso(): string
    {
        return 'professional-verification-request-submitted';
    }

    public function chaveMensagemFlashPendente(): string
    {
        return 'professional-verification-pending-exists';
    }

    public function chaveMensagemFlashJaAprovada(): string
    {
        return 'professional-verification-already-approved';
    }

    private function possuiArquivoDeVerificacao(Professional $profissional, string $codigoTipo): bool
    {
        return $profissional->profileFiles()
            ->where('kind', ProfessionalFile::KIND_VERIFICATION_DOCUMENT)
            ->where('file_type', $codigoTipo)
            ->exists();
    }

    private function descricaoValida(?string $texto): bool
    {
        if ($texto === null) {
            return false;
        }

        return Str::length(trim($texto)) >= self::TAMANHO_MINIMO_DESCRICAO;
    }
}
