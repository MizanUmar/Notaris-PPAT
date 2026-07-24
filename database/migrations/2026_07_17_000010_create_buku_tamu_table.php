<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('buku_tamu', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade')->onUpdate('cascade');
            $table->string('nama_tamu', 150);
            $table->string('instansi', 150)->nullable();
            $table->string('nomor_hp', 20)->nullable();
            $table->text('keperluan')->nullable();
            $table->date('tanggal_kunjungan');
            $table->string('qr_code', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('buku_tamu');
    }
};
