<?php

namespace App\Http\Controllers;

use App\Services\Home\HomeProfessionalCategoriesService;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(
        private readonly HomeProfessionalCategoriesService $professionalCategoriesService
    ) {}

    public function index(): View
    {
        return view('welcome', [
            'homepageProfessions' => $this->professionalCategoriesService->getHomepageProfessions(),
        ]);
    }
}
