<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropForeign(['professional_user_id']);
            $table->dropIndex(['professional_user_id', 'status']);
            $table->dropIndex(['professional_user_id', 'value_withdrawn']);
        });

        Schema::table('services', function (Blueprint $table) {
            $table->unsignedBigInteger('professional_user_id')->nullable()->change();
        });

        Schema::table('services', function (Blueprint $table) {
            $table->foreign('professional_user_id')->references('id')->on('users');
            $table->index(['professional_user_id', 'status']);
            $table->index(['professional_user_id', 'value_withdrawn']);
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropForeign(['professional_user_id']);
            $table->dropIndex(['professional_user_id', 'status']);
            $table->dropIndex(['professional_user_id', 'value_withdrawn']);
        });

        Schema::table('services', function (Blueprint $table) {
            $table->unsignedBigInteger('professional_user_id')->nullable(false)->change();
        });

        Schema::table('services', function (Blueprint $table) {
            $table->foreign('professional_user_id')->references('id')->on('users');
            $table->index(['professional_user_id', 'status']);
            $table->index(['professional_user_id', 'value_withdrawn']);
        });
    }
};
