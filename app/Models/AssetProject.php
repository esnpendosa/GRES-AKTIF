<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AssetProject extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'budget_estimate' => 'float',
        'progress_percentage' => 'integer',
        'start_date' => 'date',
        'target_completion' => 'date',
    ];

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    public function updates(): HasMany
    {
        return $this->hasMany(AssetProjectUpdate::class, 'project_id')->latest();
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'planning' => 'Perencanaan',
            'approved' => 'Disetujui',
            'in_progress' => 'Dalam Pelaksanaan',
            'completed' => 'Selesai & Aktif',
            'cancelled' => 'Dibatalkan',
            default => ucfirst($this->status),
        };
    }

    public function getStatusBadgeColorAttribute(): string
    {
        return match ($this->status) {
            'planning' => 'amber',
            'approved' => 'blue',
            'in_progress' => 'cyan',
            'completed' => 'emerald',
            'cancelled' => 'rose',
            default => 'slate',
        };
    }
}
