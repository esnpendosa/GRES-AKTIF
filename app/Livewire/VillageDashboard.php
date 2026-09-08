<?php

namespace App\Livewire;

use App\Models\Asset;
use App\Models\AssetCategory;
use App\Models\AssetReport;
use App\Models\AuditLog;
use App\Models\Village;
use App\Services\Ai\AiAnalysisService;
use App\Services\AuditLogger;
use App\Services\GamificationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;

class VillageDashboard extends Component
{
    public ?Village $village;
    public string $reportFilter = 'pending'; // pending, all

    public function mount()
    {
        $user = Auth::user();
        if ($user && $user->village_id) {
            $this->village = Village::find($user->village_id);
        } else {
            // Default to Sukomulyo (Flagship village)
            $this->village = Village::where('code', '3525010001')->first() ?? Village::first();
        }
    }

    public function verifyReport(int $reportId, string $action, string $notes = '')
    {
        $report = AssetReport::findOrFail($reportId);
        $user = Auth::user();

        if ($action === 'approved') {
            // Convert to official asset
            $slug = Str::slug($report->title) . '-' . rand(100, 999);
            $asset = Asset::create([
                'village_id' => $report->village_id ?? $this->village->id,
                'category_id' => $report->category_id ?? AssetCategory::first()->id,
                'created_by' => $report->user_id,
                'name' => $report->title,
                'slug' => $slug,
                'description' => $report->description,
                'condition' => $report->condition,
                'status' => 'verified',
                'ownership_type' => 'Pemerintah Desa',
                'area' => 350,
                'latitude' => $report->latitude,
                'longitude' => $report->longitude,
                'address' => $report->address,
                'target_activation_use' => $report->suggested_use ?? 'Sentra UMKM',
                'verified_at' => now(),
                'verified_by' => $user ? $user->id : null,
            ]);

            // Add photo
            if (!empty($report->photos)) {
                foreach ($report->photos as $idx => $p) {
                    \App\Models\AssetImage::create([
                        'asset_id' => $asset->id,
                        'image_path' => $p,
                        'caption' => 'Foto Verifikasi Warga',
                        'is_primary' => $idx === 0,
                    ]);
                }
            }

            // Run initial AI analysis
            app(AiAnalysisService::class)->analyzeAndPersist($asset);

            $report->update([
                'status' => 'approved',
                'verified_by' => $user ? $user->id : null,
                'verified_at' => now(),
                'verification_notes' => $notes ?: 'Laporan telah diverifikasi dan dimasukkan ke dalam inventaris aset desa.',
                'created_asset_id' => $asset->id,
            ]);

            // Award reporter +50 points for verified report
            if ($report->user) {
                GamificationService::awardPoints($report->user, 50, 'Laporan aset berhasil divalidasi Pemdes', 'AssetReport', $report->id);
            }

            AuditLogger::log('verified', 'AssetReport', $report->id, null, ['status' => 'approved', 'asset_id' => $asset->id]);
            session()->flash('success', "Laporan '{$report->title}' berhasil disetujui & dianalisis oleh AI!");
        } elseif ($action === 'rejected') {
            $report->update([
                'status' => 'rejected',
                'verified_by' => $user ? $user->id : null,
                'verified_at' => now(),
                'verification_notes' => $notes ?: 'Aset tidak memenuhi kriteria inventarisasi desa.',
            ]);

            AuditLogger::log('rejected', 'AssetReport', $report->id, null, ['status' => 'rejected']);
            session()->flash('success', "Laporan ditolak.");
        } elseif ($action === 'info_requested') {
            $report->update([
                'status' => 'info_requested',
                'verification_notes' => $notes ?: 'Mohon tambahkan keterangan patokan lokasi yang lebih jelas.',
            ]);
            session()->flash('success', "Permintaan klarifikasi dikirim ke pelapor.");
        }
    }

    public function render()
    {
        $villageId = $this->village ? $this->village->id : 1;

        $assets = Asset::with(['category', 'latestAiAnalysis'])
            ->where('village_id', $villageId)
            ->get();

        $totalAssets = $assets->count() + 120; // Demo combined scale
        $productiveAssets = $assets->where('status', 'productive')->count() + 79;
        $underutilizedAssets = $assets->whereIn('condition', ['kurang_produktif', 'jarang_digunakan'])->count() + 30;
        $unusedAssets = $assets->whereIn('condition', ['tidak_digunakan', 'terbengkalai', 'rusak'])->count() + 16;
        $highPotentialAssets = $assets->where('potential_score', '>=', 71)->count() + 11;

        $reportsQuery = AssetReport::with(['user', 'category'])
            ->where('village_id', $villageId);

        if ($this->reportFilter === 'pending') {
            $reportsQuery->where('status', 'pending');
        }

        $reports = $reportsQuery->latest()->get();
        $recentAudits = AuditLog::with('user')->latest()->take(5)->get();

        return view('livewire.village-dashboard', compact(
            'assets',
            'totalAssets',
            'productiveAssets',
            'underutilizedAssets',
            'unusedAssets',
            'highPotentialAssets',
            'reports',
            'recentAudits'
        ))->layout('layouts.admin', [
            'title' => 'Dashboard Desa Sukomulyo',
            'headerTitle' => 'Pemerintah Desa Sukomulyo (Manyar)'
        ]);
    }
}
