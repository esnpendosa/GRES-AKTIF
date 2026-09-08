<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_ideas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('category')->default('UMKM'); // UMKM, Kuliner, Pertanian, Pariwisata, Pendidikan, Olahraga, Workshop, Coworking, Lainnya
            $table->text('description')->nullable();
            $table->integer('votes_count')->default(0)->index();
            $table->boolean('is_ai_recommended')->default(false);
            $table->timestamps();
        });

        Schema::create('idea_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('idea_id')->constrained('asset_ideas')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('vote_type')->default('upvote');
            $table->timestamps();

            $table->unique(['idea_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('idea_votes');
        Schema::dropIfExists('asset_ideas');
    }
};
