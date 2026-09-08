<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('villages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('district_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('postal_code')->nullable();
            $table->decimal('latitude', 10, 7)->default(-7.1566);
            $table->decimal('longitude', 10, 7)->default(112.6555);
            $table->decimal('area_sqkm', 8, 2)->default(0);
            $table->integer('population')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('villages');
    }
};
