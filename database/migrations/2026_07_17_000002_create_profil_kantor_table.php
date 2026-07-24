<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profil_kantor', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kantor', 150);
            $table->text('alamat');
            $table->string('no_telepon', 15);
            $table->string('email', 100);
            $table->string('logo', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profil_kantor');
    }
};
