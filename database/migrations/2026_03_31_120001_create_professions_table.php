<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('professions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('practice_area_id')->constrained('practice_areas')->cascadeOnUpdate()->restrictOnDelete();
            $table->string('title');
            $table->text('description');
            $table->string('slug')->unique();
            $table->string('icon', 128)->nullable();
            $table->boolean('show_on_homepage')->default(false);
            $table->boolean('is_global_listing')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('professions');
    }
};
