<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AssetIdea extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'is_ai_recommended' => 'boolean',
        'votes_count' => 'integer',
    ];

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function votes(): HasMany
    {
        return $this->hasMany(IdeaVote::class, 'idea_id');
    }

    public function isVotedBy(?User $user): bool
    {
        if (!$user) return false;
        return $this->votes()->where('user_id', $user->id)->exists();
    }
}
