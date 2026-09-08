<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiAnalysis extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'location_score' => 'integer',
        'accessibility_score' => 'integer',
        'condition_score' => 'integer',
        'infrastructure_score' => 'integer',
        'community_score' => 'integer',
        'economic_score' => 'integer',
        'potential_score' => 'integer',
        'confidence_score' => 'integer',
        'recommendations' => 'array',
        'economic_scenarios' => 'array',
    ];

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }
}
