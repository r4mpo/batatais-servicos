<?php

namespace App\Services\Home;

use App\Repositories\ProfessionRepository;
use Illuminate\Database\Eloquent\Collection;

/**
 * Camada de serviço da home: profissões exibidas na seção de categorias.
 */
class HomeProfessionalCategoriesService
{
    public function __construct(
        private readonly ProfessionRepository $professionsRepository
    ) {}

    /**
     * Passo a passo:
     * 1. Delegar ao repositório a leitura de `professions` com `show_on_homepage`.
     *
     * @return Collection<int, \App\Models\Profession>
     */
    public function obterProfissoesDaPaginaInicial(): Collection
    {
        return $this->professionsRepository->obterProfissoesDaPaginaInicial();
    }
}
