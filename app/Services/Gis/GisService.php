<?php

namespace App\Services\Gis;

use App\Models\Asset;
use Illuminate\Database\Eloquent\Collection;

class GisService
{
    /**
     * Calculate Haversine distance between two coordinates in kilometers.
     */
    public static function haversineDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371; // km

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round($earthRadius * $c, 2);
    }

    /**
     * Convert collection of assets to GeoJSON FeatureCollection format for Leaflet.
     */
    public function toGeoJson($assets): array
    {
        $features = [];

        foreach ($assets as $asset) {
            $features[] = [
                'type' => 'Feature',
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => [(float)$asset->longitude, (float)$asset->latitude],
                ],
                'properties' => [
                    'id' => $asset->id,
                    'name' => $asset->name,
                    'slug' => $asset->slug,
                    'village' => $asset->village ? $asset->village->name : 'Gresik',
                    'district' => $asset->village && $asset->village->district ? $asset->village->district->name : '',
                    'condition' => $asset->condition_label,
                    'condition_code' => $asset->condition,
                    'status' => $asset->status_label,
                    'status_code' => $asset->status,
                    'potential_score' => $asset->potential_score,
                    'potential_level' => $asset->potential_level,
                    'category' => $asset->category ? $asset->category->name : 'Bangunan',
                    'target_use' => $asset->target_activation_use ?? 'Sentra UMKM',
                    'area' => $asset->area,
                    'marker_color' => $asset->map_marker_color,
                    'image' => $asset->primary_image_url,
                    'url' => route('assets.show', $asset->slug ?? $asset->id),
                ],
            ];
        }

        return [
            'type' => 'FeatureCollection',
            'features' => $features,
        ];
    }
}
