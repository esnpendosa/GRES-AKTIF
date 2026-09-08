<?php

namespace App\Services\Ai;

use App\Models\Asset;
use App\Models\AssetIdea;

class MockAiProvider implements AiAssetAnalyzerInterface
{
    public function analyzeAsset(Asset $asset): array
    {
        $area = $asset->area > 0 ? $asset->area : 450;
        $categoryName = $asset->category ? $asset->category->name : 'Bangunan';
        $villageName = $asset->village ? $asset->village->name : 'Gresik';
        $districtName = $asset->village && $asset->village->district ? $asset->village->district->name : 'Manyar';

        // Calculate heuristic multi-factor scores
        $locationScore = match (strtolower($districtName)) {
            'manyar', 'kebomas', 'gresik' => rand(85, 95),
            'menganti', 'driyorejo', 'cerme' => rand(78, 88),
            'sidayu', 'bungah', 'ujungpangkah' => rand(72, 84),
            default => rand(65, 80),
        };

        $accessibilityScore = match ($asset->condition) {
            'rusak', 'terbengkalai' => rand(55, 75),
            'kurang_produktif' => rand(75, 88),
            default => rand(80, 92),
        };

        $conditionScore = match ($asset->condition) {
            'tidak_digunakan' => 60,
            'jarang_digunakan' => 72,
            'kurang_produktif' => 68,
            'rusak' => 45,
            'terbengkalai' => 38,
            default => 65,
        };

        $infrastructureScore = rand(70, 90);
        $communityDemandScore = min(98, 65 + ($asset->ideas()->count() * 4) + ($asset->supporters_count > 0 ? 15 : 0));
        $economicScore = rand(80, 95);

        // Weighted Formula:
        // 20% Location, 15% Accessibility, 15% Condition, 10% Size, 10% Infrastructure, 15% Community Demand, 15% Economic Potential
        $sizeScore = $area >= 1000 ? 95 : ($area >= 400 ? 88 : ($area >= 150 ? 75 : 60));

        $potentialScore = round(
            ($locationScore * 0.20) +
            ($accessibilityScore * 0.15) +
            ($conditionScore * 0.15) +
            ($sizeScore * 0.10) +
            ($infrastructureScore * 0.10) +
            ($communityDemandScore * 0.15) +
            ($economicScore * 0.15)
        );

        // Economic scenarios tailoring
        $scenarios = [
            [
                'code' => 'A',
                'title' => 'Sentra UMKM & Produk Unggulan Desa',
                'category' => 'UMKM',
                'potential_score' => 94,
                'estimated_tenants' => max(8, round($area / 30)),
                'estimated_jobs' => max(15, round($area / 15)),
                'estimated_renovation' => 'Sedang (Rp 75M - 150M)',
                'economic_impact' => 'Tinggi',
                'rationale' => "Luas area {$area} m² sangat strategis untuk kios modular pelaku usaha mikro, stan kuliner oleh-oleh khas Gresik (pudak, bandeng), dan ruang display kerajinan warga.",
                'confidence' => 94,
            ],
            [
                'code' => 'B',
                'title' => 'Pusat Kuliner & Pujasera Komunitas',
                'category' => 'Kuliner',
                'potential_score' => 87,
                'estimated_tenants' => max(6, round($area / 40)),
                'estimated_jobs' => max(12, round($area / 20)),
                'estimated_renovation' => 'Ringan-Sedang (Rp 50M - 100M)',
                'economic_impact' => 'Menengah - Tinggi',
                'rationale' => "Aksesibilitas yang baik di {$villageName} mendukung perputaran ekonomi malam hari untuk pedagang makanan lokal dan ruang interaksi santai warga.",
                'confidence' => 87,
            ],
            [
                'code' => 'C',
                'title' => 'Balai Pelatihan Vokasi & Coworking Desa',
                'category' => 'Pendidikan & Vokasi',
                'potential_score' => 81,
                'estimated_tenants' => 4,
                'estimated_jobs' => 10,
                'estimated_renovation' => 'Ringan (Rp 40M - 80M)',
                'economic_impact' => 'Menengah (Jangka Panjang)',
                'rationale' => "Menyediakan ruang inkubasi keterampilan digital pemuda desa, pelatihan sertifikasi industri, dan balai literasi wirausaha.",
                'confidence' => 81,
            ],
        ];

        $recommendations = [
            [
                'rank' => 1,
                'title' => 'Sentra UMKM & Display Produk Lokal',
                'match_percentage' => 94,
                'category' => 'UMKM',
                'description' => "Potensi konversi ruang menjadi sentra ritel dan kemasan produk BUMDes serta UMKM lokal.",
            ],
            [
                'rank' => 2,
                'title' => 'Pusat Kuliner Kreatif & Pujasera Desa',
                'match_percentage' => 87,
                'category' => 'Kuliner',
                'description' => "Optimalisasi ruang terbuka & pelataran menjadi destinasi kuliner higienis terpusat.",
            ],
            [
                'rank' => 3,
                'title' => 'Balai Pelatihan & Digital Creative Hub',
                'match_percentage' => 81,
                'category' => 'Edukasi / Vokasi',
                'description' => "Penyediaan fasilitas workshop ketrampilan kerja terpadu untuk pemuda dan ibu-ibu PKK.",
            ],
        ];

        $summary = "Aset {$asset->name} di {$villageName}, Kecamatan {$districtName} memiliki luas {$area} m² dengan aksesibilitas yang memadai ({$accessibilityScore}/100). Lokasi yang berdekatan dengan pusat aktivitas warga meningkatkan kelayakan aktivasi menjadi Sentra UMKM dan ruang ekonomi produktif berbasis BUMDes.";

        return [
            'provider' => 'local_heuristic',
            'model' => 'gresik-asset-v1',
            'analysis_type' => 'comprehensive',
            'location_score' => $locationScore,
            'accessibility_score' => $accessibilityScore,
            'condition_score' => $conditionScore,
            'infrastructure_score' => $infrastructureScore,
            'community_score' => $communityDemandScore,
            'economic_score' => $economicScore,
            'potential_score' => $potentialScore,
            'confidence_score' => 89,
            'summary' => $summary,
            'recommendations' => $recommendations,
            'economic_scenarios' => $scenarios,
            'raw_response' => json_encode(['status' => 'success', 'generated_at' => now()->toIso8601String()]),
        ];
    }

    public function analyzeCommunityConsensus(Asset $asset): array
    {
        $ideas = $asset->ideas()->with('votes')->get();
        $totalSuggestions = $ideas->count();

        if ($totalSuggestions === 0) {
            return [
                'total_suggestions' => 0,
                'dominant_category' => 'UMKM',
                'consensus_summary' => 'Belum ada aspirasi terdaftar dari masyarakat untuk aset ini.',
                'clusters' => [
                    ['category' => 'Sentra UMKM', 'percentage' => 45, 'votes' => 0],
                    ['category' => 'Pusat Kuliner', 'percentage' => 30, 'votes' => 0],
                    ['category' => 'Fasilitas Olahraga', 'percentage' => 15, 'votes' => 0],
                    ['category' => 'Lainnya', 'percentage' => 10, 'votes' => 0],
                ],
                'confidence_percentage' => 75,
            ];
        }

        $categoriesCount = [];
        $totalVotes = 0;

        foreach ($ideas as $idea) {
            $cat = $idea->category ?: 'Lainnya';
            $votes = max(1, $idea->votes_count);
            $categoriesCount[$cat] = ($categoriesCount[$cat] ?? 0) + $votes;
            $totalVotes += $votes;
        }

        arsort($categoriesCount);
        $dominant = array_key_first($categoriesCount) ?: 'UMKM';

        $clusters = [];
        foreach ($categoriesCount as $cat => $count) {
            $clusters[] = [
                'category' => $cat,
                'percentage' => round(($count / max(1, $totalVotes)) * 100),
                'votes' => $count,
            ];
        }

        $consensusSummary = "Mayoritas aspirasi masyarakat ({$clusters[0]['percentage']}%) mengusulkan pemanfaatan aset ini difokuskan sebagai '{$dominant}' yang mendukung perputaran ekonomi langsung warga.";

        return [
            'total_suggestions' => $totalSuggestions,
            'dominant_category' => $dominant,
            'consensus_summary' => $consensusSummary,
            'clusters' => $clusters,
            'confidence_percentage' => min(98, 80 + ($totalSuggestions * 2)),
        ];
    }
}
