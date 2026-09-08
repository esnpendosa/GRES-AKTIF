<?php

namespace App\Services;

use App\Models\Badge;
use App\Models\User;
use App\Models\UserPointsHistory;

class GamificationService
{
    /**
     * Award points to user and check for unlocked badges.
     */
    public static function awardPoints(User $user, int $points, string $reason, ?string $refType = null, ?int $refId = null): void
    {
        $user->increment('points', $points);

        UserPointsHistory::create([
            'user_id' => $user->id,
            'points' => $points,
            'reason' => $reason,
            'reference_type' => $refType,
            'reference_id' => $refId,
        ]);

        // Update reputation level
        $totalPoints = $user->points;
        $reputation = match (true) {
            $totalPoints >= 300 => 'Inovator Gresik',
            $totalPoints >= 150 => 'Penggerak Ekonomi',
            $totalPoints >= 50 => 'Kontributor Desa',
            default => 'Warga Aktif',
        };

        $user->update(['reputation_level' => $reputation]);

        // Check badge unlocks
        self::checkBadgeUnlocks($user);
    }

    protected static function checkBadgeUnlocks(User $user): void
    {
        $eligibleBadges = Badge::where('min_points', '<=', $user->points)->get();

        foreach ($eligibleBadges as $badge) {
            if (!$user->badges()->where('badge_id', $badge->id)->exists()) {
                $user->badges()->attach($badge->id, ['awarded_at' => now()]);
            }
        }
    }
}
