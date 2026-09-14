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
    // Filter & Search
    public string $statusFilter = 'all';
    public string $search = '';
    public string $categoryFilter = 'all';

    // Modals
    public bool $showCreateModal = false;
    public bool $showUpdateModal = false;
    public bool $showHistoryModal = false;
    public bool $showDeleteConfirmModal = false;
    public bool $isEditing = false;

    // Selected Project
    public ?int $selectedProjectId = null;

    // Project Form Fields
    public ?int $asset_id = null;
    public string $title = '';
    public string $category = 'UMKM';
    public string $objective = '';
    public $budget_estimate = '';
    public string $funding_source = 'BUMDes & Dana Desa';
    public string $responsible_department = 'Pemerintah Desa';
    public ?string $start_date = null;
    public ?string $target_completion = null;
    public string $status = 'planning';
    public int $progress_percentage = 0;
    public string $description = '';

    // Update Progress Fields
    public int $update_progress_percentage = 0;
    public string $update_title = '';
    public string $update_notes = '';
    public string $update_status = 'in_progress';

    // Toast Alert
    public ?string $successMessage = null;

    public function mount()
    {
        $this->start_date = now()->format('Y-m-d');
        $this->target_completion = now()->addMonths(6)->format('Y-m-d');
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->isEditing = false;
        
        $firstAsset = Asset::first();
        if ($firstAsset) {
            $this->asset_id = $firstAsset->id;
        }

        $this->start_date = now()->format('Y-m-d');
        $this->target_completion = now()->addMonths(6)->format('Y-m-d');
        $this->showCreateModal = true;
    }

    public function openEditModal(int $projectId)
    {
        $project = AssetProject::findOrFail($projectId);
        $this->selectedProjectId = $project->id;
        $this->asset_id = $project->asset_id;
        $this->title = $project->title;
        $this->category = $project->category ?? 'UMKM';
        $this->objective = $project->objective ?? '';
        $this->budget_estimate = $project->budget_estimate;
        $this->funding_source = $project->funding_source ?? 'BUMDes & Dana Desa';
        $this->responsible_department = $project->responsible_department ?? 'Pemerintah Desa';
        $this->start_date = $project->start_date ? $project->start_date->format('Y-m-d') : now()->format('Y-m-d');
        $this->target_completion = $project->target_completion ? $project->target_completion->format('Y-m-d') : now()->addMonths(6)->format('Y-m-d');
        $this->status = $project->status;
        $this->progress_percentage = $project->progress_percentage;
        $this->description = $project->description ?? '';
        
        $this->isEditing = true;
        $this->showCreateModal = true;
    }

    public function saveProject()
    {
        $this->validate([
            'asset_id' => 'required|exists:assets,id',
            'title' => 'required|string|min:4|max:255',
            'category' => 'required|string',
            'objective' => 'required|string|min:5',
            'budget_estimate' => 'required|numeric|min:0',
            'funding_source' => 'required|string',
            'responsible_department' => 'required|string',
            'status' => 'required|in:planning,approved,in_progress,completed,cancelled',
            'progress_percentage' => 'required|integer|min:0|max:100',
        ], [
            'asset_id.required' => 'Pilih salah satu aset desa.',
            'title.required' => 'Judul proyek aktivasi wajib diisi.',
            'objective.required' => 'Tujuan proyek wajib diisi.',
            'budget_estimate.required' => 'Estimasi anggaran wajib diisi angka.',
        ]);

        if ($this->isEditing && $this->selectedProjectId) {
            $project = AssetProject::findOrFail($this->selectedProjectId);
            $project->update([
                'asset_id' => $this->asset_id,
                'title' => $this->title,
                'category' => $this->category,
                'objective' => $this->objective,
                'budget_estimate' => $this->budget_estimate,
                'funding_source' => $this->funding_source,
                'responsible_department' => $this->responsible_department,
                'start_date' => $this->start_date ?: null,
                'target_completion' => $this->target_completion ?: null,
                'status' => $this->status,
                'progress_percentage' => $this->progress_percentage,
                'description' => $this->description,
            ]);

            AuditLogger::log('update', 'AssetProject', $project->id, null, ['title' => $project->title]);
            $this->successMessage = "Proyek '{$project->title}' berhasil diperbarui!";
        } else {
            $project = AssetProject::create([
                'asset_id' => $this->asset_id,
                'title' => $this->title,
                'category' => $this->category,
                'objective' => $this->objective,
                'budget_estimate' => $this->budget_estimate,
                'funding_source' => $this->funding_source,
                'responsible_department' => $this->responsible_department,
                'start_date' => $this->start_date ?: now(),
                'target_completion' => $this->target_completion ?: now()->addMonths(6),
                'status' => $this->status,
                'progress_percentage' => $this->progress_percentage,
                'description' => $this->description,
            ]);

            // Create initial milestone update
            AssetProjectUpdate::create([
                'project_id' => $project->id,
                'user_id' => Auth::id(),
                'title' => 'Inisiasi & Pencatatan Agenda Proyek',
                'notes' => 'Proyek baru telah didaftarkan ke sistem pemantauan aktivasi aset desa.',
                'progress_percentage' => $this->progress_percentage,
            ]);

            AuditLogger::log('create', 'AssetProject', $project->id, null, ['title' => $project->title]);
            $this->successMessage = "Proyek baru '{$project->title}' berhasil ditambahkan ke rencana eksekusi!";
        }

        $this->showCreateModal = false;
        $this->resetForm();
    }

    public function openUpdateModal(int $projectId)
    {
        $project = AssetProject::findOrFail($projectId);
        $this->selectedProjectId = $project->id;
        $this->update_progress_percentage = $project->progress_percentage;
        $this->update_status = $project->status;
        $this->update_title = '';
        $this->update_notes = '';
        $this->showUpdateModal = true;
    }

    public function saveProgressUpdate()
    {
        $this->validate([
            'update_title' => 'required|string|min:4|max:255',
            'update_progress_percentage' => 'required|integer|min:0|max:100',
            'update_status' => 'required|in:planning,approved,in_progress,completed,cancelled',
            'update_notes' => 'nullable|string',
        ], [
            'update_title.required' => 'Judul pencapaian / milestone wajib diisi.',
            'update_progress_percentage.required' => 'Persentase progres wajib ditentukan.',
        ]);

        $project = AssetProject::findOrFail($this->selectedProjectId);

        // Record update log
        AssetProjectUpdate::create([
            'project_id' => $project->id,
            'user_id' => Auth::id(),
            'title' => $this->update_title,
            'notes' => $this->update_notes,
            'progress_percentage' => $this->update_progress_percentage,
        ]);

        // Auto change status if 100%
        $newStatus = $this->update_status;
        if ($this->update_progress_percentage >= 100 && $newStatus !== 'completed') {
            $newStatus = 'completed';
        }

        $project->update([
            'progress_percentage' => $this->update_progress_percentage,
            'status' => $newStatus,
        ]);

        // If completed, update related asset status to active
        if ($newStatus === 'completed' && $project->asset) {
            $project->asset->update(['status' => 'active']);
        }

        AuditLogger::log('progress_update', 'AssetProject', $project->id, null, ['progress' => $this->update_progress_percentage]);

        $this->successMessage = "Pencapaian '{$this->update_title}' ({$this->update_progress_percentage}%) berhasil dicatat!";
        $this->showUpdateModal = false;
        $this->update_title = '';
        $this->update_notes = '';
    }

    public function openHistoryModal(int $projectId)
    {
        $this->selectedProjectId = $projectId;
        $this->showHistoryModal = true;
    }

    public function confirmDelete(int $projectId)
    {
        $this->selectedProjectId = $projectId;
        $this->showDeleteConfirmModal = true;
    }

    public function deleteProject()
    {
        if ($this->selectedProjectId) {
            $project = AssetProject::findOrFail($this->selectedProjectId);
            $title = $project->title;
            $id = $project->id;
            $project->delete();
            AuditLogger::log('delete', 'AssetProject', $id, ['title' => $title], null);
            $this->successMessage = "Proyek '{$title}' telah dihapus.";
        }

        $this->showDeleteConfirmModal = false;
        $this->selectedProjectId = null;
    }

    public function resetForm()
    {
        $this->selectedProjectId = null;
        $this->title = '';
        $this->category = 'UMKM';
        $this->objective = '';
        $this->budget_estimate = '';
        $this->funding_source = 'BUMDes & Dana Desa';
        $this->responsible_department = 'Pemerintah Desa';
        $this->status = 'planning';
        $this->progress_percentage = 0;
        $this->description = '';
    }

    public function dismissToast()
    {
        $this->successMessage = null;
    }

    public function render()
    {
        $query = AssetProject::with(['asset.village.district', 'updates.user']);

        // Filter status
        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        // Filter category
        if ($this->categoryFilter !== 'all') {
            $query->where('category', $this->categoryFilter);
        }

        // Search
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                    ->orWhere('objective', 'like', '%' . $this->search . '%')
                    ->orWhereHas('asset', function ($qa) {
                        $qa->where('name', 'like', '%' . $this->search . '%')
                           ->orWhereHas('village', function ($qv) {
                               $qv->where('name', 'like', '%' . $this->search . '%');
                           });
                    });
            });
        }

        $projects = $query->latest()->get();

        // Statistics
        $totalProjects = AssetProject::count();
        $inProgressCount = AssetProject::where('status', 'in_progress')->count();
        $planningCount = AssetProject::where('status', 'planning')->count();
        $completedCount = AssetProject::where('status', 'completed')->count();
        $totalBudget = AssetProject::sum('budget_estimate');

        // Assets for dropdown
        $availableAssets = Asset::with('village.district')->orderBy('name')->get();

        // Selected project for history modal
        $selectedProjectForHistory = $this->selectedProjectId ? AssetProject::with(['updates.user', 'asset.village.district'])->find($this->selectedProjectId) : null;

        return view('livewire.project-management', compact(
            'projects',
            'totalProjects',
            'inProgressCount',
            'planningCount',
            'completedCount',
            'totalBudget',
            'availableAssets',
            'selectedProjectForHistory'
        ))->layout('layouts.admin', [
            'title' => 'Proyek Aktivasi Aset Desa Gresik',
            'headerTitle' => 'Monitoring Realisasi & Eksekusi Lapangan'
        ]);
    }
}
