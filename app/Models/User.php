<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'points' => 'integer',
    ];

    // Relationships
    public function village(): BelongsTo
    {
        return $this->belongsTo(Village::class);
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(AssetReport::class);
    }

    public function ideas(): HasMany
    {
        return $this->hasMany(AssetIdea::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(AssetComment::class);
    }

    public function votes(): HasMany
    {
        return $this->hasMany(IdeaVote::class);
    }

    public function badges(): BelongsToMany
    {
        return $this->belongsToMany(Badge::class, 'user_badges')->withPivot('awarded_at');
    }

    public function pointsHistory(): HasMany
    {
        return $this->hasMany(UserPointsHistory::class);
    }

    // Role checks
    public function isCommunity(): bool
    {
        return $this->role === 'community';
    }

    public function isVillageAdmin(): bool
    {
        return $this->role === 'village_admin';
    }

    public function isDistrictAdmin(): bool
    {
        return $this->role === 'district_admin';
    }

    public function isRegencyAdmin(): bool
    {
        return $this->role === 'regency_admin';
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isGovernment(): bool
    {
        return in_array($this->role, ['village_admin', 'district_admin', 'regency_admin', 'super_admin']);
    }

    public function getRoleTitleAttribute(): string
    {
        return match ($this->role) {
            'village_admin' => 'Pemerintah Desa',
            'district_admin' => 'Pemerintah Kecamatan',
            'regency_admin' => 'Pemerintah Kabupaten (Bappeda)',
            'super_admin' => 'Super Administrator',
            default => 'Masyarakat / Komunitas',
        };
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            if (str_starts_with($this->avatar, 'http')) {
                return $this->avatar;
            }
            return asset('storage/' . $this->avatar);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=0d9488&color=ffffff&bold=true';
    }
}
