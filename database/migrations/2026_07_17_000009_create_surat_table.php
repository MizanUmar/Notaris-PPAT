<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permintaan_id')->nullable()->constrained('permintaan_layanan')->onDelete('set null')->onUpdate('cascade');
            $table->string('nomor_surat', 100);
            $table->string('jenis_surat', 100);
            $table->date('tanggal_surat');
            $table->string('file_surat', 255);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat');
    }
};
