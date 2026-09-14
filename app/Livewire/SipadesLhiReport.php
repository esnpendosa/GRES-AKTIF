<?php

namespace App\Livewire;

use App\Models\District;
use App\Models\Village;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class SipadesLhiReport extends Component
{
    public ?int $selectedVillageId = null;
    public string $lhiType         = 'tanah';
    public string $reportDate      = '31 December 2026';
    public string $signDate        = '12 September 2026';
    public string $sekretarisName  = 'MOHAMMAD WAHYUDI';
    public string $pengurusName    = 'PETUGAS / PENGURUS BARANG MILIK DESA';

    protected array $lhiMeta = [
        'tanah'     => ['title' => 'TANAH',                    'short' => 'LHI Tanah',      'route' => 'reports.sipades.lhi-tanah'],
        'mesin'     => ['title' => 'PERALATAN DAN MESIN',      'short' => 'LHI Mesin',      'route' => 'reports.sipades.lhi-mesin'],
        'kendaraan' => ['title' => 'KENDARAAN BERMOTOR',       'short' => 'LHI Kendaraan',  'route' => 'reports.sipades.lhi-kendaraan'],
        'gedung'    => ['title' => 'GEDUNG DAN BANGUNAN',      'short' => 'LHI Gedung',     'route' => 'reports.sipades.lhi-gedung'],
        'jalan'     => ['title' => 'JALAN, IRIGASI DAN JARINGAN', 'short' => 'LHI Jalan',  'route' => 'reports.sipades.lhi-jalan'],
        'lainnya'   => ['title' => 'ASET TETAP LAINNYA',       'short' => 'LHI Lainnya',    'route' => 'reports.sipades.lhi-lainnya'],
    ];

    public function mount(string $lhi = 'tanah', ?int $villageId = null)
    {
        $this->lhiType = strtolower($lhi);
        $user = auth()->user();
        if ($villageId) {
            $this->selectedVillageId = $villageId;
        } elseif ($user && $user->village_id) {
            $this->selectedVillageId = $user->village_id;
        } else {
            $this->selectedVillageId = Village::first()?->id ?? 1;
        }
    }

    protected function buildColumns(): array
    {
        return match($this->lhiType) {
            'tanah'     => ['No', 'Jenis/Nama Barang', 'Kode Barang', 'Reg', 'Luas (M²)', 'Alamat', 'Hak', 'No. Sertifikat', 'Asal-Usul', 'Harga (Rp)', 'Kondisi', 'Ket.'],
            'mesin'     => ['No', 'Jenis/Nama Barang', 'Kode Barang', 'Reg', 'Merk/Type', 'No. Seri', 'Tahun Beli', 'Jumlah', 'Satuan', 'Asal-Usul', 'Harga (Rp)', 'Kondisi', 'Ket.'],
            'kendaraan' => ['No', 'Jenis/Nama Barang', 'Kode Barang', 'Reg', 'Merk/Type', 'No. Polisi', 'No. BPKB', 'Tahun', 'Warna', 'Asal-Usul', 'Harga (Rp)', 'Kondisi', 'Ket.'],
            'gedung'    => ['No', 'Jenis/Nama Barang', 'Kode Barang', 'Reg', 'Alamat', 'Luas Tanah (M²)', 'Luas Bgn (M²)', 'Lantai', 'Tahun', 'Asal-Usul', 'Harga (Rp)', 'Kondisi', 'Ket.'],
            'jalan'     => ['No', 'Jenis/Nama Barang', 'Kode Barang', 'Reg', 'Alamat', 'Panjang (M)', 'Lebar (M)', 'Tahun', 'Asal-Usul', 'Harga (Rp)', 'Kondisi', 'Ket.'],
            'lainnya'   => ['No', 'Jenis/Nama Barang', 'Kode Barang', 'Reg', 'Jumlah', 'Satuan', 'Tahun Beli', 'Alamat', 'Asal-Usul', 'Harga (Rp)', 'Kondisi', 'Ket.'],
            default     => ['No', 'Jenis/Nama Barang', 'Kode Barang', 'Reg', 'Keterangan', 'Harga (Rp)', 'Kondisi'],
        };
    }

    protected function getSampleItems(string $lhi, $village): array
    {
        $vName = $village?->name ?? 'Desa';
        return match($lhi) {
            'tanah' => [
                ['no'=>1,'name'=>'Tanah Bengkok Kepala Desa','code'=>'2.01.01.01.001','reg'=>'00001','luas'=>13637,'alamat'=>"Desa {$vName}",'hak'=>'Lainnya','sertifikat'=>'-','asal'=>'Kekayaan Asli Desa','harga'=>3409250000,'kondisi'=>'Baik','ket'=>'Ganjaran Kepala Desa'],
                ['no'=>2,'name'=>'Tanah Makam Umum','code'=>'2.01.02.03.008','reg'=>'00001','luas'=>712,'alamat'=>"Desa {$vName}",'hak'=>'Lainnya','sertifikat'=>'-','asal'=>'Kekayaan Asli Desa','harga'=>178000000,'kondisi'=>'Baik','ket'=>'TKD Makam'],
                ['no'=>3,'name'=>'Tanah Sawah','code'=>'2.01.03.01.999','reg'=>'00001','luas'=>3127,'alamat'=>"Desa {$vName}",'hak'=>'Lainnya','sertifikat'=>'-','asal'=>'Kekayaan Asli Desa','harga'=>781750000,'kondisi'=>'Baik','ket'=>'TKD Sawah No.1'],
                ['no'=>4,'name'=>'Tambak','code'=>'2.01.07.01.001','reg'=>'00001','luas'=>7410,'alamat'=>"Desa {$vName}",'hak'=>'Lainnya','sertifikat'=>'-','asal'=>'Kekayaan Asli Desa','harga'=>1852500000,'kondisi'=>'Baik','ket'=>'TKD Tambak No.10'],
            ],
            'mesin' => [
                ['no'=>1,'name'=>'Meja Kerja Pimpinan','code'=>'2.02.01.01.001','reg'=>'00001','merk'=>'Lokal / Kayu','seri'=>'-','tahun'=>'2020','jumlah'=>2,'satuan'=>'Unit','asal'=>'Kekayaan Asli Desa','harga'=>2500000,'kondisi'=>'Baik','ket'=>''],
                ['no'=>2,'name'=>'Kursi Kerja','code'=>'2.02.01.02.001','reg'=>'00001','merk'=>'Chitose / Besi','seri'=>'-','tahun'=>'2021','jumlah'=>10,'satuan'=>'Unit','asal'=>'Kekayaan Asli Desa','harga'=>850000,'kondisi'=>'Baik','ket'=>''],
                ['no'=>3,'name'=>'Komputer PC All-in-One','code'=>'2.02.06.01.001','reg'=>'00001','merk'=>'Asus / PC','seri'=>'SN-2022001','tahun'=>'2022','jumlah'=>2,'satuan'=>'Unit','asal'=>'Dana Desa','harga'=>8500000,'kondisi'=>'Baik','ket'=>''],
                ['no'=>4,'name'=>'Printer Laser','code'=>'2.02.06.03.001','reg'=>'00001','merk'=>'HP LaserJet','seri'=>'HP-2022001','tahun'=>'2022','jumlah'=>1,'satuan'=>'Unit','asal'=>'Dana Desa','harga'=>2200000,'kondisi'=>'Baik','ket'=>''],
            ],
            'kendaraan' => [
                ['no'=>1,'name'=>'Sepeda Motor Dinas','code'=>'2.02.05.01.001','reg'=>'00001','merk'=>'Honda Supra','polisi'=>'W 1234 AB','bpkb'=>'BK-001','tahun'=>'2019','warna'=>'Merah','asal'=>'Kekayaan Asli Desa','harga'=>18500000,'kondisi'=>'Baik','ket'=>'Operasional Desa'],
                ['no'=>2,'name'=>'Kendaraan Roda 3 (Viar)','code'=>'2.02.05.02.001','reg'=>'00001','merk'=>'Viar Karya','polisi'=>'W 5678 CD','bpkb'=>'BK-002','tahun'=>'2021','warna'=>'Biru','asal'=>'Dana Desa','harga'=>22000000,'kondisi'=>'Baik','ket'=>'Angkutan Sampah'],
            ],
            'gedung' => [
                ['no'=>1,'name'=>'Kantor Kepala Desa','code'=>'2.03.01.01.001','reg'=>'00001','alamat'=>"Desa {$vName}",'luas_tanah'=>880,'luas_bgn'=>320,'lantai'=>1,'tahun'=>'2005','asal'=>'Kekayaan Asli Desa','harga'=>450000000,'kondisi'=>'Baik','ket'=>''],
                ['no'=>2,'name'=>'Balai Desa / Aula','code'=>'2.03.01.02.001','reg'=>'00001','alamat'=>"Desa {$vName}",'luas_tanah'=>1200,'luas_bgn'=>600,'lantai'=>1,'tahun'=>'2010','asal'=>'Kekayaan Asli Desa','harga'=>750000000,'kondisi'=>'Baik','ket'=>''],
                ['no'=>3,'name'=>'Gedung Posyandu','code'=>'2.03.01.04.001','reg'=>'00001','alamat'=>"Desa {$vName}",'luas_tanah'=>200,'luas_bgn'=>80,'lantai'=>1,'tahun'=>'2015','asal'=>'Dana Desa','harga'=>85000000,'kondisi'=>'Baik','ket'=>''],
            ],
            'jalan' => [
                ['no'=>1,'name'=>'Jalan Desa (Paving)','code'=>'2.04.01.01.001','reg'=>'00001','alamat'=>"Desa {$vName}",'panjang'=>1200,'lebar'=>3.5,'tahun'=>'2015','asal'=>'Kekayaan Asli Desa','harga'=>840000000,'kondisi'=>'Baik','ket'=>'Jalan Dusun Barat'],
                ['no'=>2,'name'=>'Saluran Irigasi Tersier','code'=>'2.04.02.01.001','reg'=>'00001','alamat'=>"Desa {$vName}",'panjang'=>800,'lebar'=>0.6,'tahun'=>'2017','asal'=>'Dana Desa','harga'=>180000000,'kondisi'=>'Baik','ket'=>'Irigasi Sawah'],
                ['no'=>3,'name'=>'Jaringan Air Bersih PAMSIMAS','code'=>'2.04.03.01.001','reg'=>'00001','alamat'=>"Desa {$vName}",'panjang'=>2500,'lebar'=>0.1,'tahun'=>'2020','asal'=>'APBN','harga'=>320000000,'kondisi'=>'Baik','ket'=>''],
            ],
            'lainnya' => [
                ['no'=>1,'name'=>'Buku Perpustakaan Desa','code'=>'2.05.01.01.001','reg'=>'00001','jumlah'=>150,'satuan'=>'Eksemplar','tahun'=>'2021','alamat'=>"Desa {$vName}",'asal'=>'Dana Desa','harga'=>7500000,'kondisi'=>'Baik','ket'=>''],
                ['no'=>2,'name'=>'Mesin Pompa Air 3 Inch','code'=>'2.05.02.01.001','reg'=>'00001','jumlah'=>2,'satuan'=>'Unit','tahun'=>'2022','alamat'=>"Desa {$vName}",'asal'=>'Dana Desa','harga'=>3200000,'kondisi'=>'Baik','ket'=>'Pertanian'],
                ['no'=>3,'name'=>'Tiang Lampu Jalan LED','code'=>'2.05.03.01.001','reg'=>'00001','jumlah'=>25,'satuan'=>'Buah','tahun'=>'2023','alamat'=>"Desa {$vName}",'asal'=>'Dana Desa','harga'=>1800000,'kondisi'=>'Baik','ket'=>'PJU'],
            ],
            default => [],
        };
    }

    public function render()
    {
        $villages = Village::with('district')->orderBy('name')->get();
        $currentVillage = Village::with('district')->find($this->selectedVillageId);
        if (!$currentVillage && $villages->isNotEmpty()) {
            $currentVillage = $villages->first();
            $this->selectedVillageId = $currentVillage->id;
        }

        $meta    = $this->lhiMeta[$this->lhiType] ?? $this->lhiMeta['tanah'];
        $columns = $this->buildColumns();
        $items   = $this->getSampleItems($this->lhiType, $currentVillage);

        // Dynamically sync real Asset records from database for this village
        if ($currentVillage) {
            if ($this->lhiType === 'tanah') {
                $dbTanah = \App\Models\Asset::where('village_id', $currentVillage->id)
                    ->whereHas('category', function($q) {
                        $q->whereIn('slug', ['tanah-kas-desa', 'tanah', 'pertanian-tambak', 'wisata-budaya']);
                    })->get();

                if ($dbTanah->isNotEmpty()) {
                    $dbItems = [];
                    foreach ($dbTanah as $i => $a) {
                        $dbItems[] = [
                            'no' => $i + 1,
                            'name' => $a->name,
                            'code' => '2.01.01.01.' . sprintf('%03d', $i + 1),
                            'reg' => sprintf('%05d', $i + 1),
                            'luas' => (float)$a->area,
                            'alamat' => $a->address ?: "Desa {$currentVillage->name}",
                            'hak' => 'Lainnya / Hak Pakai',
                            'sertifikat' => '-',
                            'asal' => 'Kekayaan Asli Desa',
                            'harga' => (float)($a->estimated_economic_value ?: ($a->area * 250000)),
                            'kondisi' => ucfirst(str_replace('_', ' ', $a->condition_label ?? 'Baik')),
                            'ket' => $a->target_activation_use ?: 'TKD Desa',
                        ];
                    }
                    $items = array_merge($dbItems, $items);
                }
            } elseif ($this->lhiType === 'gedung') {
                $dbGedung = \App\Models\Asset::where('village_id', $currentVillage->id)
                    ->whereHas('category', function($q) {
                        $q->whereIn('slug', ['bangunan-gedung', 'bangunan', 'gedung', 'pasar-desa', 'fasilitas-olahraga']);
                    })->get();

                if ($dbGedung->isNotEmpty()) {
                    $dbItems = [];
                    foreach ($dbGedung as $i => $a) {
                        $dbItems[] = [
                            'no' => $i + 1,
                            'name' => $a->name,
                            'code' => '2.03.01.01.' . sprintf('%03d', $i + 1),
                            'reg' => sprintf('%05d', $i + 1),
                            'alamat' => $a->address ?: "Desa {$currentVillage->name}",
                            'luas' => (float)$a->area,
                            'luas_bangunan' => (float)($a->area * 0.7),
                            'lantai' => 1,
                            'tahun' => '2020',
                            'asal' => 'APBDes / Swadaya Desa',
                            'harga' => (float)($a->estimated_economic_value ?: ($a->area * 1500000)),
                            'kondisi' => ucfirst(str_replace('_', ' ', $a->condition_label ?? 'Baik')),
                            'ket' => $a->target_activation_use ?: 'Gedung Desa',
                        ];
                    }
                    $items = array_merge($dbItems, $items);
                }
            }
        }

        $total   = 0;
        foreach ($items as $item) {
            $total += (float)($item['harga'] ?? 0);
        }

        $districtCode = $currentVillage?->district?->id ?? '1';
        $villageCode  = $currentVillage?->code ? substr($currentVillage->code, -4) : '2003';
        $kodeLokasi   = "35 . 25 . {$districtCode} . {$villageCode} .";

        return view('livewire.sipades-lhi-report', [
            'villages'       => $villages,
            'currentVillage' => $currentVillage,
            'meta'           => $meta,
            'columns'        => $columns,
            'items'          => $items,
            'totalPrice'     => $total,
            'kodeLokasi'     => $kodeLokasi,
            'lhiType'        => $this->lhiType,
        ]);
    }
}
