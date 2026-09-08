<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_analyses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained()->cascadeOnDelete();
            $table->string('provider')->default('heuristic'); // heuristic, openai, anthropic
            $table->string('model')->default('gresik-asset-v1');
            $table->string('analysis_type')->default('comprehensive');
            
            // Sub-scores
            $table->integer('location_score')->default(50);
            $table->integer('accessibility_score')->default(50);
            $table->integer('condition_score')->default(50);
            $table->integer('infrastructure_score')->default(50);
            $table->integer('community_score')->default(50);
            $table->integer('economic_score')->default(50);
            $table->integer('potential_score')->default(50);
            $table->integer('confidence_score')->default(85);
            
            $table->text('summary')->nullable();
            $table->json('recommendations')->nullable(); // Ranked recommendations [{name, score, rationale}]
            $table->json('economic_scenarios')->nullable(); // Scenario A, B, C with jobs, tenants, impact
            $table->longText('raw_response')->nullable();
            $table->timestamps();
        });

        Schema::create('community_consensus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained()->cascadeOnDelete();
            $table->integer('total_suggestions')->default(0);
            $table->string('dominant_category')->nullable();
            $table->text('consensus_summary')->nullable();
            $table->json('clusters')->nullable(); // [{category: 'UMKM', percentage: 44}, {category: 'Kuliner', percentage: 31}, ...]
            $table->integer('confidence_percentage')->default(85);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('community_consensus');
        Schema::dropIfExists('ai_analyses');
    }
};
