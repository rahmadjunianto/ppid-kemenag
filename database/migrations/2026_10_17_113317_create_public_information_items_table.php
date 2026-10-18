<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('public_information_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('public_information_id')
                ->constrained('public_informations')
                ->cascadeOnDelete();

            $table->string('label'); // Contoh: "Tahun 2026", "Semester I"
            $table->year('year')->nullable()->index();

            // Cukup gunakan 'file', 'link', atau 'content' (halaman teks langsung)
            $table->enum('action_type', ['file', 'link', 'content'])->default('file');
            $table->string('file_path')->nullable();
            $table->string('external_url')->nullable();
            $table->longText('content')->nullable(); // Pengganti tabel pages

            $table->unsignedBigInteger('download_count')->default(0);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('public_information_items');
    }
};
