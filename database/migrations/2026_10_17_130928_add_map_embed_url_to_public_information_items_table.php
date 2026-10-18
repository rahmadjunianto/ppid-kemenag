<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('public_information_items', function (Blueprint $table) {
            // Kolom untuk menyimpan URL src iframe Google Maps
            $table->text('map_embed_url')->nullable()->after('content');
        });
    }

    public function down(): void
    {
        Schema::table('public_information_items', function (Blueprint $table) {
            $table->dropColumn('map_embed_url');
        });
    }
};