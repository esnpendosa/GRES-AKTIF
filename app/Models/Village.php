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
}
