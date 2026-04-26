<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('professions', function (Blueprint $tabela) {
            $tabela->boolean('verificacao_exige_cnh')->default(false);
            $tabela->boolean('verificacao_exige_certificado')->default(false);
            $tabela->boolean('verificacao_exige_diploma')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('professions', function (Blueprint $tabela) {
            $tabela->dropColumn([
                'verificacao_exige_cnh',
                'verificacao_exige_certificado',
                'verificacao_exige_diploma',
            ]);
        });
    }
};
