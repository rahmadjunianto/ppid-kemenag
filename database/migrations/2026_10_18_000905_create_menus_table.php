<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('label'); // Nama menu (contoh: "Profil", "Arsip Renstra")
            $table->string('url')->nullable(); // Link tujuan (bisa URL eksternal atau route internal)
            $table->foreignId('parent_id')->nullable()->constrained('menus')->cascadeOnDelete(); // Untuk Submenu
            $table->integer('sort_order')->default(0); // Urutan tampil
            $table->boolean('is_active')->default(true); // Status aktif/tidak
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};