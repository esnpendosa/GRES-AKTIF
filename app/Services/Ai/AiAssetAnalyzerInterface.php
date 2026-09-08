<?php

namespace App\Services\Ai;

use App\Models\Asset;

interface AiAssetAnalyzerInterface
{
    /**
     * Analyze an asset and return scores, economic recommendations, and simulation scenarios.
     *
     * @param Asset $asset
     * @return array
     */
    public function analyzeAsset(Asset $asset): array;

    /**
     * Cluster and summarize community suggestions into consensus data.
     *
     * @param Asset $asset
     * @return array
     */
    public function analyzeCommunityConsensus(Asset $asset): array;
}
