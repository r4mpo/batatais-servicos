<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('professional_files', function (Blueprint $table) {
            $table->string('file_type', 32)->nullable()->after('kind');
            $table->index(['professional_id', 'kind', 'file_type']);
        });

        if (Schema::hasTable('professional_files')) {
            DB::table('professional_files')
                ->where('kind', 'public_photo')
                ->whereNull('file_type')
                ->update(['file_type' => 'showcase']);

            DB::table('professional_files')
                ->where('kind', 'verification_document')
                ->whereNull('file_type')
                ->update(['file_type' => 'other']);
        }
    }

    public function down(): void
    {
        Schema::table('professional_files', function (Blueprint $table) {
            $table->dropIndex(['professional_id', 'kind', 'file_type']);
            $table->dropColumn('file_type');
        });
    }
};
