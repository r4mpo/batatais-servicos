<?php

namespace App\Http\Controllers;

use App\Services\Professional\ProfessionalServiceHistoryService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfessionalServiceHistoryController extends Controller
{
    public function __construct(
        private readonly ProfessionalServiceHistoryService $historyService,
    ) {}

    public function index(Request $request): View
    {
        return $this->responder($this->historyService->montarHistorico($request->user()));
    }
}
