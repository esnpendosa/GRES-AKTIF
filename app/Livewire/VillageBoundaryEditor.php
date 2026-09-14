<?php

namespace App\Livewire;

use App\Models\Village;
use App\Services\AuditLogger;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class VillageBoundaryEditor extends Component
{
    public ?Village $village = null;
    public int $selectedVillageId = 1;
    public string $boundaryJson = '[]';
    public bool $saved = false;

    public function mount(): void
    {
        $user = Auth::user();
        if ($user && $user->village_id) {
            $this->village = Village::find($user->village_id);
        }
        if (!$this->village) {
            $this->village = Village::first();
        }

        if ($this->village) {
            $this->selectedVillageId = $this->village->id;
            $this->boundaryJson = !empty($this->village->boundary)
                ? json_encode($this->village->boundary)
                : '[]';
        }
    }

    public function updatedSelectedVillageId($id): void
    {
        $this->village = Village::find($id);
        if ($this->village) {
            $this->boundaryJson = !empty($this->village->boundary)
                ? json_encode($this->village->boundary)
                : '[]';
            $this->saved = false;
            $this->resetErrorBag();

            $this->dispatch('village-switched', [
                'villageId'   => $this->village->id,
                'villageName' => $this->village->name,
                'lat'         => (float) ($this->village->latitude ?? -7.1350),
                'lng'         => (float) ($this->village->longitude ?? 112.6020),
                'boundary'    => $this->village->boundary ?? [],
            ]);
        }
    }

    #[On('save-boundary-coords')]
    public function saveBoundaryCoords(string $coordsJson): void
    {
        $this->saveBoundary($coordsJson);
    }

    public function saveBoundary(?string $coordsJson = null): void
    {
        if (Auth::user()?->isDistrictAdmin()) {
            session()->flash('error', 'Akses Ditolak: Pemerintah Kecamatan berstatus Pengawas (Hanya Lihat).');
            return;
        }

        $json = $coordsJson ?: $this->boundaryJson;
        $coords = json_decode($json, true);

        if (!is_array($coords) || count($coords) < 3) {
            $this->addError('boundary', 'Batas desa harus memiliki minimal 3 titik sudut.');
            session()->flash('error', 'Gagal menyimpan: Batas desa harus memiliki minimal 3 titik sudut.');
            return;
        }

        foreach ($coords as $point) {
            if (!is_array($point) || count($point) < 2 || !is_numeric($point[0]) || !is_numeric($point[1])) {
                $this->addError('boundary', 'Format koordinat titik batas tidak valid.');
                session()->flash('error', 'Gagal menyimpan: Format koordinat titik batas tidak valid.');
                return;
            }
        }

        if (!$this->village) {
            $this->village = Village::find($this->selectedVillageId) ?: Village::first();
        }

        $this->village->update(['boundary' => $coords]);
        $this->boundaryJson = json_encode($coords);

        AuditLogger::log('updated', 'Village', $this->village->id, null, [
            'action' => 'boundary_updated',
            'points' => count($coords),
        ]);

        $this->saved = true;
        $pointCount = count($coords);
        session()->flash('success', "Batas wilayah Desa {$this->village->name} ({$pointCount} titik sudut) berhasil disimpan ke database!");
        $this->dispatch('boundary-saved');
    }

    public function clearBoundary(): void
    {
        if (Auth::user()?->isDistrictAdmin()) {
            session()->flash('error', 'Akses Ditolak: Pemerintah Kecamatan berstatus Pengawas (Hanya Lihat).');
            return;
        }

        if ($this->village) {
            $this->village->update(['boundary' => null]);
            $this->boundaryJson = '[]';
            $this->saved = false;
            session()->flash('success', "Batas wilayah Desa {$this->village->name} berhasil dihapus.");
            $this->dispatch('boundary-cleared');
        }
    }

    public function render()
    {
        $allVillages = Village::with('district')->orderBy('name')->get();

        return view('livewire.village-boundary-editor', [
            'allVillages' => $allVillages,
        ])->layout('layouts.admin', [
            'title'       => 'Editor Batas Wilayah Desa',
            'headerTitle' => 'Editor Batas Wilayah — ' . ($this->village?->name ?? 'Desa'),
        ]);
    }
}