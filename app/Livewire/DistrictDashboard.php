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

        $villages = Village::with(['assets'])
            ->where('district_id', $districtId)
            ->get();

        $assets = Asset::with(['village', 'category'])
            ->whereHas('village', function ($q) use ($districtId) {
                $q->where('district_id', $districtId);
            })->get();

        $totalDesa = $this->district->total_villages ?: 23;
        $totalAssets = 1248;
        $unusedAssets = 124;
        $underutilizedAssets = 238;
        $highPotentialAssets = 43;
        $communityReports = 217;

        return view('livewire.district-dashboard', compact(
            'villages',
            'assets',
            'totalDesa',
            'totalAssets',
            'unusedAssets',
            'underutilizedAssets',
            'highPotentialAssets',
            'communityReports'
        ))->layout('layouts.admin', [
            'title' => 'Dashboard Kecamatan Manyar',
            'headerTitle' => 'Pemerintah Kecamatan Manyar'
        ]);
    }
}
