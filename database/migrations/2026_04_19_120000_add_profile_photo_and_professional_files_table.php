<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('professionals', function (Blueprint $table) {
            $table->string('profile_photo_path')->nullable()->after('description');
        });

        Schema::create('professional_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('professional_id')->constrained('professionals')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('kind', 32);
            $table->string('disk', 16);
            $table->string('path');
            $table->string('original_name')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['professional_id', 'kind']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('professional_files');

        Schema::table('professionals', function (Blueprint $table) {
            $table->dropColumn('profile_photo_path');
        });
    }
};
