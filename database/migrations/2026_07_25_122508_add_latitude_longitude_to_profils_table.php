<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Sesuaikan nama tabel 'profils' jika nama tabel kamu berbeda
     * (misalnya 'profil' tanpa 's', cek php artisan tinker -> \App\Models\Profil::first()->getTable()).
     */
    public function up(): void
    {
        Schema::table('profils', function (Blueprint $table) {
            $table->decimal('latitude', 10, 6)->nullable()->after('alamat');
            $table->decimal('longitude', 10, 6)->nullable()->after('latitude');
        });
    }

    public function down(): void
    {
        Schema::table('profils', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude']);
        });
    }
};
