<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Village extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'boundary' => 'array',
    ];

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(AssetReport::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Kembalikan boundary polygon desa.
     * Jika boundary belum diset, buat kotak otomatis ±0.008° dari pusat desa.
     */
    public function getBoundaryCoords(): array
    {
        if (!empty($this->boundary)) {
            return $this->boundary;
        }

        // Fallback: buat kotak kasar dari koordinat pusat desa (±~900m)
        $lat = (float) ($this->latitude ?? -7.1350);
        $lng = (float) ($this->longitude ?? 112.6020);
        $delta = 0.008;

        return [
            [$lat - $delta, $lng - $delta],
            [$lat - $delta, $lng + $delta],
            [$lat + $delta, $lng + $delta],
            [$lat + $delta, $lng - $delta],
        ];
    }
}
