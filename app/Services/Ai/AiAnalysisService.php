<?php

namespace App\Services\Ai;

use App\Models\AiAnalysis;
use App\Models\Asset;
use App\Models\CommunityConsensus;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiAnalysisService
{
    protected AiAssetAnalyzerInterface $provider;

    public function __construct()
    {
        $apiKey = config('services.ai.api_key') ?? env('AI_API_KEY');
        if (!empty($apiKey) && env('AI_PROVIDER') === 'openai') {
            // Can instantiate OpenAiVisionProvider
            $this->provider = new MockAiProvider(); // default fallback
        } else {
            $this->provider = new MockAiProvider();
        }
    }

    /**
     * Run full AI analysis on an asset and save record.
     */
    public function analyzeAndPersist(Asset $asset): AiAnalysis
    {
        $result = $this->provider->analyzeAsset($asset);

        // Update asset's calculated scores
        $asset->update([
            'potential_score' => $result['potential_score'],
            'location_score' => $result['location_score'],
            'accessibility_score' => $result['accessibility_score'],
            'condition_score' => $result['condition_score'],
            'infrastructure_score' => $result['infrastructure_score'],
            'community_demand_score' => $result['community_score'],
            'economic_score' => $result['economic_score'],
            'target_activation_use' => $result['recommendations'][0]['title'] ?? $asset->target_activation_use,
            'status' => in_array($asset->status, ['reported', 'verified']) ? 'ai_analyzed' : $asset->status,
        ]);

        // Save AI Analysis log
        $analysis = AiAnalysis::create([
            'asset_id' => $asset->id,
            'provider' => $result['provider'],
            'model' => $result['model'],
            'analysis_type' => $result['analysis_type'],
            'location_score' => $result['location_score'],
            'accessibility_score' => $result['accessibility_score'],
            'condition_score' => $result['condition_score'],
            'infrastructure_score' => $result['infrastructure_score'],
            'community_score' => $result['community_score'],
            'economic_score' => $result['economic_score'],
            'potential_score' => $result['potential_score'],
            'confidence_score' => $result['confidence_score'],
            'summary' => $result['summary'],
            'recommendations' => $result['recommendations'],
            'economic_scenarios' => $result['economic_scenarios'],
            'raw_response' => $result['raw_response'],
        ]);

        // Also update consensus
        $this->updateConsensus($asset);

        return $analysis;
    }

    /**
     * Re-calculate and persist community consensus for an asset.
     */
    public function updateConsensus(Asset $asset): CommunityConsensus
    {
        $consensusData = $this->provider->analyzeCommunityConsensus($asset);

        return CommunityConsensus::updateOrCreate(
            ['asset_id' => $asset->id],
            [
                'total_suggestions' => $consensusData['total_suggestions'],
                'dominant_category' => $consensusData['dominant_category'],
                'consensus_summary' => $consensusData['consensus_summary'],
                'clusters' => $consensusData['clusters'],
                'confidence_percentage' => $consensusData['confidence_percentage'],
            ]
        );
    }
}
