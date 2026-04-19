<?php

namespace App\Repositories;

use App\Models\Profession;
use Illuminate\Database\Eloquent\Collection;

/**
 * Repositório de leitura/escrita da tabela `professions` e consultas relacionadas à listagem.
 */
class ProfessionRepository
{
    /**
     * Profissões marcadas para exibição na home (`show_on_homepage`), ordenadas por ID.
     */
    public function obterProfissoesDaPaginaInicial(): Collection
    {
        return Profession::query()
            ->where('show_on_homepage', true)
            ->orderBy('id')
            ->get();
    }

    /**
     * Lista todas as profissões ordenadas por título (filtros e selects do site).
     *
     * @return Collection<int, Profession>
     */
    public function orderedForProfessionalsFilter(): Collection
    {
        return Profession::query()
            ->orderBy('title')
            ->get(['id', 'title', 'slug']);
    }

    /**
     * Resolve o ID numérico da profissão a partir do slug (ex.: query string `categoria`).
     *
     * @param  string  $slug  Identificador textual único da profissão.
     * @return int|null  ID encontrado ou `null` se inexistente.
     */
    public function findIdBySlug(string $slug): ?int
    {
        $id = Profession::query()->where('slug', $slug)->value('id');

        return $id !== null ? (int) $id : null;
    }
}
