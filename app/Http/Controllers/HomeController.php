<?php

namespace App\Http\Controllers;

use App\Services\Home\HomeProfessionalCategoriesService;
use Illuminate\View\View;

/**
 * Página inicial pública e agregação de conteúdo da home.
 */
class HomeController extends Controller
{
    public function __construct(
        private readonly HomeProfessionalCategoriesService $professionalCategoriesService
    ) {}

    /**
     * Renderiza a landing page com categorias profissionais destacadas.
     */
    public function index(): View
    {
        return view('welcome', [
            'homepageProfessions' => $this->professionalCategoriesService->obterProfissoesDaPaginaInicial(),
        ]);
    }
}
