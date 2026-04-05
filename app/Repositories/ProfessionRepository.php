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

    public function orderedForProfessionalsFilter(): Collection
    {
        return Profession::query()
            ->orderBy('title')
            ->get(['id', 'title', 'slug']);
    }

    public function findIdBySlug(string $slug): ?int
    {
        $id = Profession::query()->where('slug', $slug)->value('id');

        return $id !== null ? (int) $id : null;
    }
}
