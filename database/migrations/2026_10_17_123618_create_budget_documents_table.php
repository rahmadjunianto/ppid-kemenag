<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('budget_documents', function (Blueprint $table) {
            $table->id();
            $table->string('division_name'); // Contoh: "Bagian Tata Usaha", "Bidang Pendidikan Madrasah"
            $table->string('code', 20)->nullable(); // Contoh: "01", "02"
            $table->year('year')->index(); // 2024, 2025, 2026
            $table->text('description')->nullable(); // Keterangan ringkas dokumen DIPA
            $table->string('file_path')->nullable(); // File PDF DIPA
            $table->string('external_url')->nullable(); // Alternatif link
            $table->unsignedBigInteger('download_count')->default(0);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('budget_documents');
    }
};