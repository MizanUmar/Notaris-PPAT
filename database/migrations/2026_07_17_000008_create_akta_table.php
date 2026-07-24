<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('akta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permintaan_id')->constrained('permintaan_layanan')->onDelete('cascade')->onUpdate('cascade');
            $table->string('nomor_akta', 100);
            $table->string('nama_akta', 100);
            $table->date('tanggal_akta');
            $table->string('file_akta', 255);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('akta');
    }
};
