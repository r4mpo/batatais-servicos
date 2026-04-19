<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Registra bindings no container (vazio neste projeto por padrão).
     */
    public function register(): void
    {
        //
    }

    /**
     * Inicialização global: estilo de paginação alinhado ao Bootstrap 5.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();
    }
}
