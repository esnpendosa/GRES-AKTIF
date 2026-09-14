<?php

namespace App\Livewire;

use App\Models\Asset;
use App\Models\AssetCategory;
use App\Models\AssetImage;
use App\Models\AssetReport;
use App\Models\District;
use App\Models\Village;
use App\Services\Ai\AiAnalysisService;
use App\Services\AuditLogger;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Verifikasi & Validasi Laporan Aset - Bappeda Kab. Gresik')]
class AssetVerification extends Component
{
    use WithPagination;
    use WithFileUploads;

    // Filters
    public string $statusFilter = 'pending'; // 'pending', 'approved', 'rejected', 'all'
    public string $districtFilter = '';
    public string $villageFilter = '';
    public string $search = '';

    // Modals
    public bool $showDetailModal = false;
    public bool $showApproveModal = false;
    public bool $showRejectModal = false;
    public bool $showEditModal = false;
    public ?int $selectedReportId = null;

    // Edit & Photo Management Form
    public ?int $editReportId = null;
    public string $editTitle = '';
    public ?int $editCategoryId = null;
    public ?int $editDistrictId = null;
    public ?int $editVillageId = null;
    public string $editCondition = 'kurang_produktif';
    public string $editSuggestedUse = '';
    public string $editAddress = '';
    public ?float $editLatitude = null;
    public ?float $editLongitude = null;
    public int $editArea = 350;
    public string $editDescription = '';
    public string $editVerificationNotes = '';
    public array $existingPhotos = [];
    public $newPhotos = [];

    // Approval Form
    public string $approved_title = '';
    public ?int $approved_category_id = null;
    public int $approved_area = 350;
    public string $approved_target_use = '';
    public string $approved_notes = '';

    // Rejection Form
    public string $rejection_reason = '';

    // Toast
    public ?string $successMessage = null;

    public function updatedDistrictFilter()
    {
        $this->villageFilter = '';
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function openApproveModal(int $reportId)
    {
        $report = AssetReport::with('category')->findOrFail($reportId);
        $this->selectedReportId = $report->id;
        $this->approved_title = $report->title;
        $this->approved_category_id = $report->category_id ?? AssetCategory::first()->id;
        $this->approved_area = 350;
        $this->approved_target_use = $report->suggested_use ?? 'Sentra UMKM';
        $this->approved_notes = 'Laporan telah diverifikasi dan divalidasi keabsahannya oleh Bappedalitbang Kab. Gresik.';
        $this->showApproveModal = true;
    }

    public function approveReport()
    {
        $this->validate([
            'approved_title' => 'required|string|min:4|max:255',
            'approved_category_id' => 'required|exists:asset_categories,id',
            'approved_area' => 'required|numeric|min:1',
            'approved_target_use' => 'required|string',
        ], [
            'approved_title.required' => 'Nama/judul aset resmi wajib diisi.',
            'approved_category_id.required' => 'Pilih kategori aset.',
            'approved_area.required' => 'Estimasi luas aset wajib ditentukan.',
        ]);

        $report = AssetReport::with('village')->findOrFail($this->selectedReportId);
        $user = Auth::user();

        // Convert Report to Official Asset
        $slug = Str::slug($this->approved_title) . '-' . rand(100, 999);
        $asset = Asset::create([
            'village_id' => $report->village_id ?? 1,
            'category_id' => $this->approved_category_id,
            'created_by' => $report->user_id ?? ($user ? $user->id : 1),
            'name' => $this->approved_title,
            'slug' => $slug,
            'description' => $report->description ?: "Aset diverifikasi dan disahkan oleh Bappeda Kabupaten Gresik dari laporan masyarakat.",
            'condition' => $report->condition ?: 'kurang_produktif',
            'status' => 'verified',
            'ownership_type' => 'Pemerintah Desa',
            'area' => $this->approved_area,
            'latitude' => $report->latitude,
            'longitude' => $report->longitude,
            'address' => $report->address ?: "Desa " . ($report->village?->name ?? 'Gresik') . ", Kab. Gresik",
            'target_activation_use' => $this->approved_target_use,
            'verified_at' => now(),
            'verified_by' => $user ? $user->id : null,
        ]);

        // Save Photo to AssetImage
        $photos = $report->photos;
        $primaryPhoto = is_array($photos) && count($photos) > 0 
            ? $photos[0] 
            : 'https://images.unsplash.com/photo-1513694203232-719a280e022f?auto=format&fit=crop&w=800&q=80';

        AssetImage::create([
            'asset_id' => $asset->id,
            'image_path' => $primaryPhoto,
            'caption' => 'Foto Aset Hasil Laporan Warga',
            'is_primary' => true,
        ]);

        // Run AI Analysis
        try {
            app(AiAnalysisService::class)->analyzeAndPersist($asset);
        } catch (\Throwable $e) {}

        // Update Report
        $report->update([
            'status' => 'approved',
            'verified_at' => now(),
            'verified_by' => $user ? $user->id : null,
            'created_asset_id' => $asset->id,
            'verification_notes' => $this->approved_notes,
        ]);

        AuditLogger::log('verify_approve', 'AssetReport', $report->id, null, ['asset_id' => $asset->id, 'title' => $asset->name]);

        $this->successMessage = "Laporan '{$report->title}' berhasil disetujui oleh Bappeda dan resmi diterbitkan menjadi Aset Desa!";
        $this->showApproveModal = false;
        $this->selectedReportId = null;
    }

    public function quickApprove(int $reportId)
    {
        $report = AssetReport::with('village')->findOrFail($reportId);
        $user = Auth::user();

        $slug = Str::slug($report->title) . '-' . rand(100, 999);
        $asset = Asset::create([
            'village_id' => $report->village_id ?? 1,
            'category_id' => $report->category_id ?? AssetCategory::first()->id,
            'created_by' => $report->user_id ?? ($user ? $user->id : 1),
            'name' => $report->title,
            'slug' => $slug,
            'description' => $report->description ?: "Aset diverifikasi langsung oleh Bappeda Kabupaten Gresik.",
            'condition' => $report->condition ?: 'kurang_produktif',
            'status' => 'verified',
            'ownership_type' => 'Pemerintah Desa',
            'area' => 350,
            'latitude' => $report->latitude,
            'longitude' => $report->longitude,
            'address' => $report->address ?: "Desa " . ($report->village?->name ?? 'Gresik') . ", Kab. Gresik",
            'target_activation_use' => $report->suggested_use ?? 'Sentra UMKM',
            'verified_at' => now(),
            'verified_by' => $user ? $user->id : null,
        ]);

        $photos = $report->photos;
        $primaryPhoto = is_array($photos) && count($photos) > 0 
            ? $photos[0] 
            : 'https://images.unsplash.com/photo-1513694203232-719a280e022f?auto=format&fit=crop&w=800&q=80';

        AssetImage::create([
            'asset_id' => $asset->id,
            'image_path' => $primaryPhoto,
            'caption' => 'Foto Aset Terverifikasi Bappeda',
            'is_primary' => true,
        ]);

        try {
            app(AiAnalysisService::class)->analyzeAndPersist($asset);
        } catch (\Throwable $e) {}

        $report->update([
            'status' => 'approved',
            'verified_at' => now(),
            'verified_by' => $user ? $user->id : null,
            'created_asset_id' => $asset->id,
            'verification_notes' => 'Disetujui langsung oleh Bappeda Kab. Gresik.',
        ]);

        AuditLogger::log('verify_quick_approve', 'AssetReport', $report->id, null, ['asset_id' => $asset->id]);

        $this->successMessage = "Laporan '{$report->title}' langsung disetujui Bappeda dan telah masuk ke Peta GIS & SIPADES!";
    }

    public function openRejectModal(int $reportId)
    {
        $this->selectedReportId = $reportId;
        $this->rejection_reason = '';
        $this->showRejectModal = true;
    }

    public function rejectReport()
    {
        $this->validate([
            'rejection_reason' => 'required|string|min:5',
        ], [
            'rejection_reason.required' => 'Alasan penolakan laporan wajib diisi.',
        ]);

        $report = AssetReport::findOrFail($this->selectedReportId);
        $report->update([
            'status' => 'rejected',
            'verification_notes' => $this->rejection_reason,
            'verified_at' => now(),
            'verified_by' => Auth::id(),
        ]);

        AuditLogger::log('verify_reject', 'AssetReport', $report->id, null, ['reason' => $this->rejection_reason]);

        $this->successMessage = "Laporan '{$report->title}' telah ditolak.";
        $this->showRejectModal = false;
        $this->selectedReportId = null;
    }

    public function openDetailModal(int $reportId)
    {
        $this->selectedReportId = $reportId;
        $this->showDetailModal = true;
    }

    public function dismissToast()
    {
        $this->successMessage = null;
    }

    public function openEditModal(int $reportId)
    {
        $report = AssetReport::with(['village.district', 'category', 'createdAsset'])->findOrFail($reportId);
        $this->selectedReportId = $report->id;
        $this->editReportId = $report->id;
        $this->editTitle = $report->title;
        $this->editCategoryId = $report->category_id ?? (AssetCategory::first()?->id);
        $this->editDistrictId = $report->village?->district_id ?? (District::first()?->id);
        $this->editVillageId = $report->village_id ?? (Village::where('district_id', $this->editDistrictId)->first()?->id);
        $this->editCondition = $report->condition ?: 'kurang_produktif';
        $this->editSuggestedUse = $report->suggested_use ?: 'Sentra UMKM';
        $this->editAddress = $report->address ?: '';
        $this->editLatitude = (float) $report->latitude;
        $this->editLongitude = (float) $report->longitude;
        $this->editArea = $report->createdAsset ? $report->createdAsset->area : 350;
        $this->editDescription = $report->description ?: '';
        $this->editVerificationNotes = $report->verification_notes ?: '';
        $this->existingPhotos = is_array($report->photos) ? $report->photos : [];
        $this->newPhotos = [];
        $this->showEditModal = true;
    }

    public function updatedEditDistrictId()
    {
        if ($this->editDistrictId) {
            $firstVillage = Village::where('district_id', $this->editDistrictId)->first();
            $this->editVillageId = $firstVillage ? $firstVillage->id : null;
        }
    }

    public function removeExistingPhoto(int $index)
    {
        if (isset($this->existingPhotos[$index])) {
            unset($this->existingPhotos[$index]);
            $this->existingPhotos = array_values($this->existingPhotos);
        }
    }

    public function removeNewPhoto(int $index)
    {
        if (isset($this->newPhotos[$index])) {
            unset($this->newPhotos[$index]);
            $this->newPhotos = array_values($this->newPhotos);
        }
    }

    public function saveReportEdit(bool $publish = false)
    {
        $this->validate([
            'editTitle' => 'required|string|min:3|max:255',
            'editCategoryId' => 'required|exists:asset_categories,id',
            'editVillageId' => 'required|exists:villages,id',
            'editCondition' => 'required|string',
            'editLatitude' => 'required|numeric',
            'editLongitude' => 'required|numeric',
            'editArea' => 'required|numeric|min:1',
            'newPhotos.*' => 'nullable|image|max:10240',
        ], [
            'editTitle.required' => 'Nama/judul aset wajib diisi.',
            'editCategoryId.required' => 'Pilih kategori aset.',
            'editVillageId.required' => 'Pilih desa/kelurahan.',
            'editLatitude.required' => 'Koordinat Latitude wajib diisi.',
            'editLongitude.required' => 'Koordinat Longitude wajib diisi.',
            'newPhotos.*.image' => 'Berkas harus berupa gambar.',
            'newPhotos.*.max' => 'Ukuran maksimal berkas 10MB per foto.',
        ]);

        $report = AssetReport::with(['village', 'createdAsset'])->findOrFail($this->editReportId);
        $user = Auth::user();

        // Process new uploaded photos
        $photos = $this->existingPhotos;
        if (!empty($this->newPhotos)) {
            foreach ($this->newPhotos as $photo) {
                $storedPath = $photo->store('reports', 'public');
                $photos[] = $storedPath;
            }
        }

        // Default fallback photo if none exist
        if (empty($photos)) {
            $photos = ['https://images.unsplash.com/photo-1513694203232-719a280e022f?auto=format&fit=crop&w=800&q=80'];
        }

        $report->update([
            'title' => $this->editTitle,
            'category_id' => $this->editCategoryId,
            'village_id' => $this->editVillageId,
            'condition' => $this->editCondition,
            'suggested_use' => $this->editSuggestedUse,
            'address' => $this->editAddress,
            'latitude' => $this->editLatitude,
            'longitude' => $this->editLongitude,
            'description' => $this->editDescription,
            'photos' => $photos,
            'verification_notes' => $this->editVerificationNotes,
        ]);

        if ($publish) {
            if ($report->created_asset_id && $report->createdAsset) {
                // Update existing linked asset
                $asset = $report->createdAsset;
                $asset->update([
                    'name' => $this->editTitle,
                    'category_id' => $this->editCategoryId,
                    'village_id' => $this->editVillageId,
                    'condition' => $this->editCondition,
                    'address' => $this->editAddress,
                    'latitude' => $this->editLatitude,
                    'longitude' => $this->editLongitude,
                    'area' => $this->editArea,
                    'description' => $this->editDescription,
                    'target_activation_use' => $this->editSuggestedUse,
                    'status' => 'verified',
                    'verified_at' => now(),
                    'verified_by' => $user ? $user->id : null,
                ]);

                // Sync photos to AssetImage
                AssetImage::where('asset_id', $asset->id)->delete();
                foreach ($photos as $idx => $p) {
                    AssetImage::create([
                        'asset_id' => $asset->id,
                        'image_path' => $p,
                        'caption' => 'Foto Aset Dokumentasi Bappeda',
                        'is_primary' => $idx === 0,
                    ]);
                }
            } else {
                // Create new official Asset
                $slug = Str::slug($this->editTitle) . '-' . rand(100, 999);
                $asset = Asset::create([
                    'village_id' => $this->editVillageId,
                    'category_id' => $this->editCategoryId,
                    'created_by' => $report->user_id ?? ($user ? $user->id : 1),
                    'name' => $this->editTitle,
                    'slug' => $slug,
                    'description' => $this->editDescription ?: "Aset diverifikasi dan disahkan oleh Bappeda Kabupaten Gresik dari laporan masyarakat.",
                    'condition' => $this->editCondition,
                    'status' => 'verified',
                    'ownership_type' => 'Pemerintah Desa',
                    'area' => $this->editArea,
                    'latitude' => $this->editLatitude,
                    'longitude' => $this->editLongitude,
                    'address' => $this->editAddress ?: ("Desa " . ($report->village?->name ?? 'Gresik') . ", Kab. Gresik"),
                    'target_activation_use' => $this->editSuggestedUse ?: 'Sentra UMKM',
                    'verified_at' => now(),
                    'verified_by' => $user ? $user->id : null,
                ]);

                foreach ($photos as $idx => $p) {
                    AssetImage::create([
                        'asset_id' => $asset->id,
                        'image_path' => $p,
                        'caption' => 'Foto Aset Dokumentasi Bappeda',
                        'is_primary' => $idx === 0,
                    ]);
                }

                try {
                    app(AiAnalysisService::class)->analyzeAndPersist($asset);
                } catch (\Throwable $e) {}

                $report->update([
                    'status' => 'approved',
                    'verified_at' => now(),
                    'verified_by' => $user ? $user->id : null,
                    'created_asset_id' => $asset->id,
                ]);
            }

            AuditLogger::log('verify_approve_and_edit', 'AssetReport', $report->id, null, ['asset_id' => $report->created_asset_id, 'title' => $this->editTitle]);
            $this->successMessage = "Laporan '{$report->title}' berhasil diperbarui dan resmi disahkan menjadi Aset Desa!";
        } else {
            // Keep asset in sync if already approved
            if ($report->status === 'approved' && $report->created_asset_id && $report->createdAsset) {
                $asset = $report->createdAsset;
                $asset->update([
                    'name' => $this->editTitle,
                    'category_id' => $this->editCategoryId,
                    'village_id' => $this->editVillageId,
                    'condition' => $this->editCondition,
                    'address' => $this->editAddress,
                    'latitude' => $this->editLatitude,
                    'longitude' => $this->editLongitude,
                    'area' => $this->editArea,
                    'description' => $this->editDescription,
                    'target_activation_use' => $this->editSuggestedUse,
                ]);

                AssetImage::where('asset_id', $asset->id)->delete();
                foreach ($photos as $idx => $p) {
                    AssetImage::create([
                        'asset_id' => $asset->id,
                        'image_path' => $p,
                        'caption' => 'Foto Aset Dokumentasi Bappeda',
                        'is_primary' => $idx === 0,
                    ]);
                }
            }

            AuditLogger::log('edit_report', 'AssetReport', $report->id, null, ['title' => $report->title]);
            $this->successMessage = "Perubahan data dan foto laporan '{$report->title}' berhasil disimpan!";
        }

        $this->showEditModal = false;
        $this->newPhotos = [];
    }

    public function render()
    {
        $query = AssetReport::with(['village.district', 'category', 'user', 'createdAsset']);

        // Filter status
        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        // Filter district
        if (!empty($this->districtFilter)) {
            $query->whereHas('village', function($q) {
                $q->where('district_id', $this->districtFilter);
            });
        }

        // Filter village
        if (!empty($this->villageFilter)) {
            $query->where('village_id', $this->villageFilter);
        }

        // Search
        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%')
                  ->orWhere('address', 'like', '%' . $this->search . '%')
                  ->orWhereHas('village', fn($qv) => $qv->where('name', 'like', '%' . $this->search . '%'))
                  ->orWhereHas('user', fn($qu) => $qu->where('name', 'like', '%' . $this->search . '%'));
            });
        }

        $reports = $query->latest()->paginate(10);

        // Counts
        $pendingCount = AssetReport::where('status', 'pending')->count();
        $approvedCount = AssetReport::where('status', 'approved')->count();
        $rejectedCount = AssetReport::where('status', 'rejected')->count();
        $totalCount = AssetReport::count();

        // Districts and Villages for filters
        $districts = District::orderBy('name')->get();
        $villages = !empty($this->districtFilter) 
            ? Village::where('district_id', $this->districtFilter)->orderBy('name')->get() 
            : Village::orderBy('name')->take(50)->get();

        $editVillages = !empty($this->editDistrictId)
            ? Village::where('district_id', $this->editDistrictId)->orderBy('name')->get()
            : Village::where('district_id', District::first()?->id)->orderBy('name')->get();

        $categories = AssetCategory::orderBy('name')->get();

        $selectedReport = $this->selectedReportId ? AssetReport::with(['village.district', 'category', 'user', 'createdAsset'])->find($this->selectedReportId) : null;
        $successMessage = $this->successMessage;

        return view('livewire.asset-verification', compact(
            'reports',
            'pendingCount',
            'approvedCount',
            'rejectedCount',
            'totalCount',
            'districts',
            'villages',
            'editVillages',
            'categories',
            'selectedReport',
            'successMessage'
        ));
    }
}