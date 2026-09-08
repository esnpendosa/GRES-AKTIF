<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetCategory;
use App\Models\District;
use App\Services\Gis\GisService;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    public function explore(Request $request)
    {
        return view('pages.explore');
    }

    public function show(string $slugOrId)
    {
        $asset = Asset::with([
            'village.district',
            'category',
            'images',
            'latestAiAnalysis',
            'consensus',
            'ideas.user',
            'ideas.votes',
            'comments.user',
            'projects.updates',
        ])
        ->where('slug', $slugOrId)
        ->orWhere('id', $slugOrId)
        ->firstOrFail();

        // Increment supporters/views or get related assets
        $relatedAssets = Asset::with(['village', 'category'])
            ->where('village_id', $asset->village_id)
            ->where('id', '!=', $asset->id)
            ->take(3)
            ->get();

        return view('pages.asset-detail', compact('asset', 'relatedAssets'));
    }

    public function map(Request $request)
    {
        $categories = AssetCategory::all();
        $districts = District::all();
        return view('pages.opportunity-map', compact('categories', 'districts'));
    }
}
