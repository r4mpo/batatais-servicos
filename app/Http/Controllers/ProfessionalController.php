<?php

namespace App\Http\Controllers;

use App\Services\Professional\ProfessionalListingService;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Listagem pública de profissionais com filtros na query string.
 */
class ProfessionalController extends Controller
{
    public function __construct(
        private readonly ProfessionalListingService $listingService
    ) {}

    /**
     * Exibe a grade paginada de profissionais conforme filtros do {@see Request}.
     */
    public function index(Request $request): View
    {
        return view('professionals.index', $this->listingService->buildListing($request));
    }
}
