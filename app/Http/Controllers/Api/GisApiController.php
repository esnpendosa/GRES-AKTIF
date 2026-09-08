<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Services\Gis\GisService;
use Illuminate\Http\Request;

class GisApiController extends Controller
{
    protected GisService $gisService;

    public function __construct(GisService $gisService)
    {
        $this->gisService = $gisService;
    }

    public function geojson(Request $request)
    {
        $query = Asset::with(['village.district', 'category', 'images']);

        // Filter by district
        if ($request->filled('district_id')) {
            $query->whereHas('village', function ($q) use ($request) {
                $q->where('district_id', $request->district_id);
            });
        }

        // Filter by village
        if ($request->filled('village_id')) {
            $query->where('village_id', $request->village_id);
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by condition
        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by opportunity / potential score
        if ($request->filled('min_potential')) {
            $query->where('potential_score', '>=', (int)$request->min_potential);
        }

        // AI Sector Filter (UMKM, Kuliner, Pertanian, Wisata, etc.)
        if ($request->filled('target_sector')) {
            $sector = $request->target_sector;
            $query->where(function ($q) use ($sector) {
                $q->where('target_activation_use', 'like', "%{$sector}%")
                  ->orWhereHas('category', function ($qc) use ($sector) {
                      $qc->where('name', 'like', "%{$sector}%");
                  });
            });
        }

        $assets = $query->get();

        // If radius search with user coordinates
        if ($request->filled('user_lat') && $request->filled('user_lng')) {
            $userLat = (float)$request->user_lat;
            $userLng = (float)$request->user_lng;
            $radiusKm = (float)($request->radius_km ?? 25);

            $assets = $assets->filter(function ($asset) use ($userLat, $userLng, $radiusKm) {
                $distance = GisService::haversineDistance($userLat, $userLng, $asset->latitude, $asset->longitude);
                $asset->distance_km = $distance;
                return $distance <= $radiusKm;
            })->values();
        }

        return response()->json($this->gisService->toGeoJson($assets));
    }
}
