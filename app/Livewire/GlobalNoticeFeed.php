<?php

namespace App\Livewire;

use App\Models\Asset;
use App\Models\AssetIdea;
use App\Models\AssetReport;
use Livewire\Component;

class GlobalNoticeFeed extends Component
{
    public bool $isDismissed = false;

    public function dismiss()
    {
        $this->isDismissed = true;
    }

    public function render()
    {
        if ($this->isDismissed) {
            return <<<'HTML'
            <div></div>
            HTML;
        }

        $latestAssets = Asset::with('village')
            ->latest('updated_at')
            ->take(3)
            ->get()
            ->map(function ($asset) {
                return [
                    'type' => 'asset',
                    'icon' => 'building-2',
                    'title' => 'Pembaruan Data Aset',
                    'message' => "Aset '{$asset->name}' di Desa " . ($asset->village->name ?? 'Gresik') . " telah diperbarui statusnya.",
                    'time' => $asset->updated_at ? $asset->updated_at->diffForHumans() : 'baru saja',
                    'link' => route('assets.show', $asset->slug ?? $asset->id),
                ];
            });

        $latestReports = AssetReport::with('village')
            ->latest('created_at')
            ->take(3)
            ->get()
            ->map(function ($report) {
                return [
                    'type' => 'report',
                    'icon' => 'flag',
                    'title' => 'Laporan Netizen Masuk',
                    'message' => "Laporan baru warga di Desa " . ($report->village->name ?? 'Gresik') . " ({$report->title})",
                    'time' => $report->created_at ? $report->created_at->diffForHumans() : 'baru saja',
                    'link' => route('map') . '?report=' . $report->id,
                ];
            });

        $latestSuggestions = AssetIdea::with('asset')
            ->latest('created_at')
            ->take(2)
            ->get()
            ->map(function ($sug) {
                return [
                    'type' => 'suggestion',
                    'icon' => 'sparkles',
                    'title' => 'Aspirasi AI Inklusif',
                    'message' => "Netizen mengusulkan ide: " . ($sug->title ?? 'Ide Optimalisasi Aset'),
                    'time' => $sug->created_at ? $sug->created_at->diffForHumans() : 'baru saja',
                    'link' => $sug->asset ? route('assets.show', $sug->asset->slug ?? $sug->asset_id) : route('explore'),
                ];
            });

        $feedItems = $latestReports->concat($latestAssets)->concat($latestSuggestions)->sortByDesc('time')->take(5)->values();

        return view('livewire.global-notice-feed', [
            'feedItems' => $feedItems
        ]);
    }
}
