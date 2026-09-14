<div class="py-6 bg-slate-200/80 min-h-screen">

    {{-- Toolbar --}}
    <div class="max-w-[1280px] mx-auto px-4 sm:px-6 mb-6 print:hidden">
        <div class="bg-white rounded-xl border border-slate-300 p-4 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('reports.sipades') }}" class="p-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                </a>
                <div>
                    <h2 class="font-bold text-slate-900 text-sm sm:text-base flex items-center gap-2">
                        <span>Laporan Hasil Inventarisasi (LHI) {{ $meta['title'] }}</span>
                        <span class="text-[10px] px-2 py-0.5 rounded bg-amber-100 text-amber-800 font-bold border border-amber-300">LHI &bull; SIPADES R3</span>
                    </h2>
                    <p class="text-xs text-slate-500">Standar Permendagri &amp; Pengelolaan Aset Desa Kabupaten Gresik</p>
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <div class="flex items-center gap-1.5 bg-slate-50 border border-slate-200 rounded-lg px-2.5 py-1">
                    <span class="text-[11px] text-slate-500 font-semibold">Desa:</span>
                    <select wire:model.live="selectedVillageId" class="bg-transparent text-xs font-bold text-slate-800 focus:outline-none cursor-pointer">
                        @foreach($villages as $v)
                            <option value="{{ $v->id }}">{{ $v->name }} ({{ $v->district->name ?? '' }})</option>
                        @endforeach
                    </select>
                </div>
                <button onclick="window.print()" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-[#008080] hover:bg-[#006666] text-white text-xs font-bold shadow transition-colors cursor-pointer">
                    <i data-lucide="printer" class="w-3.5 h-3.5"></i>
                    <span>Cetak / Simpan PDF</span>
                </button>
            </div>
        </div>
    </div>

    {{-- Document --}}
    <div class="max-w-[1280px] mx-auto px-2 sm:px-6 space-y-8 print:space-y-0 print:p-0 print:max-w-none select-text">
        <div class="page-sheet bg-white p-6 sm:p-10 shadow-2xl print:shadow-none border border-slate-300 print:border-none text-black font-sans text-[10.5px] leading-snug flex flex-col justify-between relative">
            <div>
                {{-- Header --}}
                <div class="text-center mb-6">
                    <h1 class="text-base font-bold uppercase tracking-wider">LAPORAN HASIL INVENTARISASI (LHI)</h1>
                    <h2 class="text-sm font-bold uppercase">{{ $meta['title'] }}</h2>
                    <p class="text-[10.5px] mt-1">Periode Tanggal, {{ $reportDate }} &mdash; Kabupaten Gresik, Jawa Timur</p>
                </div>

                {{-- Location --}}
                <div class="mb-4 text-[10.5px]">
                    <table class="border-collapse text-left">
                        <tbody>
                            <tr><td class="font-bold pr-3 w-28">Kecamatan</td><td class="pr-2">:</td><td class="font-bold">{{ strtoupper($currentVillage?->district?->name ?? '-') }}</td></tr>
                            <tr><td class="font-bold pr-3">Desa</td><td class="pr-2">:</td><td class="font-bold">{{ strtoupper($currentVillage?->name ?? '-') }}</td></tr>
                            <tr><td class="font-bold pr-3">No. Kode Lokasi</td><td class="pr-2">:</td><td class="font-bold">{{ $kodeLokasi }}</td></tr>
                        </tbody>
                    </table>
                </div>

                {{-- Dynamic table per LHI type --}}
                <div class="overflow-x-auto print:overflow-visible">
                    {{-- TANAH --}}
                    @if($lhiType === 'tanah')
                    <table class="w-full border-collapse border border-black text-[9px] leading-tight text-black">
                        <thead><tr class="text-center font-bold">
                            <th class="border border-black p-1">NO</th><th class="border border-black p-1">JENIS/NAMA BARANG</th><th class="border border-black p-1">KODE</th><th class="border border-black p-1">REG</th>
                            <th class="border border-black p-1">LUAS (M²)</th><th class="border border-black p-1">ALAMAT</th><th class="border border-black p-1">HAK</th>
                            <th class="border border-black p-1">NO. SERTIFIKAT</th><th class="border border-black p-1">ASAL-USUL</th><th class="border border-black p-1">HARGA (Rp)</th><th class="border border-black p-1">KONDISI</th><th class="border border-black p-1">KET.</th>
                        </tr></thead>
                        <tbody>
                        @forelse($items as $item)
                        <tr>
                            <td class="border border-black p-1 text-center">{{ $item['no'] }}</td>
                            <td class="border border-black p-1 font-medium">{{ $item['name'] }}</td>
                            <td class="border border-black p-1 text-center whitespace-nowrap">{{ $item['code'] }}</td>
                            <td class="border border-black p-1 text-center">{{ $item['reg'] }}</td>
                            <td class="border border-black p-1 text-right">{{ number_format($item['luas'],2) }}</td>
                            <td class="border border-black p-1">{{ $item['alamat'] }}</td>
                            <td class="border border-black p-1 text-center">{{ $item['hak'] }}</td>
                            <td class="border border-black p-1 text-center">{{ $item['sertifikat'] }}</td>
                            <td class="border border-black p-1">{{ $item['asal'] }}</td>
                            <td class="border border-black p-1 text-right whitespace-nowrap">{{ number_format($item['harga'],2) }}</td>
                            <td class="border border-black p-1 text-center">{{ $item['kondisi'] }}</td>
                            <td class="border border-black p-1">{{ $item['ket'] }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="12" class="border border-black p-3 text-center italic text-slate-500">Tidak ada data.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                    @endif

                    {{-- MESIN --}}
                    @if($lhiType === 'mesin')
                    <table class="w-full border-collapse border border-black text-[9px] leading-tight text-black">
                        <thead><tr class="text-center font-bold">
                            <th class="border border-black p-1">NO</th><th class="border border-black p-1">JENIS/NAMA BARANG</th><th class="border border-black p-1">KODE</th><th class="border border-black p-1">REG</th>
                            <th class="border border-black p-1">MERK/TYPE</th><th class="border border-black p-1">NO. SERI</th><th class="border border-black p-1">TAHUN</th><th class="border border-black p-1">JUMLAH</th>
                            <th class="border border-black p-1">SATUAN</th><th class="border border-black p-1">ASAL-USUL</th><th class="border border-black p-1">HARGA (Rp)</th><th class="border border-black p-1">KONDISI</th><th class="border border-black p-1">KET.</th>
                        </tr></thead>
                        <tbody>
                        @forelse($items as $item)
                        <tr>
                            <td class="border border-black p-1 text-center">{{ $item['no'] }}</td>
                            <td class="border border-black p-1 font-medium">{{ $item['name'] }}</td>
                            <td class="border border-black p-1 text-center whitespace-nowrap">{{ $item['code'] }}</td>
                            <td class="border border-black p-1 text-center">{{ $item['reg'] }}</td>
                            <td class="border border-black p-1">{{ $item['merk'] }}</td>
                            <td class="border border-black p-1 text-center">{{ $item['seri'] }}</td>
                            <td class="border border-black p-1 text-center">{{ $item['tahun'] }}</td>
                            <td class="border border-black p-1 text-right">{{ $item['jumlah'] }}</td>
                            <td class="border border-black p-1 text-center">{{ $item['satuan'] }}</td>
                            <td class="border border-black p-1">{{ $item['asal'] }}</td>
                            <td class="border border-black p-1 text-right whitespace-nowrap">{{ number_format($item['harga'],2) }}</td>
                            <td class="border border-black p-1 text-center">{{ $item['kondisi'] }}</td>
                            <td class="border border-black p-1">{{ $item['ket'] }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="13" class="border border-black p-3 text-center italic text-slate-500">Tidak ada data.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                    @endif

                    {{-- KENDARAAN --}}
                    @if($lhiType === 'kendaraan')
                    <table class="w-full border-collapse border border-black text-[9px] leading-tight text-black">
                        <thead><tr class="text-center font-bold">
                            <th class="border border-black p-1">NO</th><th class="border border-black p-1">JENIS/NAMA</th><th class="border border-black p-1">KODE</th><th class="border border-black p-1">REG</th>
                            <th class="border border-black p-1">MERK/TYPE</th><th class="border border-black p-1">NO. POLISI</th><th class="border border-black p-1">NO. BPKB</th><th class="border border-black p-1">TAHUN</th>
                            <th class="border border-black p-1">WARNA</th><th class="border border-black p-1">ASAL-USUL</th><th class="border border-black p-1">HARGA (Rp)</th><th class="border border-black p-1">KONDISI</th><th class="border border-black p-1">KET.</th>
                        </tr></thead>
                        <tbody>
                        @forelse($items as $item)
                        <tr>
                            <td class="border border-black p-1 text-center">{{ $item['no'] }}</td>
                            <td class="border border-black p-1 font-medium">{{ $item['name'] }}</td>
                            <td class="border border-black p-1 text-center whitespace-nowrap">{{ $item['code'] }}</td>
                            <td class="border border-black p-1 text-center">{{ $item['reg'] }}</td>
                            <td class="border border-black p-1">{{ $item['merk'] }}</td>
                            <td class="border border-black p-1 text-center">{{ $item['polisi'] }}</td>
                            <td class="border border-black p-1 text-center">{{ $item['bpkb'] }}</td>
                            <td class="border border-black p-1 text-center">{{ $item['tahun'] }}</td>
                            <td class="border border-black p-1 text-center">{{ $item['warna'] }}</td>
                            <td class="border border-black p-1">{{ $item['asal'] }}</td>
                            <td class="border border-black p-1 text-right whitespace-nowrap">{{ number_format($item['harga'],2) }}</td>
                            <td class="border border-black p-1 text-center">{{ $item['kondisi'] }}</td>
                            <td class="border border-black p-1">{{ $item['ket'] }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="13" class="border border-black p-3 text-center italic text-slate-500">Tidak ada data.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                    @endif

                    {{-- GEDUNG --}}
                    @if($lhiType === 'gedung')
                    <table class="w-full border-collapse border border-black text-[9px] leading-tight text-black">
                        <thead><tr class="text-center font-bold">
                            <th class="border border-black p-1">NO</th><th class="border border-black p-1">JENIS/NAMA</th><th class="border border-black p-1">KODE</th><th class="border border-black p-1">REG</th>
                            <th class="border border-black p-1">ALAMAT</th><th class="border border-black p-1">LUAS TANAH (M²)</th><th class="border border-black p-1">LUAS BGN (M²)</th>
                            <th class="border border-black p-1">LANTAI</th><th class="border border-black p-1">TAHUN</th><th class="border border-black p-1">ASAL-USUL</th><th class="border border-black p-1">HARGA (Rp)</th><th class="border border-black p-1">KONDISI</th><th class="border border-black p-1">KET.</th>
                        </tr></thead>
                        <tbody>
                        @forelse($items as $item)
                        <tr>
                            <td class="border border-black p-1 text-center">{{ $item['no'] }}</td>
                            <td class="border border-black p-1 font-medium">{{ $item['name'] }}</td>
                            <td class="border border-black p-1 text-center whitespace-nowrap">{{ $item['code'] }}</td>
                            <td class="border border-black p-1 text-center">{{ $item['reg'] }}</td>
                            <td class="border border-black p-1">{{ $item['alamat'] }}</td>
                            <td class="border border-black p-1 text-right">{{ number_format($item['luas_tanah'],2) }}</td>
                            <td class="border border-black p-1 text-right">{{ number_format($item['luas_bgn'],2) }}</td>
                            <td class="border border-black p-1 text-center">{{ $item['lantai'] }}</td>
                            <td class="border border-black p-1 text-center">{{ $item['tahun'] }}</td>
                            <td class="border border-black p-1">{{ $item['asal'] }}</td>
                            <td class="border border-black p-1 text-right whitespace-nowrap">{{ number_format($item['harga'],2) }}</td>
                            <td class="border border-black p-1 text-center">{{ $item['kondisi'] }}</td>
                            <td class="border border-black p-1">{{ $item['ket'] }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="13" class="border border-black p-3 text-center italic text-slate-500">Tidak ada data.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                    @endif

                    {{-- JALAN --}}
                    @if($lhiType === 'jalan')
                    <table class="w-full border-collapse border border-black text-[9px] leading-tight text-black">
                        <thead><tr class="text-center font-bold">
                            <th class="border border-black p-1">NO</th><th class="border border-black p-1">JENIS/NAMA</th><th class="border border-black p-1">KODE</th><th class="border border-black p-1">REG</th>
                            <th class="border border-black p-1">ALAMAT</th><th class="border border-black p-1">PANJANG (M)</th><th class="border border-black p-1">LEBAR (M)</th>
                            <th class="border border-black p-1">TAHUN</th><th class="border border-black p-1">ASAL-USUL</th><th class="border border-black p-1">HARGA (Rp)</th><th class="border border-black p-1">KONDISI</th><th class="border border-black p-1">KET.</th>
                        </tr></thead>
                        <tbody>
                        @forelse($items as $item)
                        <tr>
                            <td class="border border-black p-1 text-center">{{ $item['no'] }}</td>
                            <td class="border border-black p-1 font-medium">{{ $item['name'] }}</td>
                            <td class="border border-black p-1 text-center whitespace-nowrap">{{ $item['code'] }}</td>
                            <td class="border border-black p-1 text-center">{{ $item['reg'] }}</td>
                            <td class="border border-black p-1">{{ $item['alamat'] }}</td>
                            <td class="border border-black p-1 text-right">{{ number_format($item['panjang'],2) }}</td>
                            <td class="border border-black p-1 text-right">{{ number_format($item['lebar'],2) }}</td>
                            <td class="border border-black p-1 text-center">{{ $item['tahun'] }}</td>
                            <td class="border border-black p-1">{{ $item['asal'] }}</td>
                            <td class="border border-black p-1 text-right whitespace-nowrap">{{ number_format($item['harga'],2) }}</td>
                            <td class="border border-black p-1 text-center">{{ $item['kondisi'] }}</td>
                            <td class="border border-black p-1">{{ $item['ket'] }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="12" class="border border-black p-3 text-center italic text-slate-500">Tidak ada data.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                    @endif

                    {{-- LAINNYA --}}
                    @if($lhiType === 'lainnya')
                    <table class="w-full border-collapse border border-black text-[9px] leading-tight text-black">
                        <thead><tr class="text-center font-bold">
                            <th class="border border-black p-1">NO</th><th class="border border-black p-1">JENIS/NAMA BARANG</th><th class="border border-black p-1">KODE</th><th class="border border-black p-1">REG</th>
                            <th class="border border-black p-1">JUMLAH</th><th class="border border-black p-1">SATUAN</th><th class="border border-black p-1">TAHUN</th><th class="border border-black p-1">ALAMAT</th>
                            <th class="border border-black p-1">ASAL-USUL</th><th class="border border-black p-1">HARGA (Rp)</th><th class="border border-black p-1">KONDISI</th><th class="border border-black p-1">KET.</th>
                        </tr></thead>
                        <tbody>
                        @forelse($items as $item)
                        <tr>
                            <td class="border border-black p-1 text-center">{{ $item['no'] }}</td>
                            <td class="border border-black p-1 font-medium">{{ $item['name'] }}</td>
                            <td class="border border-black p-1 text-center whitespace-nowrap">{{ $item['code'] }}</td>
                            <td class="border border-black p-1 text-center">{{ $item['reg'] }}</td>
                            <td class="border border-black p-1 text-right">{{ $item['jumlah'] }}</td>
                            <td class="border border-black p-1 text-center">{{ $item['satuan'] }}</td>
                            <td class="border border-black p-1 text-center">{{ $item['tahun'] }}</td>
                            <td class="border border-black p-1">{{ $item['alamat'] }}</td>
                            <td class="border border-black p-1">{{ $item['asal'] }}</td>
                            <td class="border border-black p-1 text-right whitespace-nowrap">{{ number_format($item['harga'],2) }}</td>
                            <td class="border border-black p-1 text-center">{{ $item['kondisi'] }}</td>
                            <td class="border border-black p-1">{{ $item['ket'] }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="12" class="border border-black p-3 text-center italic text-slate-500">Tidak ada data.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                    @endif
                </div>

                {{-- Jumlah --}}
                <div class="mt-4 text-right text-[10.5px] font-bold text-black border-t border-black pt-2">
                    Jumlah Nilai : Rp {{ number_format($totalPrice, 2) }}
                </div>

                {{-- Signatures --}}
                <div class="mt-10 grid grid-cols-2 gap-12 text-[10.5px] text-black">
                    <div class="text-center space-y-1">
                        <p class="font-bold">MENGETAHUI</p>
                        <p class="font-bold">SEKRETARIS DESA</p>
                        <div class="h-20"></div>
                        <p class="font-bold underline uppercase">{{ $sekretarisName }}</p>
                    </div>
                    <div class="text-center space-y-1">
                        <p>{{ $signDate }}</p>
                        <p class="font-bold uppercase">PETUGAS / PENGURUS BARANG MILIK DESA</p>
                        <div class="h-20"></div>
                        <p class="font-bold underline uppercase">{{ $pengurusName }}</p>
                    </div>
                </div>
            </div>

            <div class="mt-6 pt-1.5 flex items-center justify-between text-[8.5px] text-black border-t border-black/20">
                <p>Printed By SIPADES R3 - Laporan Hasil Inventarisasi (LHI) {{ $meta['title'] }}</p>
                <p>Halaman : 1 dari 1</p>
            </div>
        </div>
    </div>

    <style>
    @media print {
        @page { size: landscape; margin: 6mm 8mm; }
        html, body { background: #ffffff !important; color: #000000 !important; margin: 0 !important; }
        header, footer, nav, .print\:hidden { display: none !important; }
        .page-sheet { box-shadow: none !important; border: none !important; padding: 0 !important; margin: 0 !important; width: 100% !important; page-break-inside: avoid !important; }
        table { border-collapse: collapse !important; }
        th, td { border: 1px solid #000000 !important; color: #000000 !important; }
    }
    </style>
</div>
