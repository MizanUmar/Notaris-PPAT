<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('checklist_persyaratan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permintaan_id')->constrained('permintaan_layanan')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('persyaratan_id')->constrained('persyaratan_dokumen')->onDelete('cascade')->onUpdate('cascade');
            $table->boolean('status')->default(false);
            $table->timestamps();

            $table->unique(['permintaan_id', 'persyaratan_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('checklist_persyaratan');
    }
};
