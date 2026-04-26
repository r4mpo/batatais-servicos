<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('professional_verification_requests', function (Blueprint $tabela) {
            $tabela->id();
            $tabela->foreignId('user_id')->constrained('users')->cascadeOnUpdate()->cascadeOnDelete();
            $tabela->foreignId('decided_by_user_id')->nullable()->constrained('users')->cascadeOnUpdate()->nullOnDelete();
            $tabela->timestamp('decided_at')->nullable();
            $tabela->boolean('approved')->nullable()->comment('null=pending, 0=rejected, 1=approved');
            $tabela->text('notes')->nullable();
            $tabela->timestamps();
            $tabela->softDeletes();

            $tabela->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('professional_verification_requests');
    }
};
