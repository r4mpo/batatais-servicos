<?php

namespace App\Providers;

use Illuminate\Database\Events\ConnectionEstablished;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Event;
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

        // SQL Server em português interpreta Y-m-d como ano-dia-mês no tipo datetime.
        // unprepared é necessário: SET via prepared statement não altera a sessão.
        Event::listen(function (ConnectionEstablished $event) {
            if ($event->connection->getDriverName() === 'sqlsrv') {
                $event->connection->unprepared('SET DATEFORMAT ymd');
            }
        });
    }
}
