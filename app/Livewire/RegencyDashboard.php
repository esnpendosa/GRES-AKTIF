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

        return view('livewire.regency-dashboard', compact(
            'totalAssets',
            'productiveAssets',
            'underutilizedAssets',
            'unusedAssets',
            'highOpportunityAssets',
            'topOpportunities',
            'districts',
            'categories'
        ))->layout('layouts.admin', [
            'title' => 'Gresik Asset Intelligence',
            'headerTitle' => 'Gresik Asset Intelligence (Bappeda / BPKAD)'
        ]);
    }
}
