<?php

namespace App\Livewire;

use App\Models\Asset;
use App\Models\District;
use App\Models\Village;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class DistrictDashboard extends Component
{
    public ?District $district;
    public string $selectedCategory = '';

    public function mount()
    {
        $user = Auth::user();
        if ($user && $user->district_id) {
            $this->district = District::find($user->district_id);
        } else {
            $this->district = District::where('code', 'MANYAR')->first() ?? District::first();
        }
    }

    public function render()
    {
        $districtId = $this->district ? $this->district->id : 1;

        $villages = Village::with(['assets.category'])
            ->withCount(['assets', 'reports'])
            ->where('district_id', $districtId)
            ->get();

        $assetsQuery = Asset::with(['village', 'category'])
            ->whereHas('village', function ($q) use ($districtId) {
                $q->where('district_id', $districtId);
            });

        if (!empty($this->selectedCategory)) {
            $assetsQuery->where('category_id', $this->selectedCategory);
        }

        $assets = $assetsQuery->get();

        $totalDesa = $villages->count();
        $totalAssets = $assets->count();
        $productiveAssets = $assets->where('status', 'productive')->count();
        $unusedAssets = $assets->whereIn('condition', ['tidak_digunakan', 'terbengkalai', 'rusak'])->count();
        $underutilizedAssets = $assets->whereIn('condition', ['kurang_produktif', 'jarang_digunakan'])->count();
        $highPotentialAssets = $assets->where('potential_score', '>=', 70)->count();
        $communityReports = \App\Models\AssetReport::whereHas('village', fn($q) => $q->where('district_id', $districtId))->count();
        $totalValuation = $assets->sum('estimated_economic_value') ?: ($assets->sum('area') * 250000);

        $districtName = $this->district ? $this->district->name : 'Manyar';

        return view('livewire.district-dashboard', compact(
            'villages',
            'assets',
            'totalDesa',
            'totalAssets',
            'productiveAssets',
            'unusedAssets',
            'underutilizedAssets',
            'highPotentialAssets',
            'communityReports',
            'totalValuation',
            'districtName'
        ))->layout('layouts.admin', [
            'title' => "Dashboard Kecamatan {$districtName}",
            'headerTitle' => "Pemerintah Kecamatan {$districtName}"
        ]);
    }
}
