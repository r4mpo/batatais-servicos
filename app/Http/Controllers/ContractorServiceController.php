<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContractorServiceRequest;
use App\Models\Service;
use App\Services\Contractor\ContractorServiceControlService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContractorServiceController extends Controller
{
    public function __construct(
        private readonly ContractorServiceControlService $controlService,
    ) {}

    public function index(Request $request): View
    {
        return $this->responder($this->controlService->listar($request->user(), $request));
    }

    public function create(Request $request): View|RedirectResponse
    {
        return $this->responder($this->controlService->montarCriacao($request->user()));
    }

    public function store(ContractorServiceRequest $request): View|RedirectResponse
    {
        return $this->responder($this->controlService->criar($request->user(), $request->validated()));
    }

    public function show(Request $request, Service $service): View
    {
        return $this->responder($this->controlService->exibir($request->user(), $service));
    }

    public function edit(Request $request, Service $service): View
    {
        return $this->responder($this->controlService->montarEdicao($request->user(), $service));
    }

    public function update(ContractorServiceRequest $request, Service $service): View|RedirectResponse
    {
        return $this->responder($this->controlService->atualizar($request->user(), $service, $request->validated()));
    }

    public function destroy(Request $request, Service $service): RedirectResponse
    {
        return $this->responder($this->controlService->excluir($request->user(), $service));
    }

    public function pay(Request $request, Service $service): RedirectResponse
    {
        return $this->responder($this->controlService->marcarComoPago($request->user(), $service));
    }

    public function searchProfessionals(Request $request): JsonResponse
    {
        return $this->responder($this->controlService->buscarProfissionais(
            $request->user(),
            (string) $request->query('q', ''),
        ));
    }
}
