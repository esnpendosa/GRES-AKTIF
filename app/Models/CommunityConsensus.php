<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommunityConsensus extends Model
{
    use HasFactory;

    protected $table = 'community_consensus';

    protected $guarded = [];

    protected $casts = [
        'total_suggestions' => 'integer',
        'confidence_percentage' => 'integer',
        'clusters' => 'array',
    ];

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }
}
