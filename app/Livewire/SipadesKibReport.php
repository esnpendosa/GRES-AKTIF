<?php

namespace App\Livewire;

use App\Models\District;
use App\Models\Village;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class SipadesKibReport extends Component
{
    public ?int $selectedVillageId = null;
    public string $kibType         = 'b'; // b,c,d,e,f,kir
    public string $reportDate      = '31 December 2026';
    public string $signDate        = '12 September 2026';
    public string $sekretarisName  = 'MOHAMMAD WAHYUDI';
    public string $pengurusName    = 'PETUGAS / PENGURUS BARANG MILIK DESA';

    // KIB type metadata
    protected array $kibMeta = [
        'b'   => ['title' => 'PERALATAN DAN MESIN',             'short' => 'KIB B', 'route' => 'reports.sipades.kib-b'],
        'c'   => ['title' => 'GEDUNG DAN BANGUNAN',             'short' => 'KIB C', 'route' => 'reports.sipades.kib-c'],
        'd'   => ['title' => 'JALAN, IRIGASI DAN JARINGAN',     'short' => 'KIB D', 'route' => 'reports.sipades.kib-d'],
        'e'   => ['title' => 'ASET TETAP LAINNYA',              'short' => 'KIB E', 'route' => 'reports.sipades.kib-e'],
        'f'   => ['title' => 'KONSTRUKSI DALAM PEKERJAAN',      'short' => 'KIB F', 'route' => 'reports.sipades.kib-f'],
        'kir' => ['title' => 'KARTU INVENTARIS RUANGAN (KIR)',  'short' => 'KIR',   'route' => 'reports.sipades.kir'],
    ];

    public function mount(string $kib = 'b', ?int $villageId = null)
    {
        $this->kibType = strtolower($kib);
        $user = auth()->user();
        if ($villageId) {
            $this->selectedVillageId = $villageId;
        } elseif ($user && $user->village_id) {
            $this->selectedVillageId = $user->village_id;
        } else {
            $this->selectedVillageId = Village::first()?->id ?? 1;
        }
    }

    protected function getSampleItems(string $kib, $village): array
    {
        $vName = $village?->name ?? 'Desa';
        return match($kib) {
            'b' => [
                ['name' => 'Meja Kerja Pimpinan',      'code' => '2 . 02 . 01 . 01 . 001 .', 'reg' => '00001', 'merk' => 'Lokal',    'type' => 'Kayu',   'qty' => 2,   'unit' => 'Unit', 'year_buy' => '2020', 'price' => 2500000,   'condition' => 'Baik',   'notes' => 'Inventaris Kantor Desa', 'address' => "Kantor Desa {$vName}"],
                ['name' => 'Kursi Kerja',               'code' => '2 . 02 . 01 . 02 . 001 .', 'reg' => '00001', 'merk' => 'Chitose',  'type' => 'Besi',   'qty' => 10,  'unit' => 'Unit', 'year_buy' => '2021', 'price' => 850000,    'condition' => 'Baik',   'notes' => 'Inventaris Kantor Desa', 'address' => "Kantor Desa {$vName}"],
                ['name' => 'Komputer PC All-in-One',    'code' => '2 . 02 . 06 . 01 . 001 .', 'reg' => '00001', 'merk' => 'Asus',     'type' => 'PC',     'qty' => 2,   'unit' => 'Unit', 'year_buy' => '2022', 'price' => 8500000,   'condition' => 'Baik',   'notes' => 'Inventaris Kantor Desa', 'address' => "Kantor Desa {$vName}"],
                ['name' => 'Printer Laser',             'code' => '2 . 02 . 06 . 03 . 001 .', 'reg' => '00001', 'merk' => 'HP',       'type' => 'Laser',  'qty' => 1,   'unit' => 'Unit', 'year_buy' => '2022', 'price' => 2200000,   'condition' => 'Baik',   'notes' => 'Inventaris Kantor Desa', 'address' => "Kantor Desa {$vName}"],
                ['name' => 'Genset Listrik 5000 Watt',  'code' => '2 . 02 . 08 . 01 . 001 .', 'reg' => '00001', 'merk' => 'Yamaha',   'type' => 'Diesel', 'qty' => 1,   'unit' => 'Unit', 'year_buy' => '2019', 'price' => 12000000,  'condition' => 'Baik',   'notes' => 'Cadangan Listrik',       'address' => "Kantor Desa {$vName}"],
            ],
            'c' => [
                ['name' => 'Kantor Kepala Desa',        'code' => '2 . 03 . 01 . 01 . 001 .', 'reg' => '00001', 'address' => "Desa {$vName}", 'area_land' => 880,  'area_build' => 320,  'floors' => 1, 'year_build' => '2005', 'price' => 450000000,  'condition' => 'Baik',   'notes' => 'Gedung Pemerintah Desa'],
                ['name' => 'Balai Desa / Aula Desa',   'code' => '2 . 03 . 01 . 02 . 001 .', 'reg' => '00001', 'address' => "Desa {$vName}", 'area_land' => 1200, 'area_build' => 600,  'floors' => 1, 'year_build' => '2010', 'price' => 750000000,  'condition' => 'Baik',   'notes' => 'Fasilitas Pertemuan'],
                ['name' => 'Gedung Posyandu',           'code' => '2 . 03 . 01 . 04 . 001 .', 'reg' => '00001', 'address' => "Desa {$vName}", 'area_land' => 200,  'area_build' => 80,   'floors' => 1, 'year_build' => '2015', 'price' => 85000000,   'condition' => 'Baik',   'notes' => 'Fasilitas Kesehatan'],
                ['name' => 'Gedung BUMDes',             'code' => '2 . 03 . 01 . 05 . 001 .', 'reg' => '00001', 'address' => "Desa {$vName}", 'area_land' => 300,  'area_build' => 150,  'floors' => 1, 'year_build' => '2018', 'price' => 200000000,  'condition' => 'Baik',   'notes' => 'Unit Usaha BUMDes'],
            ],
            'd' => [
                ['name' => 'Jalan Desa (Paving)',               'code' => '2 . 04 . 01 . 01 . 001 .', 'reg' => '00001', 'address' => "Desa {$vName}", 'length' => 1200,  'width' => 3.5, 'year_build' => '2015', 'price' => 840000000,  'condition' => 'Baik',   'notes' => 'Jalan Dusun Barat'],
                ['name' => 'Jalan Rabat Beton',                 'code' => '2 . 04 . 01 . 01 . 002 .', 'reg' => '00001', 'address' => "Desa {$vName}", 'length' => 500,   'width' => 3.0, 'year_build' => '2018', 'price' => 275000000,  'condition' => 'Baik',   'notes' => 'Jalan Akses Pertanian'],
                ['name' => 'Saluran Irigasi Tersier',           'code' => '2 . 04 . 02 . 01 . 001 .', 'reg' => '00001', 'address' => "Desa {$vName}", 'length' => 800,   'width' => 0.6, 'year_build' => '2017', 'price' => 180000000,  'condition' => 'Baik',   'notes' => 'Irigasi Sawah'],
                ['name' => 'Jaringan Air Bersih Desa',          'code' => '2 . 04 . 03 . 01 . 001 .', 'reg' => '00001', 'address' => "Desa {$vName}", 'length' => 2500,  'width' => 0.1, 'year_build' => '2020', 'price' => 320000000,  'condition' => 'Baik',   'notes' => 'PAMSIMAS Desa'],
            ],
            'e' => [
                ['name' => 'Buku Perpustakaan Desa',      'code' => '2 . 05 . 01 . 01 . 001 .', 'reg' => '00001', 'qty' => 150,  'unit' => 'Eksemplar', 'year_buy' => '2021', 'price' => 7500000,   'condition' => 'Baik', 'notes' => 'Perpustakaan Desa', 'address' => "Desa {$vName}"],
                ['name' => 'Mesin Pompa Air 3 Inch',      'code' => '2 . 05 . 02 . 01 . 001 .', 'reg' => '00001', 'qty' => 2,    'unit' => 'Unit',       'year_buy' => '2022', 'price' => 3200000,   'condition' => 'Baik', 'notes' => 'Pertanian Desa',    'address' => "Desa {$vName}"],
                ['name' => 'Tiang Lampu Jalan LED',        'code' => '2 . 05 . 03 . 01 . 001 .', 'reg' => '00001', 'qty' => 25,   'unit' => 'Buah',       'year_buy' => '2023', 'price' => 1800000,   'condition' => 'Baik', 'notes' => 'PJU Jalan Desa',    'address' => "Desa {$vName}"],
            ],
            'f' => [
                ['name' => 'Pembangunan Drainase Desa (On Progress)',     'code' => '2 . 06 . 01 . 01 . 001 .', 'reg' => '00001', 'address' => "Desa {$vName}", 'contractor' => 'CV. Maju Jaya',     'contract_no' => '001/SPK/2026', 'value' => 450000000,  'start' => '2026-01-15', 'finish' => '2026-12-31', 'progress' => 45, 'notes' => 'Dana ADD 2026'],
                ['name' => 'Renovasi Balai Desa (Tahap II)',              'code' => '2 . 06 . 01 . 02 . 001 .', 'reg' => '00001', 'address' => "Desa {$vName}", 'contractor' => 'CV. Karya Mandiri', 'contract_no' => '002/SPK/2026', 'value' => 220000000,  'start' => '2026-03-01', 'finish' => '2026-09-30', 'progress' => 70, 'notes' => 'Dana DD 2026'],
            ],
            'kir' => [
                ['room' => 'Ruang Kepala Desa',  'items' => [
                    ['name' => 'Meja Kerja Pimpinan', 'code' => '2.02.01.01.001', 'qty' => 1, 'condition' => 'Baik', 'notes' => ''],
                    ['name' => 'Kursi Kerja',          'code' => '2.02.01.02.001', 'qty' => 2, 'condition' => 'Baik', 'notes' => ''],
                    ['name' => 'Lemari Arsip',         'code' => '2.02.01.03.001', 'qty' => 1, 'condition' => 'Baik', 'notes' => ''],
                    ['name' => 'AC 1 PK',              'code' => '2.02.07.01.001', 'qty' => 1, 'condition' => 'Baik', 'notes' => ''],
                ]],
                ['room' => 'Ruang Sekretaris Desa', 'items' => [
                    ['name' => 'Meja Kerja Staf',  'code' => '2.02.01.01.002', 'qty' => 4, 'condition' => 'Baik', 'notes' => ''],
                    ['name' => 'Komputer PC',       'code' => '2.02.06.01.001', 'qty' => 2, 'condition' => 'Baik', 'notes' => ''],
                    ['name' => 'Printer',           'code' => '2.02.06.03.001', 'qty' => 1, 'condition' => 'Baik', 'notes' => ''],
                ]],
                ['room' => 'Ruang Pelayanan', 'items' => [
                    ['name' => 'Kursi Tunggu',   'code' => '2.02.01.02.003', 'qty' => 6,  'condition' => 'Baik', 'notes' => ''],
                    ['name' => 'Meja Pelayanan', 'code' => '2.02.01.01.003', 'qty' => 2,  'condition' => 'Baik', 'notes' => ''],
                    ['name' => 'TV 40 Inch',     'code' => '2.02.07.03.001', 'qty' => 1,  'condition' => 'Baik', 'notes' => 'Informasi Publik'],
                ]],
            ],
            default => [],
        };
    }

    public function render()
    {
        $villages = Village::with('district')->orderBy('name')->get();

        $currentVillage = Village::with('district')->find($this->selectedVillageId);
        if (!$currentVillage && $villages->isNotEmpty()) {
            $currentVillage          = $villages->first();
            $this->selectedVillageId = $currentVillage->id;
        }

        $meta   = $this->kibMeta[$this->kibType] ?? $this->kibMeta['b'];
        $items  = $this->getSampleItems($this->kibType, $currentVillage);

        // Dynamically sync real Asset records from database for this village
        if ($currentVillage) {
            if ($this->kibType === 'c') {
                $dbGedung = \App\Models\Asset::where('village_id', $currentVillage->id)
                    ->whereHas('category', function($q) {
                        $q->whereIn('slug', ['bangunan-gedung', 'bangunan', 'gedung', 'pasar-desa', 'fasilitas-olahraga', 'pendidikan-pelatihan']);
                    })->get();

                if ($dbGedung->isNotEmpty()) {
                    $dbItems = [];
                    foreach ($dbGedung as $i => $a) {
                        $dbItems[] = [
                            'name' => $a->name,
                            'code' => '2 . 03 . 01 . 0' . min(9, $i + 1) . ' . 001 .',
                            'reg' => sprintf('%05d', $i + 1),
                            'address' => $a->address ?: "Desa {$currentVillage->name}",
                            'area_land' => (float)$a->area,
                            'area_build' => (float)($a->area * 0.7),
                            'floors' => 1,
                            'year_build' => '2020',
                            'price' => (float)($a->estimated_economic_value ?: ($a->area * 1500000)),
                            'condition' => ucfirst(str_replace('_', ' ', $a->condition_label ?? 'Baik')),
                            'notes' => $a->target_activation_use ?: 'Aset Milik Desa',
                        ];
                    }
                    $items = array_merge($dbItems, $items);
                }
            } elseif ($this->kibType === 'd') {
                $dbJalan = \App\Models\Asset::where('village_id', $currentVillage->id)
                    ->whereHas('category', function($q) {
                        $q->whereIn('slug', ['infrastruktur-sarana', 'jalan', 'irigasi', 'jembatan']);
                    })->get();

                if ($dbJalan->isNotEmpty()) {
                    $dbItems = [];
                    foreach ($dbJalan as $i => $a) {
                        $dbItems[] = [
                            'name' => $a->name,
                            'code' => '2 . 04 . 01 . 01 . ' . sprintf('%03d', $i + 1) . ' .',
                            'reg' => sprintf('%05d', $i + 1),
                            'address' => $a->address ?: "Desa {$currentVillage->name}",
                            'length' => (float)($a->area > 0 ? $a->area : 500),
                            'width' => 3.5,
                            'year_build' => '2019',
                            'price' => (float)($a->estimated_economic_value ?: 350000000),
                            'condition' => ucfirst(str_replace('_', ' ', $a->condition_label ?? 'Baik')),
                            'notes' => $a->target_activation_use ?: 'Infrastruktur Desa',
                        ];
                    }
                    $items = array_merge($dbItems, $items);
                }
            }
        }

        $total  = 0;
        if ($this->kibType !== 'kir') {
            foreach ($items as $item) {
                $total += (float)($item['price'] ?? $item['value'] ?? 0);
            }
        }

        $districtCode = $currentVillage?->district?->id ?? '1';
        $villageCode  = $currentVillage?->code ? substr($currentVillage->code, -4) : '2003';
        $kodeLokasi   = "35 . 25 . {$districtCode} . {$villageCode} .";

        return view('livewire.sipades-kib-report', [
            'villages'       => $villages,
            'currentVillage' => $currentVillage,
            'meta'           => $meta,
            'items'          => $items,
            'totalPrice'     => $total,
            'kodeLokasi'     => $kodeLokasi,
            'kibType'        => $this->kibType,
        ]);
    }
}
