<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IdeaVote extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function idea(): BelongsTo
    {
        return $this->belongsTo(AssetIdea::class, 'idea_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
