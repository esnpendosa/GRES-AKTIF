<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetReport extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'photos' => 'array',
        'verified_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function village(): BelongsTo
    {
        return $this->belongsTo(Village::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(AssetCategory::class, 'category_id');
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function createdAsset(): BelongsTo
    {
        return $this->belongsTo(Asset::class, 'created_asset_id');
    }

    public function getConditionLabelAttribute(): string
    {
        return match ($this->condition) {
            'tidak_digunakan' => 'Tidak Digunakan',
            'jarang_digunakan' => 'Jarang Digunakan',
            'kurang_produktif' => 'Kurang Produktif',
            'rusak' => 'Rusak',
            'terbengkalai' => 'Terbengkalai',
            default => 'Belum Dipastikan',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Menunggu Verifikasi',
            'approved' => 'Disetujui & Divalidasi',
            'rejected' => 'Ditolak',
            'info_requested' => 'Memerlukan Klarifikasi',
            default => ucfirst($this->status),
        };
    }

    public function getStatusBadgeColorAttribute(): string
    {
        return match ($this->status) {
            'approved' => 'emerald',
            'rejected' => 'rose',
            'info_requested' => 'amber',
            default => 'cyan',
        };
    }
}
