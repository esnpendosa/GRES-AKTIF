<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Asset extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'area' => 'float',
        'potential_score' => 'integer',
        'location_score' => 'integer',
        'accessibility_score' => 'integer',
        'condition_score' => 'integer',
        'infrastructure_score' => 'integer',
        'community_demand_score' => 'integer',
        'economic_score' => 'integer',
        'estimated_economic_value' => 'float',
        'verified_at' => 'datetime',
    ];

    public function village(): BelongsTo
    {
        return $this->belongsTo(Village::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(AssetCategory::class, 'category_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function images(): HasMany
    {
        return $this->hasMany(AssetImage::class);
    }

    public function primaryImage()
    {
        return $this->hasOne(AssetImage::class)->where('is_primary', true)->withDefault(function () {
            return new AssetImage([
                'image_path' => 'assets/default-asset.jpg',
                'caption' => 'Foto Aset Default',
            ]);
        });
    }

    public function ideas(): HasMany
    {
        return $this->hasMany(AssetIdea::class)->orderByDesc('votes_count');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(AssetComment::class)->latest();
    }

    public function aiAnalyses(): HasMany
    {
        return $this->hasMany(AiAnalysis::class)->latest();
    }

    public function latestAiAnalysis(): HasOne
    {
        return $this->hasOne(AiAnalysis::class)->latestOfMany();
    }

    public function consensus(): HasOne
    {
        return $this->hasOne(CommunityConsensus::class)->latestOfMany();
    }

    public function projects(): HasMany
    {
        return $this->hasMany(AssetProject::class)->latest();
    }

    public function activeProject(): HasOne
    {
        return $this->hasOne(AssetProject::class)->latestOfMany();
    }

    // Helper Accessors
    public function getConditionLabelAttribute(): string
    {
        return match ($this->condition) {
            'tidak_digunakan' => 'Tidak Digunakan',
            'jarang_digunakan' => 'Jarang Digunakan',
            'kurang_produktif' => 'Kurang Produktif',
            'rusak' => 'Rusak',
            'terbengkalai' => 'Terbengkalai',
            default => ucfirst(str_replace('_', ' ', $this->condition)),
        };
    }

    public function getConditionBadgeColorAttribute(): string
    {
        return match ($this->condition) {
            'tidak_digunakan', 'terbengkalai' => 'rose',
            'rusak' => 'red',
            'jarang_digunakan', 'kurang_produktif' => 'amber',
            default => 'slate',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'reported' => 'Dilaporkan',
            'verified' => 'Terverifikasi',
            'ai_analyzed' => 'Tervalidasi AI',
            'community_discussion' => 'Diskusi Publik',
            'prioritized' => 'Diprioritaskan',
            'planning' => 'Perencanaan Proyek',
            'implementation' => 'Tahap Realisasi',
            'productive' => 'Aktif & Produktif',
            default => ucfirst(str_replace('_', ' ', $this->status)),
        };
    }

    public function getPotentialLevelAttribute(): string
    {
        if ($this->potential_score >= 71) return 'Tinggi';
        if ($this->potential_score >= 41) return 'Sedang';
        return 'Rendah';
    }

    public function getPotentialLevelColorAttribute(): string
    {
        if ($this->potential_score >= 71) return 'emerald';
        if ($this->potential_score >= 41) return 'cyan';
        return 'rose';
    }

    // Marker color for GIS Leaflet map
    public function getMapMarkerColorAttribute(): string
    {
        if ($this->status === 'productive') return '#10b981'; // Green
        if ($this->status === 'planning' || $this->status === 'implementation') return '#0284c7'; // Blue
        if ($this->condition === 'kurang_produktif' || $this->condition === 'jarang_digunakan') return '#f59e0b'; // Amber / Yellow
        return '#ef4444'; // Red (unused / terbengkalai)
    }

    public function getPrimaryImageUrlAttribute(): string
    {
        $primary = $this->images()->where('is_primary', true)->first();
        if ($primary && $primary->image_path) {
            if (str_starts_with($primary->image_path, 'http')) {
                return $primary->image_path;
            }
            return asset('storage/' . $primary->image_path);
        }
        $any = $this->images()->first();
        if ($any && $any->image_path) {
            if (str_starts_with($any->image_path, 'http')) {
                return $any->image_path;
            }
            return asset('storage/' . $any->image_path);
        }
        return 'https://images.unsplash.com/photo-1513694203232-719a280e022f?auto=format&fit=crop&w=800&q=80';
    }
}
