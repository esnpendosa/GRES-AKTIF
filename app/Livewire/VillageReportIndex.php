<?php

namespace App\Livewire;

use App\Models\Asset;
use App\Models\AssetReport;
use App\Models\CitizenSuggestion;
use App\Models\District;
use App\Models\Village;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Laporan & Rekapitulasi Aset per Desa - KENTONGAN AI')]
class VillageReportIndex extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url(as: 'kecamatan')]
    public string $selectedDistrict = '';

    #[Url(as: 'status')]
    public string $statusFilter = 'all';

    public $selectedVillageDetail = null;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSelectedDistrict()
    {
        $this->resetPage();
    }

    public function showDetail($villageId)
    {
        $village = Village::with(['district', 'assets.category', 'reports.category', 'reports.user'])
            ->find($villageId);

        if ($village) {
            $suggestionsCount = CitizenSuggestion::whereHas('asset', function ($q) use ($villageId) {
                $q->where('village_id', $villageId);
            })->count();

            $this->selectedVillageDetail = [
                'village' => $village,
                'suggestions_count' => $suggestionsCount,
                'total_valuation' => $village->assets->sum('estimated_value') ?? 0,
                'non_active_assets' => $village->assets->whereIn('condition', ['rusak', 'terbengkalai', 'tidak_digunakan', 'kurang_produktif'])->count(),
                'active_assets' => $village->assets->where('condition', 'baik')->count(),
            ];
        }
    }

    public function closeDetail()
    {
        $this->selectedVillageDetail = null;
    }

    public function render()
    {
        $districts = District::orderBy('name')->get();

        $query = Village::with(['district'])
            ->withCount([
                'assets',
                'assets as non_active_assets_count' => function ($q) {
                    $q->whereIn('condition', ['rusak', 'terbengkalai', 'tidak_digunakan', 'kurang_produktif']);
                },
                'assets as active_assets_count' => function ($q) {
                    $q->where('condition', 'baik');
                },
                'reports',
            ])
            ->withSum('assets as total_asset_value', 'estimated_value');

        if (!empty($this->search)) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        if (!empty($this->selectedDistrict)) {
            $query->where('district_id', $this->selectedDistrict);
        }

        if ($this->statusFilter === 'has_assets') {
            $query->has('assets');
        } elseif ($this->statusFilter === 'has_reports') {
            $query->has('reports');
        } elseif ($this->statusFilter === 'needs_attention') {
            $query->whereHas('assets', function ($q) {
                $q->whereIn('condition', ['rusak', 'terbengkalai']);
            });
        }

        $villages = $query->orderBy('name')->paginate(12);

        // Overall stats
        $totalVillages = Village::count();
        $totalAssets = Asset::count();
        $totalReports = AssetReport::count();
        $totalSuggestions = CitizenSuggestion::count();
        $totalValuation = Asset::sum('estimated_value');

        return view('livewire.village-report-index', compact(
            'villages',
            'districts',
            'totalVillages',
            'totalAssets',
            'totalReports',
            'totalSuggestions',
            'totalValuation'
        ));
    }
}
