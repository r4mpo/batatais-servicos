<?php

namespace App\Http\Controllers;

use App\Http\Requests\SolicitacaoVerificacaoProfissionalRequest;
use App\Repositories\ProfessionalRepository;
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
        private readonly ProfessionalRepository $repositorioProfissional,
    ) {}

    /**
     * Página informativa com checklist, valor simbólico e envio; histórico quando houver.
     */
    public function exibirFormulario(Request $requisicao): View|RedirectResponse
    {
        $usuario = $requisicao->user();
        if (! $usuario->isProfessional()) {
            return redirect()->route('dashboard');
        }

        $profissional = $this->repositorioProfissional->findFirstForUserId($usuario->id);
        if ($profissional === null) {
            return redirect()->route('professional.setup');
        }

        $profissional = $this->servicoVerificacao->garantirProfissaoELegivel($profissional);
        if ($profissional->user === null) {
            $profissional->load('user');
        }
        $historico = $this->servicoVerificacao->listarSolicitacoes($usuario);
        $faltas = $this->servicoVerificacao->requisitosFaltando($profissional);
        $possuiAprovada = $this->servicoVerificacao->possuiVerificacaoAprovada($usuario->id);
        $possuiPendente = $this->servicoVerificacao->possuiSolicitacaoPendente($usuario->id);

        return view('professional.verificacao', [
            'profissional' => $profissional,
            'historico' => $historico,
            'faltasRequisito' => $faltas,
            'possuiVerificacaoAprovada' => $possuiAprovada,
            'possuiSolicitacaoPendente' => $possuiPendente,
        ]);
    }

    /**
     * Cria a solicitação: valida requisitos, senão devolve o formulário com a lista faltando.
     */
    public function armazenar(SolicitacaoVerificacaoProfissionalRequest $requisicao): RedirectResponse
    {
        $resultado = $this->servicoVerificacao->tentarRegistrarSolicitacao($requisicao->user());
        if ($resultado['ok']) {
            return redirect()
                ->route('professional.verificacao')
                ->with('status', $this->servicoVerificacao->chaveMensagemFlashSucesso());
        }
        if ($resultado['pendente']) {
            return redirect()
                ->route('professional.verificacao')
                ->with('status', $this->servicoVerificacao->chaveMensagemFlashPendente());
        }

        return redirect()
            ->route('professional.verificacao')
            ->with('requisitos_verificacao_faltando', $resultado['faltas']);
    }
}
