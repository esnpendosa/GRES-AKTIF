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

    public bool $isSubmitted = false;
    public ?AssetReport $createdReport = null;

    public function mount()
    {
        $firstVillage = Village::first();
        if ($firstVillage) {
            $this->village_id = $firstVillage->id;
            $this->district_id = $firstVillage->district_id;
            $this->latitude = (float)$firstVillage->latitude;
            $this->longitude = (float)$firstVillage->longitude;
        }

        $firstCategory = AssetCategory::first();
        if ($firstCategory) {
            $this->category_id = $firstCategory->id;
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
        $this->latitude = (float)$lat;
        $this->longitude = (float)$lng;
        if ($address) {
            $this->address = $address;
        }

        // Find nearest village
        $nearestVillage = Village::all()->sortBy(function ($v) use ($lat, $lng) {
            return pow($v->latitude - $lat, 2) + pow($v->longitude - $lng, 2);
        })->first();

        if ($nearestVillage) {
            $this->village_id = $nearestVillage->id;
            $this->district_id = $nearestVillage->district_id;
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

        // Award +20 gamification points
        GamificationService::awardPoints($user, 20, 'Melaporkan aset desa baru', 'AssetReport', $report->id);

        // Audit Log
        AuditLogger::log('created', 'AssetReport', $report->id, null, $report->toArray());

        $this->createdReport = $report;
        $this->isSubmitted = true;
    }

    public function render()
    {
        $categories = AssetCategory::all();
        $districts = District::with('villages')->get();
        $villages = $this->district_id ? Village::where('district_id', $this->district_id)->get() : Village::all();

        return view('livewire.report-wizard', compact('categories', 'districts', 'villages'))
            ->layout('layouts.app', ['title' => 'Laporkan Aset Desa']);
    }
}
