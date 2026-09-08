<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('village_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('asset_categories')->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            
            // Step 3 condition
            $table->string('condition')->default('tidak_tahu'); // tidak_digunakan, jarang_digunakan, kurang_produktif, rusak, terbengkalai, tidak_tahu
            
            // Step 5 community opinion
            $table->string('suggested_use')->nullable(); // UMKM, Kuliner, Pertanian, Pariwisata, Pendidikan, Olahraga, Perdagangan, Workshop, Coworking, Lainnya
            
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->text('address')->nullable();
            $table->json('photos')->nullable(); // Array of image paths
            
            // Verification status: pending, approved, rejected, info_requested
            $table->string('status')->default('pending')->index();
            $table->text('verification_notes')->nullable();
            $table->unsignedBigInteger('verified_by')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->unsignedBigInteger('created_asset_id')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_reports');
    }
};
