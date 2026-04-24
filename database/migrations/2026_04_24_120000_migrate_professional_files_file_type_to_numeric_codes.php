<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * `file_type` passa a usar códigos de três dígitos (000, 001, …) em vez de slugs.
 * A coluna continua `string(32)` (códigos 000–999).
 */
return new class extends Migration
{
    private const TO_NUMERIC = [
        'showcase' => '000',
        'other' => '001',
        'rg' => '002',
        'cpf' => '003',
        'certificate' => '004',
        'diploma' => '005',
        'cnh' => '006',
    ];

    public function up(): void
    {
        foreach (self::TO_NUMERIC as $anterior => $novo) {
            DB::table('professional_files')
                ->where('file_type', $anterior)
                ->update(['file_type' => $novo]);
        }
    }

    public function down(): void
    {
        $deTras = array_flip(self::TO_NUMERIC);
        foreach ($deTras as $numerico => $antigo) {
            DB::table('professional_files')
                ->where('file_type', $numerico)
                ->update(['file_type' => $antigo]);
        }
    }
};
