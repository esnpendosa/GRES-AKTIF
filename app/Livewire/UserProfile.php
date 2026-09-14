<?php

namespace App\Livewire;

use App\Models\Badge;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class UserProfile extends Component
{
    public function render()
    {
        $user = Auth::user();
        if (!$user) {
            $user = User::where('email', 'masyarakat@gresaktif.id')->first();
        }

        $userBadges = $user ? $user->badges : collect();
        $allBadges = Badge::all();
        $reports = $user ? $user->reports()->with('category')->latest()->get() : collect();
        $ideas = $user ? $user->ideas()->with('asset')->latest()->get() : collect();
        $pointsHistory = $user ? $user->pointsHistory()->latest()->take(10)->get() : collect();

        return view('livewire.user-profile', compact('user', 'userBadges', 'allBadges', 'reports', 'ideas', 'pointsHistory'))
            ->layout('layouts.admin', ['title' => 'Pengaturan Profil']);
    }
}
