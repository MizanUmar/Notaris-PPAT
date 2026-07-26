<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('layanan', function (Blueprint $table) {
            $table->enum('kategori', ['akta', 'surat'])->default('akta')->after('nama_layanan');
        });
    }

    public function down(): void
    {
        Schema::table('layanan', function (Blueprint $table) {
            $table->dropColumn('kategori');
        });
    }
};
