<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('public_information_items', function (Blueprint $table) {
            // Mengubah tipe kolom action_type menjadi string agar bebas menerima nilai apa saja (external, internal, file, content, dll)
            $table->string('action_type', 50)->default('external')->change();
        });
    }

    public function down(): void
    {
        Schema::table('public_information_items', function (Blueprint $table) {
            $table->string('action_type')->change();
        });
    }
};