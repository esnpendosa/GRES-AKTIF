<?php

namespace App\Livewire;

use App\Models\Village;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class SipadesRekapReport extends Component
{
    public ?int $selectedVillageId = null;
    public string $rekapType       = 'pengadaan-rekap'; // pengadaan-rekap, pengadaan-dana, pemanfaatan, penghapusan, penghapusan-rinci
    public string $reportDate      = '31 December 2026';
    public string $signDate        = '12 September 2026';
    public string $sekretarisName  = 'MOHAMMAD WAHYUDI';
    public string $pengurusName    = 'PETUGAS / PENGURUS BARANG MILIK DESA';

    protected array $rekapMeta = [
        'pengadaan-rekap'   => ['title' => 'LAPORAN PENGADAAN BARANG REKAP',                          'route' => 'reports.sipades.pengadaan-rekap'],
        'pengadaan-dana'    => ['title' => 'LAPORAN PENGADAAN BARANG BERDASARKAN SUMBER DANA',         'route' => 'reports.sipades.pengadaan-dana'],
        'pemanfaatan'       => ['title' => 'LAPORAN REKAP PEMANFAATAN ASET DESA',                      'route' => 'reports.sipades.pemanfaatan'],
        'penghapusan'       => ['title' => 'LAPORAN REKAP PENGHAPUSAN ASET DESA',                      'route' => 'reports.sipades.penghapusan'],
        'penghapusan-rinci' => ['title' => 'LAPORAN REKAP PENGHAPUSAN RINCI ASET DESA',                'route' => 'reports.sipades.penghapusan-rinci'],
    ];

    public function mount(string $rekap = 'pengadaan-rekap', ?int $villageId = null)
    {
        $this->rekapType = $rekap;
        $user = auth()->user();
        if ($villageId) {
            $this->selectedVillageId = $villageId;
        } elseif ($user && $user->village_id) {
            $this->selectedVillageId = $user->village_id;
        } else {
            $this->selectedVillageId = Village::first()?->id ?? 1;
        }
    }

    protected function getSampleItems(string $rekap, $village): array
    {
        $vName = $village?->name ?? 'Desa';
        return match($rekap) {
            'pengadaan-rekap' => [
                ['no'=>1,'kode'=>'2.02.06.01.001','nama'=>'Komputer PC All-in-One','qty'=>2,'satuan'=>'Unit','harga'=>8500000,'total'=>17000000,'sumber'=>'Dana Desa','tahun'=>'2022','kondisi'=>'Baik'],
                ['no'=>2,'kode'=>'2.02.06.03.001','nama'=>'Printer Laser HP','qty'=>1,'satuan'=>'Unit','harga'=>2200000,'total'=>2200000,'sumber'=>'ADD','tahun'=>'2022','kondisi'=>'Baik'],
                ['no'=>3,'kode'=>'2.03.01.04.001','nama'=>'Gedung Posyandu','qty'=>1,'satuan'=>'Unit','harga'=>85000000,'total'=>85000000,'sumber'=>'Dana Desa','tahun'=>'2015','kondisi'=>'Baik'],
                ['no'=>4,'kode'=>'2.04.01.01.001','nama'=>'Jalan Desa (Paving)','qty'=>1,'satuan'=>'Paket','harga'=>840000000,'total'=>840000000,'sumber'=>'Dana Desa','tahun'=>'2015','kondisi'=>'Baik'],
                ['no'=>5,'kode'=>'2.05.03.01.001','nama'=>'Tiang Lampu Jalan LED','qty'=>25,'satuan'=>'Buah','harga'=>1800000,'total'=>45000000,'sumber'=>'Dana Desa','tahun'=>'2023','kondisi'=>'Baik'],
            ],
            'pengadaan-dana' => [
                ['no'=>1,'sumber'=>'Dana Desa (DD)','kode'=>'2.04.01.01.001','nama'=>'Jalan Desa (Paving)','qty'=>1,'satuan'=>'Paket','harga'=>840000000,'tahun'=>'2015','kondisi'=>'Baik'],
                ['no'=>2,'sumber'=>'Dana Desa (DD)','kode'=>'2.03.01.04.001','nama'=>'Gedung Posyandu','qty'=>1,'satuan'=>'Unit','harga'=>85000000,'tahun'=>'2015','kondisi'=>'Baik'],
                ['no'=>3,'sumber'=>'Alokasi Dana Desa (ADD)','kode'=>'2.02.06.01.001','nama'=>'Komputer PC','qty'=>2,'satuan'=>'Unit','harga'=>8500000,'tahun'=>'2022','kondisi'=>'Baik'],
                ['no'=>4,'sumber'=>'Alokasi Dana Desa (ADD)','kode'=>'2.02.06.03.001','nama'=>'Printer Laser','qty'=>1,'satuan'=>'Unit','harga'=>2200000,'tahun'=>'2022','kondisi'=>'Baik'],
                ['no'=>5,'sumber'=>'APBN','kode'=>'2.04.03.01.001','nama'=>'Jaringan Air Bersih PAMSIMAS','qty'=>1,'satuan'=>'Paket','harga'=>320000000,'tahun'=>'2020','kondisi'=>'Baik'],
            ],
            'pemanfaatan' => [
                ['no'=>1,'kode'=>'2.03.01.02.001','nama'=>'Balai Desa','pemanfaat'=>'PKK Desa','jenis'=>'Pinjam Pakai','mulai'=>'2024-01-01','selesai'=>'2026-12-31','nilai'=>0,'kondisi'=>'Baik','ket'=>'Kegiatan PKK Rutin'],
                ['no'=>2,'kode'=>'2.03.01.05.001','nama'=>'Gedung BUMDes','pemanfaat'=>'BUMDes Maju Bersama','jenis'=>'Sewa','mulai'=>'2025-01-01','selesai'=>'2025-12-31','nilai'=>6000000,'kondisi'=>'Baik','ket'=>'Sewa Tahunan'],
                ['no'=>3,'kode'=>'2.01.03.01.001','nama'=>'Sawah Desa','pemanfaat'=>'Kelompok Tani Makmur','jenis'=>'Sewa','mulai'=>'2025-01-01','selesai'=>'2025-12-31','nilai'=>12000000,'kondisi'=>'Baik','ket'=>'Pertanian Padi'],
            ],
            'penghapusan' => [
                ['no'=>1,'kode'=>'2.02.01.01.001','nama'=>'Meja Kerja Lama','reg'=>'00001','qty'=>2,'satuan'=>'Unit','harga_buku'=>800000,'alasan'=>'Rusak Berat','sk_no'=>'001/SK/2025','sk_tgl'=>'2025-03-15','ket'=>'Diganti baru'],
                ['no'=>2,'kode'=>'2.02.06.01.002','nama'=>'Komputer PC Lama','reg'=>'00002','qty'=>1,'satuan'=>'Unit','harga_buku'=>5000000,'alasan'=>'Sudah Tua/Usang','sk_no'=>'002/SK/2025','sk_tgl'=>'2025-06-01','ket'=>'Diganti PC baru'],
            ],
            'penghapusan-rinci' => [
                ['no'=>1,'kode'=>'2.02.01.01.001','nama'=>'Meja Kerja Lama','reg'=>'00001','merk'=>'Lokal','qty'=>2,'satuan'=>'Unit','tahun'=>'2010','harga_buku'=>800000,'metode'=>'Lelang','nilai_lelang'=>150000,'sk_no'=>'001/SK/2025','sk_tgl'=>'2025-03-15','ket'=>'Rusak Berat - Penyusutan'],
                ['no'=>2,'kode'=>'2.02.06.01.002','nama'=>'Komputer PC Lama','reg'=>'00002','merk'=>'Axioo','qty'=>1,'satuan'=>'Unit','tahun'=>'2012','harga_buku'=>5000000,'metode'=>'Pemusnahan','nilai_lelang'=>0,'sk_no'=>'002/SK/2025','sk_tgl'=>'2025-06-01','ket'=>'Sudah Tua/Usang'],
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

        $meta  = $this->rekapMeta[$this->rekapType] ?? $this->rekapMeta['pengadaan-rekap'];
        $items = $this->getSampleItems($this->rekapType, $currentVillage);
        $total = 0;
        foreach ($items as $item) {
            $total += (float)($item['total'] ?? $item['harga'] ?? $item['nilai_lelang'] ?? $item['nilai'] ?? 0);
        }

        $districtCode = $currentVillage?->district?->id ?? '1';
        $villageCode  = $currentVillage?->code ? substr($currentVillage->code, -4) : '2003';
        $kodeLokasi   = "35 . 25 . {$districtCode} . {$villageCode} .";

        return view('livewire.sipades-rekap-report', [
            'villages'       => $villages,
            'currentVillage' => $currentVillage,
            'meta'           => $meta,
            'items'          => $items,
            'totalPrice'     => $total,
            'kodeLokasi'     => $kodeLokasi,
            'rekapType'      => $this->rekapType,
        ]);
    }
}
