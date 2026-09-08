<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('objective')->nullable();
            $table->string('category')->default('UMKM');
            $table->decimal('budget_estimate', 15, 2)->default(0);
            $table->string('funding_source')->default('APBDes & CSR'); // APBDes, APBD Kab. Gresik, CSR BUMN/Swasta, BUMDes Mandiri
            $table->string('responsible_department')->default('Pemerintah Desa'); // Dinas PMD, Bappedalitbang, Diskoperindag, Pemdes
            $table->date('start_date')->nullable();
            $table->date('target_completion')->nullable();
            
            // Status: planning, approved, in_progress, completed, cancelled
            $table->string('status')->default('planning')->index();
            $table->integer('progress_percentage')->default(0);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('asset_project_updates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('asset_projects')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->text('notes')->nullable();
            $table->integer('progress_percentage')->default(0);
            $table->json('media_urls')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_project_updates');
        Schema::dropIfExists('asset_projects');
    }
};
