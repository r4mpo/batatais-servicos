<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contractor_user_id')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('professional_user_id')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
            $table->unsignedTinyInteger('status');
            $table->unsignedBigInteger('service_value_cents');
            $table->text('contractor_feedback')->nullable();
            $table->text('professional_feedback')->nullable();
            $table->boolean('value_withdrawn')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['professional_user_id', 'status']);
            $table->index(['professional_user_id', 'value_withdrawn']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
