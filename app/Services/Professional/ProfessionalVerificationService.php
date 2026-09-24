<?php

namespace App\Services\Professional;

use App\Http\Responses\ResultadoResposta;
use App\Models\Professional;
use App\Models\ProfessionalFile;
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

    public function montarFormulario(mixed $usuario): ResultadoResposta
    {
        if (! $usuario instanceof User || ! $usuario->isProfessional()) {
            return ResultadoResposta::redirecionar('dashboard');
        }

        $profissional = $this->repositorioProfissional->findFirstForUserId($usuario->id);
        if ($profissional === null) {
            return ResultadoResposta::redirecionar('professional.setup');
        }

        $profissional = $this->garantirProfissaoELegivel($profissional);
        if ($profissional->user === null) {
            $profissional->load('user');
        }

        return ResultadoResposta::pagina('professional.verificacao', [
            'profissional' => $profissional,
            'historico' => $this->listarSolicitacoes($usuario),
            'faltasRequisito' => $this->requisitosFaltando($profissional),
            'possuiVerificacaoAprovada' => $this->possuiVerificacaoAprovada($usuario->id),
            'possuiSolicitacaoPendente' => $this->possuiSolicitacaoPendente($usuario->id),
        ]);
    }

    /**
     * Tenta gravar uma nova solicitação em análise, exceto se já houver aprovação, pendência ou faltar requisito.
     */
    public function tentarRegistrarSolicitacao(mixed $usuario): ResultadoResposta
    {
        if (! $usuario instanceof User || ! $usuario->isProfessional()) {
            return $this->redirecionarVerificacao(faltas: []);
        }

        $profissional = $this->repositorioProfissional->findFirstForUserId($usuario->id);
        if ($profissional === null) {
            return $this->redirecionarVerificacao(faltas: [self::CHAVE_REQUISITO_PROFISSAO]);
        }

        if ($this->repositorioSolicitacoes->possuiAprovadaParaUserId($usuario->id)) {
            return ResultadoResposta::redirecionar(
                'professional.verificacao',
                status: $this->chaveMensagemFlashJaAprovada(),
            );
        }

        if ($this->repositorioSolicitacoes->possuiPendenteParaUserId($usuario->id)) {
            return ResultadoResposta::redirecionar(
                'professional.verificacao',
                status: $this->chaveMensagemFlashPendente(),
            );
        }

        $faltas = $this->requisitosFaltando($this->garantirProfissaoELegivel($profissional));
        if ($faltas !== []) {
            return $this->redirecionarVerificacao(faltas: $faltas);
        }

        $this->repositorioSolicitacoes->inserirPendente($usuario->id);

        return ResultadoResposta::redirecionar(
            'professional.verificacao',
            status: $this->chaveMensagemFlashSucesso(),
        );
    }

    /**
     * @param  list<string>  $faltas
     */
    private function redirecionarVerificacao(array $faltas): ResultadoResposta
    {
        return ResultadoResposta::redirecionar(
            'professional.verificacao',
            sessao: ['requisitos_verificacao_faltando' => $faltas],
        );
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
