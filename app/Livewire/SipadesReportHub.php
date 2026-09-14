<?php

namespace App\Livewire;

use App\Models\District;
use App\Models\Village;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Laporan SIPADES - Sistem Pengelolaan Aset Desa')]
class SipadesReportHub extends Component
{
    public ?int $selectedVillageId = null;

    public function mount()
    {
        $user = auth()->user();
        if ($user && $user->village_id) {
            $this->selectedVillageId = $user->village_id;
        } else {
            $this->selectedVillageId = Village::first()?->id ?? 1;
        }
    }

    public function render()
    {
        $villages  = Village::with('district')->orderBy('name')->get();
        $districts = District::orderBy('name')->get();

        $currentVillage = Village::with('district')->find($this->selectedVillageId);
        if (!$currentVillage && $villages->isNotEmpty()) {
            $currentVillage          = $villages->first();
            $this->selectedVillageId = $currentVillage->id;
        }

        $menus = [
            [
                'icon'  => 'shopping-cart',
                'color' => 'blue',
                'label' => 'Pengadaan',
                'items' => [
                    ['label' => 'Laporan Pengadaan Barang Rekap',                          'route' => 'reports.sipades.pengadaan-rekap', 'icon' => 'file-bar-chart-2', 'badge' => 'REKAP',   'color' => 'blue'],
                    ['label' => 'Laporan Pengadaan Barang Berdasarkan Sumber Dana',        'route' => 'reports.sipades.pengadaan-dana',  'icon' => 'banknote',         'badge' => 'PER DANA','color' => 'indigo'],
                ],
            ],
            [
                'icon'  => 'archive',
                'color' => 'amber',
                'label' => 'Inventarisasi dan Penatausahaan',
                'items' => [
                    ['label' => 'Laporan Hasil Inventarisasi (LHI) Tanah',                      'route' => 'reports.sipades.lhi-tanah',       'icon' => 'map',           'badge' => 'LHI',  'color' => 'amber'],
                    ['label' => 'Laporan Hasil Inventarisasi (LHI) Peralatan dan Mesin',        'route' => 'reports.sipades.lhi-mesin',       'icon' => 'settings',      'badge' => 'LHI',  'color' => 'amber'],
                    ['label' => 'Laporan Hasil Inventarisasi (LHI) Kendaraan Bermotor',         'route' => 'reports.sipades.lhi-kendaraan',   'icon' => 'truck',         'badge' => 'LHI',  'color' => 'amber'],
                    ['label' => 'Laporan Hasil Inventarisasi (LHI) Gedung dan Bangunan',        'route' => 'reports.sipades.lhi-gedung',      'icon' => 'building-2',    'badge' => 'LHI',  'color' => 'amber'],
                    ['label' => 'Laporan Hasil Inventarisasi (LHI) Jalan, Irigasi dan Jaringan','route' => 'reports.sipades.lhi-jalan',       'icon' => 'route',         'badge' => 'LHI',  'color' => 'amber'],
                    ['label' => 'Laporan Hasil Inventarisasi (LHI) Aset Tetap Lainnya',         'route' => 'reports.sipades.lhi-lainnya',     'icon' => 'package',       'badge' => 'LHI',  'color' => 'amber'],
                    ['label' => 'Laporan KIB Tanah',                                            'route' => 'reports.kib_a',                  'icon' => 'map',           'badge' => 'KIB A','color' => 'teal'],
                    ['label' => 'Laporan KIB Peralatan dan Mesin',                              'route' => 'reports.sipades.kib-b',           'icon' => 'settings-2',    'badge' => 'KIB B','color' => 'teal'],
                    ['label' => 'Laporan KIB Gedung dan Bangunan',                              'route' => 'reports.sipades.kib-c',           'icon' => 'building',      'badge' => 'KIB C','color' => 'teal'],
                    ['label' => 'Laporan KIB Jalan, Irigasi dan Jaringan',                      'route' => 'reports.sipades.kib-d',           'icon' => 'route',         'badge' => 'KIB D','color' => 'teal'],
                    ['label' => 'Laporan KIB Aset Tetap Lainnya',                               'route' => 'reports.sipades.kib-e',           'icon' => 'package-2',     'badge' => 'KIB E','color' => 'teal'],
                    ['label' => 'Laporan KIB Konstruksi Dalam Pekerjaan',                       'route' => 'reports.sipades.kib-f',           'icon' => 'hard-hat',      'badge' => 'KIB F','color' => 'teal'],
                    ['label' => 'Laporan Kartu Inventaris Ruangan (KIR)',                        'route' => 'reports.sipades.kir',             'icon' => 'layout-grid',   'badge' => 'KIR',  'color' => 'purple'],
                ],
            ],
            [
                'icon'  => 'recycle',
                'color' => 'rose',
                'label' => 'Penggunaan, Pemanfaatan dan Penghapusan',
                'items' => [
                    ['label' => 'Laporan Rekap Pemanfaatan',        'route' => 'reports.sipades.pemanfaatan',      'icon' => 'trending-up', 'badge' => 'REKAP', 'color' => 'emerald'],
                    ['label' => 'Laporan Rekap Penghapusan',        'route' => 'reports.sipades.penghapusan',      'icon' => 'trash-2',     'badge' => 'REKAP', 'color' => 'rose'],
                    ['label' => 'Laporan Rekap Penghapusan Rinci',  'route' => 'reports.sipades.penghapusan-rinci','icon' => 'file-minus',  'badge' => 'RINCI', 'color' => 'rose'],
                ],
            ],
        ];

        return view('livewire.sipades-report-hub', [
            'villages'       => $villages,
            'districts'      => $districts,
            'currentVillage' => $currentVillage,
            'menus'          => $menus,
        ]);
    }
}
