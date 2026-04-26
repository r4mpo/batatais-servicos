<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('professional_verification_requests', function (Blueprint $tabela) {
            if (! Schema::hasColumn('professional_verification_requests', 'decided_at')) {
                $tabela->timestamp('decided_at')->nullable()->after('decided_by_user_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('professional_verification_requests', function (Blueprint $tabela) {
            if (Schema::hasColumn('professional_verification_requests', 'decided_at')) {
                $tabela->dropColumn('decided_at');
            }
        });
    }
};
