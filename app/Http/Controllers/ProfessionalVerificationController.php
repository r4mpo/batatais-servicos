<?php

namespace App\Http\Controllers;

use App\Http\Requests\SolicitacaoVerificacaoProfissionalRequest;
use App\Services\Professional\ProfessionalVerificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Tela e envio de solicitação de verificação (selo) para o profissional.
 */
class ProfessionalVerificationController extends Controller
{
    public function __construct(
        private readonly ProfessionalVerificationService $servicoVerificacao,
    ) {}

    /**
     * Página informativa com checklist, valor simbólico e envio; histórico quando houver.
     */
    public function exibirFormulario(Request $requisicao): View|RedirectResponse
    {
        return $this->responder($this->servicoVerificacao->montarFormulario($requisicao->user()));
    }

    /**
     * Cria a solicitação: valida requisitos, senão devolve o formulário com a lista faltando.
     */
    public function armazenar(SolicitacaoVerificacaoProfissionalRequest $requisicao): RedirectResponse
    {
        return $this->responder($this->servicoVerificacao->tentarRegistrarSolicitacao($requisicao->user()));
    }
}
