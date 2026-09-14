<?php

namespace App\Livewire;

use App\Models\Asset;
use App\Models\AssetCategory;
use App\Models\District;
use App\Services\Gis\GisService;
use Livewire\Component;
use Livewire\WithPagination;

class PublicExplore extends Component
{
    use WithPagination;

    public string $search = '';
    public string $selectedDistrict = '';
    public string $selectedCategory = '';
    public string $selectedCondition = '';
    public string $selectedScore = '';
    public string $sortBy = 'score_desc'; // score_desc, latest, popular, nearest

    public ?float $userLat = null;
    public ?float $userLng = null;
    public string $locationStatus = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'selectedDistrict' => ['except' => ''],
        'selectedCategory' => ['except' => ''],
        'selectedCondition' => ['except' => ''],
        'selectedScore' => ['except' => ''],
        'sortBy' => ['except' => 'score_desc'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSelectedDistrict()
    {
        $this->resetPage();
    }

    public function updatingSelectedCategory()
    {
        $this->resetPage();
    }

    public function updatingSelectedCondition()
    {
        $this->resetPage();
    }

    public function setLocation($lat, $lng)
    {
        $this->userLat = (float)$lat;
        $this->userLng = (float)$lng;
        $this->locationStatus = 'Lokasi terdeteksi';
        $this->sortBy = 'nearest';
        $this->resetPage();
    }

    public function render()
    {
        $query = Asset::with(['village.district', 'category', 'images']);

        if (!empty($this->search)) {
            $s = $this->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('description', 'like', "%{$s}%")
                  ->orWhere('target_activation_use', 'like', "%{$s}%")
                  ->orWhereHas('village', function ($qv) use ($s) {
                      $qv->where('name', 'like', "%{$s}%")
                         ->orWhereHas('district', function ($qd) use ($s) {
                             $qd->where('name', 'like', "%{$s}%");
                         });
                  });
            });
        }

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

        if ($this->sortBy === 'latest') {
            $query->latest();
        } elseif ($this->sortBy === 'popular') {
            $query->orderByDesc('supporters_count');
        } else {
            $query->orderByDesc('potential_score');
        }

        $assets = $query->paginate(9);

        // If user coordinates present, calculate distances
        if ($this->userLat && $this->userLng) {
            foreach ($assets as $asset) {
                $asset->distance = GisService::haversineDistance($this->userLat, $this->userLng, $asset->latitude, $asset->longitude);
            }
            if ($this->sortBy === 'nearest') {
                // sort items in collection
                $items = $assets->getCollection()->sortBy('distance')->values();
                $assets->setCollection($items);
            }
        }

        $categories = AssetCategory::withCount('assets')->get();
        $districts = District::withCount('assets')->get();

        return view('livewire.public-explore', compact('assets', 'categories', 'districts'))
            ->layout('layouts.admin', ['title' => 'Data Aset Desa & Potensi Ekonomi']);
    }
}
