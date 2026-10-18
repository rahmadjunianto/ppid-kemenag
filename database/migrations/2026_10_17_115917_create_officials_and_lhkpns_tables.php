<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel Master Pejabat Struktural
        Schema::create('officials', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Contoh: Dr. AKHMAD SRUJI BAHTIAR, M.Pd.I
            $table->string('nip', 30)->nullable(); // Contoh: 197204122000031002
            $table->string('position'); // Contoh: KEPALA KANTOR WILAYAH
            $table->string('photo_path')->nullable(); // Opsional untuk Profil Pejabat (Poin A.5)
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Tabel Dokumen LHKPN per Tahun untuk tiap Pejabat
        Schema::create('official_lhkpns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('official_id')->constrained('officials')->cascadeOnDelete();
            $table->year('year')->index(); // Contoh: 2024, 2025, 2026
            $table->string('position_at_year')->nullable(); // Jabatan saat tahun pelaporan (jika mutasi)
            $table->string('file_path')->nullable(); // File PDF Bukti LHKPN
            $table->string('external_url')->nullable(); // Alternatif jika pakai link Google Drive / e-LHKPN
            $table->unsignedBigInteger('download_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('official_lhkpns');
        Schema::dropIfExists('officials');
    }
};