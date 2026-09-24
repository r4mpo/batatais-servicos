<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * No SQL Server um unique comum aceita um único NULL.
 * Índices filtrados mantêm a unicidade de CPF/CNPJ preenchidos e permitem vários vazios,
 * como no MySQL e no SQLite.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'sqlsrv') {
            return;
        }

        Schema::table('professionals', function (Blueprint $table) {
            $table->dropUnique(['cpf']);
            $table->dropUnique(['cnpj']);
        });

        DB::statement('CREATE UNIQUE INDEX professionals_cpf_unique ON professionals (cpf) WHERE cpf IS NOT NULL');
        DB::statement('CREATE UNIQUE INDEX professionals_cnpj_unique ON professionals (cnpj) WHERE cnpj IS NOT NULL');
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'sqlsrv') {
            return;
        }

        DB::statement('DROP INDEX professionals_cpf_unique ON professionals');
        DB::statement('DROP INDEX professionals_cnpj_unique ON professionals');

        Schema::table('professionals', function (Blueprint $table) {
            $table->unique('cpf');
            $table->unique('cnpj');
        });
    }
};
