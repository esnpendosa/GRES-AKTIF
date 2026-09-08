<?php

namespace App\Livewire;

use App\Models\Asset;
use App\Models\AssetCategory;
use App\Models\District;
use App\Models\Village;
use App\Services\Gis\GisService;
use Livewire\Component;

class OpportunityMap extends Component
{
    public string $selectedDistrict = '';
    public string $selectedCategory = '';
    public string $selectedCondition = '';
    public string $selectedSector = ''; // UMKM, Kuliner, Pertanian, Wisata, Olahraga, Pendidikan
    public string $selectedScore = '';
    public int $radiusKm = 25;

    public ?float $userLat = null;
    public ?float $userLng = null;
    public string $locationStatus = '';

    public ?int $selectedAssetId = null;

    // Idea Submission Form on Map
    public bool $showGlobalIdeaModal = false;
    public ?int $ideaAssetId = null;
    public string $ideaTitle = '';
    public string $ideaCategory = 'UMKM';
    public string $ideaDescription = '';
    public bool $ideaSubmitted = false;

    public function mount()
    {
        $first = Asset::first();
        if ($first) {
            $this->ideaAssetId = $first->id;
            $this->selectedAssetId = $first->id;
        }

        if (request()->query('openIdea')) {
            $this->showGlobalIdeaModal = true;
        }
    }

    public function openIdeaModal(?int $assetId = null)
    {
        if ($assetId) {
            $this->ideaAssetId = $assetId;
        } elseif (!$this->ideaAssetId) {
            $first = Asset::first();
            $this->ideaAssetId = $first ? $first->id : null;
        }
        $this->showGlobalIdeaModal = true;
        $this->ideaSubmitted = false;
    }

    public function submitIdea()
    {
        $this->validate([
            'ideaAssetId' => 'required|exists:assets,id',
            'ideaTitle' => 'required|string|min:5|max:255',
            'ideaCategory' => 'required|string',
        ]);

        $user = auth()->user();
        if (!$user) {
            $user = \App\Models\User::where('email', 'masyarakat@gresaktif.id')->first();
            if ($user) {
                auth()->login($user);
            }
        }

        $idea = \App\Models\AssetIdea::create([
            'asset_id' => $this->ideaAssetId,
            'user_id' => $user ? $user->id : 1,
            'title' => $this->ideaTitle,
            'category' => $this->ideaCategory,
            'description' => $this->ideaDescription,
            'votes_count' => 1,
        ]);

        if ($user) {
            \App\Models\IdeaVote::create([
                'idea_id' => $idea->id,
                'user_id' => $user->id,
                'vote_type' => 'upvote',
            ]);

            \App\Services\GamificationService::awardPoints($user, 10, 'Menyumbangkan ide gagasan pemanfaatan aset desa', 'AssetIdea', $idea->id);
        }

        $asset = Asset::find($this->ideaAssetId);
        if ($asset) {
            app(\App\Services\Ai\AiAnalysisService::class)->updateConsensus($asset);
        }

        $this->ideaTitle = '';
        $this->ideaDescription = '';
        $this->ideaSubmitted = true;
    }

    public function selectAsset(int $id)
    {
        $this->selectedAssetId = $id;
        $this->ideaAssetId = $id;
    }

    public function setLocation($lat, $lng)
    {
        $this->userLat = (float)$lat;
        $this->userLng = (float)$lng;
        $this->locationStatus = 'Lokasi aktif: ' . round($lat, 4) . ', ' . round($lng, 4);
        $this->dispatch('assets-updated', assets: $this->getAssetsPayload());
    }

    public function updated($propertyName)
    {
        $this->dispatch('assets-updated', assets: $this->getAssetsPayload());
    }

    public function setSector(string $sector)
    {
        $this->selectedSector = $sector;
        $this->dispatch('assets-updated', assets: $this->getAssetsPayload());
    }

    public function getAssetsPayload(): array
    {
        $query = Asset::with(['village.district', 'category', 'images', 'latestAiAnalysis']);

        if (!empty($this->selectedDistrict)) {
            $query->whereHas('village.district', function ($q) {
                $q->where('name', $this->selectedDistrict);
            });
        }

        if (!empty($this->selectedCategory)) {
            $query->where('category_id', $this->selectedCategory);
        }

        if (!empty($this->selectedCondition)) {
            $query->where('condition', $this->selectedCondition);
        }

        if (!empty($this->selectedScore)) {
            if ($this->selectedScore === 'high') {
                $query->where('potential_score', '>=', 71);
            } elseif ($this->selectedScore === 'medium') {
                $query->whereBetween('potential_score', [41, 70]);
            }
        }

        if (!empty($this->selectedSector)) {
            $s = $this->selectedSector;
            $query->where(function ($q) use ($s) {
                $q->where('target_activation_use', 'like', "%{$s}%")
                  ->orWhere('name', 'like', "%{$s}%")
                  ->orWhere('description', 'like', "%{$s}%")
                  ->orWhereHas('category', function ($qc) use ($s) {
                      $qc->where('name', 'like', "%{$s}%");
                  });
            });
        }

        return $query->get()->map(function ($a) {
            return [
                'id' => $a->id,
                'name' => $a->name,
                'slug' => $a->slug,
                'lat' => (float)$a->latitude,
                'lng' => (float)$a->longitude,
                'village' => $a->village ? $a->village->name : 'Gresik',
                'district' => $a->village && $a->village->district ? $a->village->district->name : 'Manyar',
                'condition' => $a->condition_label,
                'status' => $a->status_label,
                'score' => $a->potential_score,
                'category' => $a->category ? $a->category->name : 'Bangunan',
                'target_use' => $a->target_activation_use ?? 'Sentra UMKM & Kuliner Desa',
                'color' => $a->map_marker_color,
                'image' => $a->primary_image_url,
                'area' => $a->area,
                'location_score' => $a->location_score,
                'accessibility_score' => $a->accessibility_score,
                'economic_score' => $a->economic_score,
                'community_demand_score' => $a->community_demand_score,
                'url' => route('assets.show', $a->slug ?? $a->id)
            ];
        })->toArray();
    }

    public function render()
    {
        $assetsPayload = $this->getAssetsPayload();
        $assets = collect($assetsPayload);

        $selectedAsset = $this->selectedAssetId ? Asset::with(['village.district', 'category', 'images', 'latestAiAnalysis'])->find($this->selectedAssetId) : null;

        $categories = AssetCategory::all();
        $districts = District::all();
        $selectedSector = $this->selectedSector;
        $showGlobalIdeaModal = $this->showGlobalIdeaModal;

        return view('livewire.opportunity-map', compact(
            'assets',
            'categories',
            'districts',
            'selectedAsset',
            'assetsPayload',
            'selectedSector',
            'showGlobalIdeaModal'
        ))->layout('layouts.app', ['title' => 'Peta Peluang AI & GIS Aset Gresik']);
    }
}
