<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('villages', function (Blueprint $table) {
            // Menyimpan array titik koordinat polygon batas desa dalam format JSON
            // Contoh: [[lat1,lng1],[lat2,lng2],...]
            $table->json('boundary')->nullable()->after('longitude');
        });
    }

    public function down(): void
    {
        Schema::table('villages', function (Blueprint $table) {
            $table->dropColumn('boundary');
        });
    }
};
