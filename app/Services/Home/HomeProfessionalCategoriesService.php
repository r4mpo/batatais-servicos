<?php

namespace App\Services\Home;

use App\Repositories\ProfessionRepository;
use Illuminate\Database\Eloquent\Collection;

/**
 * Fornece as profissões exibidas na seção de categorias da página inicial.
 */
class HomeProfessionalCategoriesService
{
    public function __construct(
        private readonly ProfessionRepository $professionsRepository
    ) {}

    /**
     * Coleção de profissões marcadas para a home, via {@see ProfessionRepository::getHomepageProfessions()}.
     *
     * @return Collection<int, \App\Models\Profession>
     */
    public function getHomepageProfessions(): Collection
    {
        return $this->professionsRepository->getHomepageProfessions();
    }
}
