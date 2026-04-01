<?php

namespace App\Repositories;

use App\Models\Profession;
use Illuminate\Database\Eloquent\Collection;

class ProfessionRepository
{
    public function getHomepageProfessions(): Collection
    {
        return Profession::query()
            ->where('show_on_homepage', true)
            ->orderBy('id')
            ->get();
    }
}
