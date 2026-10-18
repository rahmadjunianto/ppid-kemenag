<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('yearly_documents', function (Blueprint $table) {
            $table->id();
            $table->string('type')->index(); // Contoh: 'renstra', 'bmn', 'statistik', 'laporan-kinerja'
            $table->string('title'); // Judul dokumen / laporan
            $table->year('year')->index(); // Tahun dokumen (2024, 2025, 2026)
            $table->text('description')->nullable(); // Keterangan ringkas
            $table->string('file_path')->nullable(); // File PDF dokumen
            $table->string('external_url')->nullable(); // Link eksternal (Google Drive / Looker Studio)
            $table->unsignedBigInteger('download_count')->default(0);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('yearly_documents');
    }
};