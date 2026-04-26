@php
    use App\Services\Professional\ProfessionalVerificationService;

    $statusSucesso = 'professional-verification-request-submitted';
    $statusPendenteFila = 'professional-verification-pending-exists';
    $faltasDaSessao = session('requisitos_verificacao_faltando', []);
    if (!is_array($faltasDaSessao)) {
        $faltasDaSessao = [];
    }
    $faltasAgora = is_array($faltasRequisito ?? null) ? $faltasRequisito : [];
@endphp

<x-app-layout>
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/professional-verificacao.css') }}">
    @endpush

    <div class="verificacao-page py-3 container-fluid px-0 px-sm-3" style="max-width: 820px; margin: 0 auto;">
        <div class="mb-3">
            <a href="{{ route('dashboard') }}" class="text-decoration-none small">
                <i class="fas fa-arrow-left me-1"
                    aria-hidden="true"></i>{{ __('labels.professional_verificacao_voltar') }}
            </a>
        </div>

        <div class="mb-4 d-flex flex-wrap align-items-center gap-2">
            <h1 class="h4 fw-bold mb-0">
                <i class="fas fa-certificate me-2 text-success" aria-hidden="true"></i>
                {{ __('labels.professional_verificacao_pagina_titulo') }}
            </h1>
            @if ($possuiVerificacaoAprovada)
                <span class="badge text-bg-primary">
                    <i class="fas fa-check-circle me-1" aria-hidden="true"></i>
                    {{ __('labels.verificacao_historico_situacao_aprovada') }}
                </span>
            @endif
        </div>
        <p class="text-muted small mb-4">{{ __('labels.professional_verificacao_pagina_intro') }}</p>

        @if (session('status') && session('status') === $statusSucesso)
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ __('labels.professional_verificacao_sucesso') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"
                    aria-label="{{ __('labels.dashboard_alert_dismiss') }}"></button>
            </div>
        @endif
        @if (session('status') && session('status') === $statusPendenteFila)
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                {{ __('labels.professional_verificacao_pendente') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"
                    aria-label="{{ __('labels.dashboard_alert_dismiss') }}"></button>
            </div>
        @endif

        @if ($faltasDaSessao !== [])
            <div class="alert alert-warning" role="alert">
                <h2 class="h6 fw-bold">{{ __('labels.verificacao_falta_titulo') }}</h2>
                <ul class="mb-0 small ps-3">
                    @foreach ($faltasDaSessao as $falta)
                        <li>
                            @if ($falta === ProfessionalVerificationService::CHAVE_REQUISITO_DESCRICAO)
                                {{ __('labels.verificacao_falta_requisito_descricao', [
                                    'min' => ProfessionalVerificationService::TAMANHO_MINIMO_DESCRICAO,
                                ]) }}
                            @else
                                {{ __('labels.verificacao_falta_' . $falta) }}
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card verificacao-destaque-custo border-0 shadow-sm mb-4">
            <div class="card-body p-4 small text-muted">
                <p class="text-body mb-0">
                    <strong class="d-block text-uppercase text-secondary mb-2"
                        style="letter-spacing: 0.04em; font-size: 0.72rem;">{{ __('labels.professional_verificacao_preco_futuro') }}</strong>
                    {{ __('labels.professional_verificacao_preco_simbolico') }}
                </p>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-12 col-md-6">
                <a class="verificacao-acesso verificacao-acesso--setup card text-decoration-none h-100 text-body border-0 shadow-sm"
                    href="{{ route('professional.setup') }}">
                    <div class="card-body p-4 d-flex align-items-start gap-3">
                        <span class="verificacao-acesso__icon text-primary" aria-hidden="true"><i
                                class="fas fa-pen fa-lg"></i></span>
                        <div>
                            <h2 class="h6 mb-1 fw-bold">{{ __('labels.professional_verificacao_cadastro') }}</h2>
                            <p class="mb-0 small text-muted">{{ __('labels.professional_verificacao_cadastro_sub') }}
                            </p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-12 col-md-6">
                <a class="verificacao-acesso verificacao-acesso--arquivos card text-decoration-none h-100 text-body border-0 shadow-sm"
                    href="{{ route('professional.files') }}">
                    <div class="card-body p-4 d-flex align-items-start gap-3">
                        <span class="verificacao-acesso__icon text-info" aria-hidden="true"><i
                                class="fas fa-images fa-lg"></i></span>
                        <div>
                            <h2 class="h6 mb-1 fw-bold">{{ __('labels.professional_verificacao_arquivos') }}</h2>
                            <p class="mb-0 small text-muted">{{ __('labels.professional_verificacao_arquivos_sub') }}
                            </p>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        @if ($faltasAgora === [])
            <p class="alert alert-light border small" role="status">
                <i class="fas fa-clipboard-check text-success me-1" aria-hidden="true"></i>
                {{ __('labels.verificacao_checklist_tudo_pronto') }}
            </p>
        @else
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body p-4 small">
                    <h2 class="h6 fw-bold text-danger">{{ __('labels.verificacao_checklist_titulo') }}</h2>
                    <ul class="ps-3 mb-0 text-muted">
                        @foreach ($faltasAgora as $falta)
                            <li>
                                @if ($falta === ProfessionalVerificationService::CHAVE_REQUISITO_DESCRICAO)
                                    {{ __('labels.verificacao_falta_requisito_descricao', [
                                        'min' => ProfessionalVerificationService::TAMANHO_MINIMO_DESCRICAO,
                                    ]) }}
                                @else
                                    {{ __('labels.verificacao_falta_' . $falta) }}
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form method="post" action="{{ route('professional.verificacao.store') }}"
            class="verificacao-form card border-0 shadow-sm mb-4">
            @csrf
            <div class="card-body p-4 p-md-5">
                @if ($possuiSolicitacaoPendente)
                    <p class="text-warning small mb-4 d-flex align-items-center gap-2">
                        <i class="fas fa-pause-circle"
                            aria-hidden="true"></i>{{ __('labels.verificacao_form_bloqueado') }}
                    </p>
                @endif
                <div class="form-check verificacao-form__check">
                    <input class="form-check-input @error('confirmo_termos') is-invalid @enderror" type="checkbox"
                        name="confirmo_termos" value="1" id="confirmo_termos" @disabled($possuiSolicitacaoPendente)>
                    <label class="form-check-label small text-body-secondary" for="confirmo_termos">
                        {{ __('labels.professional_verificacao_ciente_label') }}
                    </label>
                </div>
                <div class="verificacao-form__erro-check">
                    @error('confirmo_termos')
                        <div class="alert alert-danger py-2 mb-0 small" role="alert">
                            <i class="fas fa-exclamation-triangle me-1" aria-hidden="true"></i>{{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="d-grid d-sm-block mt-4">
                    <button type="submit" class="btn btn-success btn-lg px-4" @disabled($possuiSolicitacaoPendente)>
                        <i class="fas fa-paper-plane me-2"
                            aria-hidden="true"></i>{{ __('labels.professional_verificacao_btn_enviar') }}
                    </button>
                </div>
            </div>
        </form>

        @if (isset($historico) && $historico->isNotEmpty())
            @php
                $itensModalHistorico = $historico->map(function ($linha) {
                    if ($linha->approved === null) {
                        $etiquetaDecisao = __('labels.verificacao_modal_decisao_pendente');
                        $rotuloHora = __('labels.verificacao_modal_rotulo_hora_pendente');
                    } elseif ($linha->decided_at) {
                        $etiquetaDecisao = $linha->decided_at->translatedFormat('d/m/Y H:i');
                        if ($linha->approved) {
                            $rotuloHora = __('labels.verificacao_modal_rotulo_hora_aprovada');
                        } else {
                            $rotuloHora = __('labels.verificacao_modal_rotulo_hora_reprovada');
                        }
                    } else {
                        $etiquetaDecisao = __('labels.verificacao_modal_decisao_sem_data');
                        if ($linha->approved) {
                            $rotuloHora = __('labels.verificacao_modal_rotulo_hora_aprovada');
                        } else {
                            $rotuloHora = __('labels.verificacao_modal_rotulo_hora_reprovada');
                        }
                    }

                    if ($linha->approved === null) {
                        $resumo = __('labels.verificacao_historico_item_resumo_pendente');
                    } elseif ($linha->decided_at) {
                        $d = $linha->decided_at->translatedFormat('d/m/Y') . ' ' . __('labels.verificacao_historico_data_hora', ['hora' => $linha->decided_at->translatedFormat('H:i')]);
                        $resumo = $linha->approved
                            ? __('labels.verificacao_historico_momento_aprovacao', ['quando' => $d])
                            : __('labels.verificacao_historico_momento_reprovacao', ['quando' => $d]);
                    } else {
                        $resumo = $etiquetaDecisao;
                    }

                    $situacaoTexto = $linha->approved === null
                        ? __('labels.verificacao_historico_situacao_pendente')
                        : ($linha->approved
                            ? __('labels.verificacao_historico_situacao_aprovada')
                            : __('labels.verificacao_historico_situacao_reprovada'));
                    $responsavel = $linha->approved === true || $linha->approved === false
                        ? ($linha->decidedBy?->name ?? __('labels.verificacao_historico_responsavel_ausente'))
                        : '—';
                    if ($linha->approved === null) {
                        $responsavel = '—';
                    }
                    $notas = $linha->notes ? $linha->notes : __('labels.verificacao_historico_sem_obs');
                    $corBadge = $linha->approved === null
                        ? 'text-bg-warning'
                        : ($linha->approved ? 'text-bg-success' : 'text-bg-danger');

                    return [
                        'id' => (int) $linha->id,
                        'solicitadoEm' => $linha->created_at?->translatedFormat('d/m/Y H:i') ?? '—',
                        'situacaoTexto' => $situacaoTexto,
                        'situacaoBadge' => $corBadge,
                        'rotuloDataDecisao' => $rotuloHora,
                        'decisaoDataHora' => $etiquetaDecisao,
                        'responsavel' => $responsavel,
                        'notas' => $notas,
                        'resumo' => $resumo,
                    ];
                })->values();
            @endphp
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h2 class="h6 fw-bold mb-1">{{ __('labels.verificacao_historico_titulo') }}</h2>
                    <p class="small text-muted mb-3">{{ __('labels.verificacao_historico_clique_detalhes') }}</p>
                    <div class="verificacao-historico-lista list-group list-group-flush" role="list">
                        @foreach ($historico as $i => $linha)
                            @php
                                $it = $itensModalHistorico[$i];
                            @endphp
                            <button
                                type="button"
                                class="verificacao-historico-item list-group-item list-group-item-action px-0 py-3 d-flex text-start w-100 border-0 border-bottom"
                                data-bs-toggle="modal"
                                data-bs-target="#modalVerificacaoHistorico"
                                data-vh-index="{{ (int) $i }}"
                                id="verificacao-hist-btn-{{ (int) $i }}"
                            >
                                <div class="flex-grow-1 pe-2 min-w-0">
                                    <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                                        <span class="fw-bold text-body">#{{ $linha->id }}</span>
                                        <span class="badge {{ $it['situacaoBadge'] }} small">{{ $it['situacaoTexto'] }}</span>
                                    </div>
                                    <p class="mb-0 small text-body-secondary text-break">
                                        {{ __('labels.verificacao_modal_campo_solicitado') }}:
                                        <strong
                                            class="text-body fw-normal">{{ $it['solicitadoEm'] }}</strong>
                                    </p>
                                    <p class="mb-0 small text-muted text-break mt-1" title="{{ $it['resumo'] }}">
                                        <i class="fas fa-gavel me-1" aria-hidden="true"></i>
                                        <span class="fw-medium text-body-secondary d-inline text-break">
                                            {{ $it['rotuloDataDecisao'] }}: </span>
                                        <span class="text-break font-monospace" style="font-size: 0.8rem;">{{ $it['resumo'] }}</span>
                                    </p>
                                </div>
                                <span class="verificacao-historico-icone-chevron text-muted small align-self-center" aria-hidden="true">
                                    <i class="fas fa-chevron-right"></i>
                                </span>
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="modal fade" id="modalVerificacaoHistorico" tabindex="-1" aria-hidden="true"
                aria-labelledby="modalVerificacaoHistoricoLabel">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header border-0 pb-0">
                            <h2 class="h5 modal-title" id="modalVerificacaoHistoricoLabel">
                                <i class="fas fa-clipboard-list text-primary me-2" aria-hidden="true"></i>
                                {{ __('labels.verificacao_modal_titulo') }} — <span
                                    class="verificacao-modal__id" id="vh-m-id"></span>
                            </h2>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('labels.verificacao_modal_fechar') }}"></button>
                        </div>
                        <div class="modal-body small pt-2">
                            <p class="text-muted" id="vh-m-ajuda" style="font-size: 0.8rem;">{{ __('labels.verificacao_modal_ajuda') }}
                            </p>
                            <dl class="row mb-0" id="vh-m-dl">
                                <dt class="col-12 col-sm-4 text-body-secondary">
                                    {{ __('labels.verificacao_modal_campo_solicitado') }}</dt>
                                <dd class="col-12 col-sm-8" id="vh-m-solicitado"></dd>
                                <dt class="col-12 col-sm-4 text-body-secondary pt-2">
                                    {{ __('labels.verificacao_modal_campo_situacao') }}</dt>
                                <dd class="col-12 col-sm-8 pt-2" id="vh-m-situacao">
                                    <span class="badge" id="vh-m-situacao-badge"></span>
                                </dd>
                                <dt class="col-12 col-sm-4 text-body-secondary pt-2" id="vh-m-decisao-rotulo"></dt>
                                <dd class="col-12 col-sm-8 pt-2" id="vh-m-decisao">—</dd>
                                <dt class="col-12 col-sm-4 text-body-secondary pt-2">
                                    {{ __('labels.verificacao_modal_campo_responsavel') }}</dt>
                                <dd class="col-12 col-sm-8 pt-2" id="vh-m-responsavel">—</dd>
                                <dt class="col-12 col-sm-4 text-body-secondary pt-2">
                                    {{ __('labels.verificacao_modal_campo_observacoes') }}</dt>
                                <dd class="col-12 col-sm-8 pt-2" id="vh-m-notas">—</dd>
                            </dl>
                        </div>
                        <div class="modal-footer border-0">
                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
                                <i class="fas fa-check me-1" aria-hidden="true"></i>{{ __('labels.verificacao_modal_fechar') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            @push('scripts')
                <script>
                    (function() {
                        const itens = @json($itensModalHistorico);
                        const elModal = document.getElementById('modalVerificacaoHistorico');
                        if (!itens || !itens.length || !elModal) { return; }
                        const mId = document.getElementById('vh-m-id');
                        const mSol = document.getElementById('vh-m-solicitado');
                        const mSituB = document.getElementById('vh-m-situacao-badge');
                        const mDecisaoRot = document.getElementById('vh-m-decisao-rotulo');
                        const mDecisao = document.getElementById('vh-m-decisao');
                        const mResp = document.getElementById('vh-m-responsavel');
                        const mNotas = document.getElementById('vh-m-notas');
                        elModal.addEventListener('show.bs.modal', function (evento) {
                            const gatilho = evento.relatedTarget;
                            if (!gatilho) { return; }
                            const idx = parseInt(gatilho.getAttribute('data-vh-index') || '0', 10);
                            const d = itens[idx];
                            if (!d) { return; }
                            mId.textContent = '#' + String(d.id);
                            mSol.textContent = d.solicitadoEm;
                            mSituB.className = 'badge ' + d.situacaoBadge;
                            mSituB.textContent = d.situacaoTexto;
                            mDecisaoRot.textContent = d.rotuloDataDecisao;
                            mDecisao.textContent = d.decisaoDataHora;
                            mResp.textContent = d.responsavel;
                            mNotas.textContent = d.notas;
                        });
                    })();
                </script>
            @endpush
        @endif
    </div>

</x-app-layout>
