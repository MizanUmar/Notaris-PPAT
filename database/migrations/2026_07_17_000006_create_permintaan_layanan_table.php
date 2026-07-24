<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permintaan_layanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('client')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('layanan_id')->constrained('layanan')->onDelete('cascade')->onUpdate('cascade');
            $table->date('tanggal_permintaan');
            $table->enum('status', ['Menunggu', 'Diproses', 'Selesai', 'Ditolak'])->default('Menunggu');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permintaan_layanan');
    }
};
