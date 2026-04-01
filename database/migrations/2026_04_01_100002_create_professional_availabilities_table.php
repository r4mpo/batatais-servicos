<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('professional_availabilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('professional_id')->constrained('professionals')->cascadeOnUpdate()->cascadeOnDelete();
            /** 0 = domingo … 6 = sábado (Carbon::dayOfWeek) */
            $table->unsignedTinyInteger('day_of_week');
            $table->time('starts_at')->nullable();
            $table->time('ends_at')->nullable();
            $table->boolean('is_full_day')->default(false);
            $table->timestamps();

            $table->index(['professional_id', 'day_of_week']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('professional_availabilities');
    }
};
