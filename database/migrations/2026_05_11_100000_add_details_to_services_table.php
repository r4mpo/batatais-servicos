<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->string('title')->nullable()->after('professional_user_id');
            $table->text('description')->nullable()->after('title');
            $table->string('address_postal_code', 9)->nullable()->after('description');
            $table->string('address_street')->nullable()->after('address_postal_code');
            $table->string('address_number', 32)->nullable()->after('address_street');
            $table->string('address_complement', 128)->nullable()->after('address_number');
            $table->string('address_neighborhood', 128)->nullable()->after('address_complement');
            $table->string('address_city', 128)->nullable()->after('address_neighborhood');
            $table->string('address_state', 2)->nullable()->after('address_city');
            $table->date('scheduled_start_date')->nullable()->after('address_state');
            $table->date('scheduled_end_date')->nullable()->after('scheduled_start_date');
            $table->time('scheduled_start_time')->nullable()->after('scheduled_end_date');
            $table->time('scheduled_end_time')->nullable()->after('scheduled_start_time');
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn([
                'title',
                'description',
                'address_postal_code',
                'address_street',
                'address_number',
                'address_complement',
                'address_neighborhood',
                'address_city',
                'address_state',
                'scheduled_start_date',
                'scheduled_end_date',
                'scheduled_start_time',
                'scheduled_end_time',
            ]);
        });
    }
};
