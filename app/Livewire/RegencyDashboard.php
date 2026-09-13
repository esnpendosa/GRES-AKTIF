<?php

namespace App\Livewire;

use App\Models\Asset;
use App\Models\AssetCategory;
use App\Models\District;
use Livewire\Component;

class RegencyDashboard extends Component
{
    public string $selectedDistrict = '';
    public string $selectedSector = '';

    // Full Asset CRUD Management for Regency Admin
    public bool $showAssetModal = false;
    public ?int $editingAssetId = null;
    public ?int $assetVillageId = null;
    public string $assetName = '';
    public ?int $assetCategoryId = null;
    public string $assetCondition = 'tidak_digunakan';
    public int $assetArea = 500;
    public string $assetTargetUse = 'Sentra UMKM';
    public string $assetAddress = '';
    public string $assetStatus = 'verified';
    public string $assetDescription = '';

    public function mount()
    {
        $firstVillage = \App\Models\Village::first();
        if ($firstVillage) {
            $this->assetVillageId = $firstVillage->id;
        }
        $firstCat = AssetCategory::first();
        if ($firstCat) {
            $this->assetCategoryId = $firstCat->id;
        }
    }

    public function openCreateAssetModal()
    {
        $this->reset(['editingAssetId', 'assetName', 'assetDescription', 'assetAddress']);
        $this->assetCondition = 'tidak_digunakan';
        $this->assetTargetUse = 'Sentra UMKM';
        $this->assetArea = 500;
        $this->assetStatus = 'verified';
        $firstVillage = \App\Models\Village::first();
        $this->assetVillageId = $firstVillage ? $firstVillage->id : 1;
        $firstCat = AssetCategory::first();
        $this->assetCategoryId = $firstCat ? $firstCat->id : 1;
        $this->showAssetModal = true;
    }

    public function editAsset(int $assetId)
    {
        $asset = Asset::findOrFail($assetId);
        $this->editingAssetId = $asset->id;
        $this->assetVillageId = $asset->village_id;
        $this->assetName = $asset->name;
        $this->assetCategoryId = $asset->category_id;
        $this->assetCondition = $asset->condition;
        $this->assetArea = (int)$asset->area;
        $this->assetTargetUse = $asset->target_activation_use ?? 'Sentra UMKM';
        $this->assetAddress = $asset->address ?? '';
        $this->assetStatus = $asset->status;
        $this->assetDescription = $asset->description ?? '';
        $this->showAssetModal = true;
    }

    public function saveAsset()
    {
        $this->validate([
            'assetName' => 'required|string|min:3|max:255',
            'assetVillageId' => 'required|exists:villages,id',
            'assetCategoryId' => 'required|exists:asset_categories,id',
            'assetCondition' => 'required|string',
            'assetArea' => 'required|numeric|min:1',
            'assetTargetUse' => 'required|string',
        ]);

        $user = \Illuminate\Support\Facades\Auth::user();
        $village = \App\Models\Village::find($this->assetVillageId);

        if ($this->editingAssetId) {
            $asset = Asset::findOrFail($this->editingAssetId);
            $asset->update([
                'village_id' => $this->assetVillageId,
                'name' => $this->assetName,
                'category_id' => $this->assetCategoryId,
                'condition' => $this->assetCondition,
                'area' => $this->assetArea,
                'target_activation_use' => $this->assetTargetUse,
                'address' => $this->assetAddress,
                'status' => $this->assetStatus,
                'description' => $this->assetDescription,
            ]);

            app(\App\Services\Ai\AiAnalysisService::class)->analyzeAndPersist($asset);
            \App\Services\AuditLogger::log('updated', 'Asset', $asset->id, null, ['name' => $asset->name, 'level' => 'Kabupaten']);
            session()->flash('success', "Aset '{$asset->name}' berhasil diperbarui oleh Bappeda!");
        } else {
            $slug = \Illuminate\Support\Str::slug($this->assetName) . '-' . rand(100, 999);
            $asset = Asset::create([
                'village_id' => $this->assetVillageId,
                'category_id' => $this->assetCategoryId,
                'created_by' => $user ? $user->id : 1,
                'name' => $this->assetName,
                'slug' => $slug,
                'description' => $this->assetDescription ?: 'Aset inventarisasi resmi Pemerintah Kabupaten Gresik.',
                'condition' => $this->assetCondition,
                'status' => $this->assetStatus,
                'ownership_type' => 'Pemerintah Kabupaten',
                'area' => $this->assetArea,
                'latitude' => $village->latitude ?? -7.1566,
                'longitude' => $village->longitude ?? 112.6555,
                'address' => $this->assetAddress ?: ($village ? "Desa {$village->name}, Gresik" : "Kabupaten Gresik"),
                'target_activation_use' => $this->assetTargetUse,
                'verified_at' => now(),
                'verified_by' => $user ? $user->id : null,
            ]);

            \App\Models\AssetImage::create([
                'asset_id' => $asset->id,
                'image_path' => 'https://images.unsplash.com/photo-1513694203232-719a280e022f?auto=format&fit=crop&w=800&q=80',
                'caption' => 'Foto Aset Daerah',
                'is_primary' => true,
            ]);

            app(\App\Services\Ai\AiAnalysisService::class)->analyzeAndPersist($asset);
            \App\Services\AuditLogger::log('created', 'Asset', $asset->id, null, ['name' => $asset->name, 'level' => 'Kabupaten']);
            session()->flash('success', "Aset baru '{$asset->name}' berhasil ditambahkan ke inventaris Kabupaten!");
        }

        $this->showAssetModal = false;
    }

    public function deleteAsset(int $assetId)
    {
        $asset = Asset::findOrFail($assetId);
        $name = $asset->name;
        $asset->delete();

        \App\Services\AuditLogger::log('deleted', 'Asset', $assetId, null, ['name' => $name, 'level' => 'Kabupaten']);
        session()->flash('success', "Aset '{$name}' telah dihapus dari inventaris.");
    }

    public function render()
    {
        $totalAssets = 12842;
        $productiveAssets = 8921;
        $underutilizedAssets = 2713;
        $unusedAssets = 1208;
        $highOpportunityAssets = 326;

        $topOpportunities = Asset::with(['village.district', 'category', 'images'])
            ->where('potential_score', '>=', 80)
            ->orderByDesc('potential_score')
            ->take(5)
            ->get();

        $districts = District::withCount('assets')->get();
        $categories = AssetCategory::withCount('assets')->get();
        $allVillages = \App\Models\Village::with('district')->orderBy('name')->get();
        $allAssets = Asset::with(['village.district', 'category'])->latest()->get();

        return view('livewire.regency-dashboard', compact(
            'totalAssets',
            'productiveAssets',
            'underutilizedAssets',
            'unusedAssets',
            'highOpportunityAssets',
            'topOpportunities',
            'districts',
            'categories',
            'allVillages',
            'allAssets'
        ))->layout('layouts.admin', [
            'title' => 'Gresik Asset Intelligence',
            'headerTitle' => 'Gresik Asset Intelligence (Bappeda / BPKAD)'
        ]);
    }
}
