<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('professionals', function (Blueprint $table) {
            $table->string('rg', 32)->nullable()->after('profession_id');
            $table->string('cpf', 14)->nullable()->unique()->after('rg');
            $table->string('cnpj', 18)->nullable()->unique()->after('cpf');
        });
    }

    public function down(): void
    {
        Schema::table('professionals', function (Blueprint $table) {
            $table->dropUnique(['cpf']);
            $table->dropUnique(['cnpj']);
            $table->dropColumn(['rg', 'cpf', 'cnpj']);
        });
    }
};
