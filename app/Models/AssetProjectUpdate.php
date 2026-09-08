<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetProjectUpdate extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'progress_percentage' => 'integer',
        'media_urls' => 'array',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(AssetProject::class, 'project_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
