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

    // Asset Management State (Scoped to current village)
    public bool $showAssetModal = false;
    public ?int $editingAssetId = null;
    public string $assetName = '';
    public ?int $assetCategoryId = null;
    public string $assetCondition = 'tidak_digunakan';
    public int $assetArea = 350;
    public string $assetTargetUse = 'Sentra UMKM';
    public string $assetAddress = '';
    public string $assetStatus = 'verified';
    public string $assetDescription = '';
    public ?float $assetLatitude = null;
    public ?float $assetLongitude = null;

    public function mount()
    {
        $user = Auth::user();
        if ($user && $user->village_id) {
            $this->village = Village::find($user->village_id);
        } else {
            // Default to Sukomulyo (Flagship village)
            $this->village = Village::where('code', '3525010001')->first() ?? Village::first();
        }

        $firstCat = AssetCategory::first();
        if ($firstCat) {
            $this->assetCategoryId = $firstCat->id;
        }
    }

    public function openCreateAssetModal()
    {
        $this->reset(['editingAssetId', 'assetName', 'assetDescription', 'assetAddress', 'assetLatitude', 'assetLongitude']);
        $this->assetCondition = 'tidak_digunakan';
        $this->assetTargetUse = 'Sentra UMKM';
        $this->assetArea = 350;
        $this->assetStatus = 'verified';
        $firstCat = AssetCategory::first();
        $this->assetCategoryId = $firstCat ? $firstCat->id : 1;
        $this->assetLatitude = (float) ($this->village->latitude ?? -7.1350);
        $this->assetLongitude = (float) ($this->village->longitude ?? 112.6020);
        $this->assetAddress = "Desa " . ($this->village?->name ?? 'Sukomulyo') . ", Manyar, Gresik";
        $this->showAssetModal = true;
    }

    public function openCreateAssetModalWithCoords(?float $lat = null, ?float $lng = null): void
    {
        $this->openCreateAssetModal();
        if ($lat && $lng) {
            $this->assetLatitude = round($lat, 6);
            $this->assetLongitude = round($lng, 6);
            $this->assetAddress = "Desa " . ($this->village?->name ?? 'Sukomulyo') . " (Titik Peta: {$this->assetLatitude}, {$this->assetLongitude})";
        }
    }

    public function setLocation(float $lat, float $lng, ?string $address = null): void
    {
        $this->assetLatitude = round($lat, 6);
        $this->assetLongitude = round($lng, 6);
        if ($address) {
            $this->assetAddress = $address;
        } elseif (empty($this->assetAddress) || str_contains($this->assetAddress, 'Titik Peta')) {
            $this->assetAddress = "Desa " . ($this->village?->name ?? 'Sukomulyo') . " (Titik Peta: {$this->assetLatitude}, {$this->assetLongitude})";
        }
    }

    public function editAsset(int $assetId)
    {
        $villageId = $this->village ? $this->village->id : 1;
        $asset = Asset::where('village_id', $villageId)->findOrFail($assetId);

        $this->editingAssetId = $asset->id;
        $this->assetName = $asset->name;
        $this->assetCategoryId = $asset->category_id;
        $this->assetCondition = $asset->condition;
        $this->assetArea = (int)$asset->area;
        $this->assetTargetUse = $asset->target_activation_use ?? 'Sentra UMKM';
        $this->assetAddress = $asset->address ?? '';
        $this->assetStatus = $asset->status;
        $this->assetDescription = $asset->description ?? '';
        $this->assetLatitude  = $asset->latitude  ? (float) $asset->latitude  : null;
        $this->assetLongitude = $asset->longitude ? (float) $asset->longitude : null;
        $this->showAssetModal = true;
    }

    public function saveAsset()
    {
        $user = Auth::user();
        if ($user && $user->isDistrictAdmin()) {
            session()->flash('error', 'Akses Ditolak: Akun Pemerintah Kecamatan berstatus Pengawas (Hanya Pantau/Lihat).');
            $this->showAssetModal = false;
            return;
        }

        $this->validate([
            'assetName' => 'required|string|min:3|max:255',
            'assetCategoryId' => 'required|exists:asset_categories,id',
            'assetCondition' => 'required|string',
            'assetArea' => 'required|numeric|min:1',
            'assetTargetUse' => 'required|string',
        ]);

        $villageId = $this->village ? $this->village->id : 1;

        if ($this->editingAssetId) {
            // Scoped update
            $asset = Asset::where('village_id', $villageId)->findOrFail($this->editingAssetId);
            $asset->update([
                'name' => $this->assetName,
                'category_id' => $this->assetCategoryId,
                'condition' => $this->assetCondition,
                'area' => $this->assetArea,
                'target_activation_use' => $this->assetTargetUse,
                'address' => $this->assetAddress,
                'status' => $this->assetStatus,
                'description' => $this->assetDescription,
            ]);

            app(AiAnalysisService::class)->analyzeAndPersist($asset);
            AuditLogger::log('updated', 'Asset', $asset->id, null, ['name' => $asset->name, 'village' => $this->village->name]);
            session()->flash('success', "Aset '{$asset->name}' berhasil diperbarui oleh Pemdes!");
        } else {
            // Create scoped asset
            $slug = Str::slug($this->assetName) . '-' . rand(100, 999);
            $asset = Asset::create([
                'village_id' => $villageId,
                'category_id' => $this->assetCategoryId,
                'created_by' => $user ? $user->id : 1,
                'name' => $this->assetName,
                'slug' => $slug,
                'description' => $this->assetDescription ?: 'Aset inventarisasi resmi Pemerintah Desa.',
                'condition' => $this->assetCondition,
                'status' => $this->assetStatus,
                'ownership_type' => 'Pemerintah Desa',
                'area' => $this->assetArea,
                'latitude'  => $this->assetLatitude  ?? ($this->village->latitude  ?? -7.1350),
                'longitude' => $this->assetLongitude ?? ($this->village->longitude ?? 112.6020),
                'address' => $this->assetAddress ?: "Desa {$this->village->name}, Gresik",
                'target_activation_use' => $this->assetTargetUse,
                'verified_at' => now(),
                'verified_by' => $user ? $user->id : null,
            ]);

            \App\Models\AssetImage::create([
                'asset_id' => $asset->id,
                'image_path' => 'https://images.unsplash.com/photo-1513694203232-719a280e022f?auto=format&fit=crop&w=800&q=80',
                'caption' => 'Foto Aset Desa',
                'is_primary' => true,
            ]);

            app(AiAnalysisService::class)->analyzeAndPersist($asset);
            AuditLogger::log('created', 'Asset', $asset->id, null, ['name' => $asset->name, 'village' => $this->village->name]);
            session()->flash('success', "Aset baru '{$asset->name}' berhasil ditambahkan ke inventaris Desa!");
        }

        $this->showAssetModal = false;
        $this->dispatch('asset-saved');
    }

    public function deleteAsset(int $assetId)
    {
        $user = Auth::user();
        if ($user && $user->isDistrictAdmin()) {
            session()->flash('error', 'Akses Ditolak: Akun Pemerintah Kecamatan berstatus Pengawas (Hanya Pantau/Lihat).');
            return;
        }

        $villageId = $this->village ? $this->village->id : 1;
        $asset = Asset::where('village_id', $villageId)->findOrFail($assetId);
        $name = $asset->name;
        $asset->delete();

        AuditLogger::log('deleted', 'Asset', $assetId, null, ['name' => $name, 'village' => $this->village->name]);
        session()->flash('success', "Aset '{$name}' telah dihapus dari inventaris.");
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

        $totalAssets = $assets->count();
        $productiveAssets = $assets->where('status', 'productive')->count();
        $underutilizedAssets = $assets->whereIn('condition', ['kurang_produktif', 'jarang_digunakan'])->count();
        $unusedAssets = $assets->whereIn('condition', ['tidak_digunakan', 'terbengkalai', 'rusak'])->count();
        $highPotentialAssets = $assets->where('potential_score', '>=', 71)->count();
        $disposedAssets = $assets->where('status', 'disposed')->count();

        $totalValuation = $assets->sum('estimated_economic_value') ?: ($assets->sum('area') * 250000);
        $totalArea = $assets->sum('area');

        $reportsQuery = AssetReport::with(['user', 'category'])
            ->where('village_id', $villageId);

        $pendingReportsCount = AssetReport::where('village_id', $villageId)->where('status', 'pending')->count();
        $totalReportsCount = AssetReport::where('village_id', $villageId)->count();

        if ($this->reportFilter === 'pending') {
            $reportsQuery->where('status', 'pending');
        }

        $reports = $reportsQuery->latest()->get();
        $recentAudits = AuditLog::with('user')->latest()->take(5)->get();
        $categories = AssetCategory::all();

        $compliancePercentage = $totalAssets > 0 ? round(($assets->whereNotNull('verified_at')->count() / $totalAssets) * 100) : 100;
        $operationalPercentage = $totalAssets > 0 ? round(($productiveAssets / $totalAssets) * 100) : 0;

        $villageName = $this->village ? $this->village->name : 'Sukomulyo';
        $districtName = $this->village && $this->village->district ? $this->village->district->name : 'Manyar';

        return view('livewire.village-dashboard', compact(
            'assets',
            'totalAssets',
            'productiveAssets',
            'underutilizedAssets',
            'unusedAssets',
            'highPotentialAssets',
            'disposedAssets',
            'totalValuation',
            'totalArea',
            'pendingReportsCount',
            'totalReportsCount',
            'compliancePercentage',
            'operationalPercentage',
            'reports',
            'recentAudits',
            'categories'
        ))->layout('layouts.admin', [
            'title' => "Dashboard Desa {$villageName}",
            'headerTitle' => "Pemerintah Desa {$villageName} ({$districtName})"
        ]);
    }
}
