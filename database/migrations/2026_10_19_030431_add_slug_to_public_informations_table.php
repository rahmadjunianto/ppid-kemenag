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
    Schema::table('public_informations', function (Blueprint $table) {
        $table->string('slug')->nullable()->unique();
    });
}

public function down(): void
{
    Schema::table('public_informations', function (Blueprint $table) {
        $table->dropUnique(['slug']);
        $table->dropColumn('slug');
    });
}
};
