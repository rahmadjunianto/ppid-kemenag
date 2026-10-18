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
        Schema::create('public_informations', function (Blueprint $table) {
            $table->id();
            $table->string('classification')->default('berkala')->index();
            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('public_informations')
                ->cascadeOnDelete();

            $table->string('code', 20)->nullable(); // Contoh: "A", "1", "2.b"
            $table->string('title'); // Contoh: "Ringkasan DIPA"
            $table->text('description')->nullable();

            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('public_informations');
    }
};
