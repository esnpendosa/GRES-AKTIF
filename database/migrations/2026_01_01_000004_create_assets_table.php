<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('village_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('asset_categories')->cascadeOnDelete();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            
            // Condition: tidak_digunakan, jarang_digunakan, kurang_produktif, rusak, terbengkalai
            $table->string('condition')->default('kurang_produktif')->index();
            
            // Status: reported, verified, ai_analyzed, community_discussion, prioritized, planning, implementation, productive
            $table->string('status')->default('reported')->index();
            
            $table->string('ownership_type')->default('Pemerintah Desa'); // Pemerintah Desa, Pemerintah Daerah, Yayasan, BUMDes, Lainnya
            $table->decimal('area', 12, 2)->default(0); // area in m²
            $table->decimal('latitude', 10, 7)->index();
            $table->decimal('longitude', 10, 7)->index();
            $table->text('address')->nullable();
            
            // Scores (0 - 100)
            $table->integer('potential_score')->default(50)->index();
            $table->integer('location_score')->default(50);
            $table->integer('accessibility_score')->default(50);
            $table->integer('condition_score')->default(50);
            $table->integer('infrastructure_score')->default(50);
            $table->integer('community_demand_score')->default(50);
            $table->integer('economic_score')->default(50);
            
            // Recommended activation type
            $table->string('target_activation_use')->nullable();
            $table->decimal('estimated_economic_value', 15, 2)->default(0); // Indicative IDR valuation / potential revenue
            $table->integer('supporters_count')->default(0);
            
            $table->timestamp('verified_at')->nullable();
            $table->unsignedBigInteger('verified_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
