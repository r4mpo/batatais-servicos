<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('profile_photo')->nullable()->after('remember_token');
        });

        $destDir = public_path('img/docs/profile');
        if (! is_dir($destDir)) {
            mkdir($destDir, 0755, true);
        }

        if (Schema::hasColumn('professionals', 'profile_photo_path')) {
            $rows = DB::table('professionals')->whereNotNull('profile_photo_path')->get();
            foreach ($rows as $p) {
                $path = $p->profile_photo_path;
                $full = Storage::disk('public')->path($path);
                if (! is_file($full)) {
                    continue;
                }

                $ext = strtolower(pathinfo($full, PATHINFO_EXTENSION) ?: 'jpg');
                $basename = basename((string) $path);
                $filename = md5($basename.strtotime('now').uniqid((string) $p->user_id, true).'.'.$ext).'.'.$ext;

                if (@copy($full, $destDir.DIRECTORY_SEPARATOR.$filename)) {
                    DB::table('users')->where('id', $p->user_id)->update(['profile_photo' => $filename]);
                    Storage::disk('public')->delete($path);
                }
            }

            Schema::table('professionals', function (Blueprint $table) {
                $table->dropColumn('profile_photo_path');
            });
        }
    }

    public function down(): void
    {
        Schema::table('professionals', function (Blueprint $table) {
            $table->string('profile_photo_path')->nullable()->after('description');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('profile_photo');
        });
    }
};
