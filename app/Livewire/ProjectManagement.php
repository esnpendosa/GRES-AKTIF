<?php

namespace App\Livewire;

use App\Models\Asset;
use App\Models\AssetProject;
use App\Models\AssetProjectUpdate;
use App\Services\AuditLogger;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ProjectManagement extends Component
{
    public string $statusFilter = 'all';

    public function render()
    {
        $query = AssetProject::with(['asset.village.district', 'updates.user']);

        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        $projects = $query->latest()->get();

        return view('livewire.project-management', compact('projects'))
            ->layout('layouts.admin', [
                'title' => 'Proyek Aktivasi Aset',
                'headerTitle' => 'Manajemen Proyek Aktivasi Aset'
            ]);
    }
}
