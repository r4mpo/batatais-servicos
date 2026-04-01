<?php

namespace App\Http\Controllers;

use App\Services\Professional\ProfessionalListingService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfessionalController extends Controller
{
    public function __construct(
        private readonly ProfessionalListingService $listingService
    ) {}

    public function index(Request $request): View
    {
        return view('professionals.index', $this->listingService->buildListing($request));
    }
}
