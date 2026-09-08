<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetCategory;
use App\Models\AssetIdea;
use App\Models\AssetProject;
use App\Models\District;
use App\Services\Gis\GisService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $totalAssets = Asset::count();
        $productiveAssets = Asset::where('status', 'productive')->count();
        $potentialAssets = Asset::where('potential_score', '>=', 71)->count();
        $totalIdeas = AssetIdea::count();
        $activeProjects = AssetProject::whereIn('status', ['planning', 'approved', 'in_progress'])->count();

        $featuredAssets = Asset::with(['village.district', 'category', 'images'])
            ->where('potential_score', '>=', 75)
            ->orderByDesc('potential_score')
            ->take(6)
            ->get();

        $categories = AssetCategory::withCount('assets')->get();
        $districts = District::withCount('assets')->get();

        return view('pages.landing', compact(
            'totalAssets',
            'productiveAssets',
            'potentialAssets',
            'totalIdeas',
            'activeProjects',
            'featuredAssets',
            'categories',
            'districts'
        ));
    }
}
