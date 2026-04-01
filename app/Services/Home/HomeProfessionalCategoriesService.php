<?php

namespace App\Services\Home;

use App\Repositories\ProfessionRepository;
use Illuminate\Database\Eloquent\Collection;

class HomeProfessionalCategoriesService
{
    public function __construct(
        private readonly ProfessionRepository $professionsRepository
    ) {}

    public function getHomepageProfessions(): Collection
    {
        return $this->professionsRepository->getHomepageProfessions();
    }
}
