<?php

namespace App\Livewire;

use App\Models\AssetCategory;
use App\Models\AssetReport;
use App\Models\District;
use App\Models\Village;
use App\Services\AuditLogger;
use App\Services\GamificationService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class ReportWizard extends Component
{
    use WithFileUploads;

    public int $currentStep = 1;

    // Step 1: Photos
    public $photos = [];
    public array $photoUrls = [];

    // Step 2: Location
    public ?float $latitude = -7.1350;
    public ?float $longitude = 112.6020;
    public string $address = 'Jl. Raya Manyar, Gresik';
    public ?int $village_id = null;
    public ?int $district_id = null;

    // Step 3: Condition
    public string $condition = 'tidak_digunakan';

    // Step 4: Category
    public ?int $category_id = null;

    // Step 5: Suggested Use
    public string $suggested_use = 'UMKM';

    // Step 6: Description & Title
    public string $title = '';
    public string $description = '';

    // AI Quick Report Assistant
    public string $aiPrompt = '';
    public bool $isAiProcessing = false;
    public ?string $aiMessage = null;

    public bool $isSubmitted = false;
    public ?AssetReport $createdReport = null;

    public function mount()
    {
        $user = Auth::user();
        if ($user && $user->village_id && $user->village) {
            $this->village_id = $user->village_id;
            $this->district_id = $user->village->district_id;
            $this->latitude = (float)($user->village->latitude ?? -7.1350);
            $this->longitude = (float)($user->village->longitude ?? 112.6020);
            $this->address = "Desa {$user->village->name}, Kec. " . ($user->village->district?->name ?? 'Manyar') . ", Gresik";
        } else {
            $firstVillage = Village::with('district')->first();
            if ($firstVillage) {
                $this->village_id = $firstVillage->id;
                $this->district_id = $firstVillage->district_id;
                $this->latitude = (float)$firstVillage->latitude;
                $this->longitude = (float)$firstVillage->longitude;
                $this->address = "Desa {$firstVillage->name}, Kec. " . ($firstVillage->district?->name ?? 'Manyar') . ", Gresik";
            }
        }

        $firstCategory = AssetCategory::first();
        if ($firstCategory) {
            $this->category_id = $firstCategory->id;
        }
    }

    public function updatedVillageId($value)
    {
        $v = Village::find($value);
        if ($v) {
            $this->district_id = $v->district_id;
            $this->latitude = (float)$v->latitude;
            $this->longitude = (float)$v->longitude;
            $this->address = "Desa {$v->name}, Kecamatan Manyar, Gresik";
        }
    }

    public function nextStep()
    {
        if ($this->currentStep === 1) {
            // Photos step (optional or require at least one photo)
        } elseif ($this->currentStep === 2) {
            $this->validate([
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
            ]);
        } elseif ($this->currentStep === 3) {
            $this->validate([
                'condition' => 'required|string',
            ]);
        } elseif ($this->currentStep === 4) {
            $this->validate([
                'category_id' => 'required|exists:asset_categories,id',
            ]);
        } elseif ($this->currentStep === 5) {
            $this->validate([
                'suggested_use' => 'required|string',
            ]);
        }

        $this->currentStep++;
    }

    public function prevStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    public function setLocation($lat, $lng, $address = null)
    {
        $this->latitude = round((float)$lat, 6);
        $this->longitude = round((float)$lng, 6);

        // Find nearest village from database with district
        $nearestVillage = Village::with('district')->get()->sortBy(function ($v) use ($lat, $lng) {
            $dLat = (float)($v->latitude ?? -7.1350) - (float)$lat;
            $dLng = (float)($v->longitude ?? 112.6020) - (float)$lng;
            return ($dLat * $dLat) + ($dLng * $dLng);
        })->first();

        if ($nearestVillage) {
            $this->village_id = $nearestVillage->id;
            $this->district_id = $nearestVillage->district_id;
            $districtName = $nearestVillage->district?->name ?? 'Manyar';
            if ($address) {
                $this->address = $address;
            } else {
                $this->address = "Desa {$nearestVillage->name}, Kec. {$districtName}, Gresik (Titik GPS: {$this->latitude}, {$this->longitude})";
            }
        } elseif ($address) {
            $this->address = $address;
        }
    }

    public function fillWithAi()
    {
        $prompt = trim($this->aiPrompt);
        if (empty($prompt)) {
            $this->addError('aiPrompt', 'Silakan ketik deskripsi aset yang ingin dilaporkan.');
            return;
        }

        $this->isAiProcessing = true;
        $this->resetErrorBag('aiPrompt');

        try {
            $openRouter = app(\App\Services\Ai\OpenRouterAiService::class);
            $parsed = $openRouter->parseAssetReportFromText($prompt);

            if (!empty($parsed)) {
                $this->title = $parsed['title'];
                $this->description = $parsed['description'];
                $this->condition = $parsed['condition'];
                $this->suggested_use = $parsed['suggested_use'];
                $this->category_id = $parsed['category_id'] ?? $this->category_id;
                $this->village_id = $parsed['village_id'] ?? $this->village_id;
                $this->district_id = $parsed['district_id'] ?? $this->district_id;
                $villageObj = $this->village_id ? Village::find($this->village_id) : null;
                $this->address = !empty($parsed['address']) ? $parsed['address'] : ($villageObj ? "Desa {$villageObj->name}, Kecamatan Manyar, Gresik" : 'Desa Sukomulyo, Kecamatan Manyar, Gresik');

                $this->aiMessage = "Data aset berhasil diekstrak oleh AI! Silakan tinjau ringkasan di bawah dan klik Kirim.";
                $this->currentStep = 6; // Jump directly to Review & Submit step
            }
        } catch (\Throwable $e) {
            $this->addError('aiPrompt', 'Gagal memproses dengan AI: ' . $e->getMessage());
        } finally {
            $this->isAiProcessing = false;
        }
    }

    public function submitReport()
    {
        $this->validate([
            'title' => 'required|string|min:5|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'condition' => 'required|string',
            'category_id' => 'required|exists:asset_categories,id',
            'suggested_use' => 'required|string',
        ]);

        $uploadedPaths = [];
        foreach ($this->photos as $photo) {
            $path = $photo->store('reports', 'public');
            $uploadedPaths[] = $path;
        }

        if (empty($uploadedPaths)) {
            // fallback dummy photo for showcase
            $uploadedPaths = ['https://images.unsplash.com/photo-1513694203232-719a280e022f?auto=format&fit=crop&w=800&q=80'];
        }

        $user = Auth::user();
        if (!$user) {
            // Auto login default community user if not logged in
            $user = \App\Models\User::where('email', 'masyarakat@gresaktif.id')->first();
            Auth::login($user);
        }

        $report = AssetReport::create([
            'user_id' => $user->id,
            'village_id' => $this->village_id,
            'category_id' => $this->category_id,
            'title' => $this->title,
            'description' => $this->description,
            'condition' => $this->condition,
            'suggested_use' => $this->suggested_use,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'address' => $this->address,
            'photos' => $uploadedPaths,
            'status' => 'pending',
        ]);


        // Audit Log
        AuditLogger::log('created', 'AssetReport', $report->id, null, $report->toArray());

        // Send email notification to admin
        \App\Services\EmailNotificationService::sendNewAssetReport($report->load(['village', 'user', 'category']), 'Form Laporan Warga');

        $this->createdReport = $report;
        $this->isSubmitted = true;

        $this->dispatch('report-submitted-success', [
            'id' => $report->id,
            'lat' => $report->latitude,
            'lng' => $report->longitude,
            'title' => $report->title,
        ]);
    }

    public function render()
    {
        $categories = AssetCategory::all();
        $districts = District::with('villages')->get();
        $villages = $this->district_id ? Village::where('district_id', $this->district_id)->get() : Village::all();

        return view('livewire.report-wizard', compact('categories', 'districts', 'villages'))
            ->layout('layouts.admin', ['title' => 'Laporkan Aset Desa']);
    }
}
