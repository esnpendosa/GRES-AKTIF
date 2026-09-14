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
                        <span>{{ $meta['title'] }}</span>
                        <span class="text-[10px] px-2 py-0.5 rounded bg-blue-100 text-blue-800 font-bold border border-blue-300">SIPADES R3</span>
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
    <div class="max-w-[1280px] mx-auto px-2 sm:px-6 print:p-0 print:max-w-none select-text">
        <div class="page-sheet bg-white p-6 sm:p-10 shadow-2xl print:shadow-none border border-slate-300 print:border-none text-black font-sans text-[10.5px] leading-snug flex flex-col justify-between">
            <div>
                <div class="text-center mb-6">
                    <h1 class="text-base font-bold uppercase tracking-wider">{{ $meta['title'] }}</h1>
                    <p class="text-[10.5px] mt-1">Periode Tanggal, {{ $reportDate }} &mdash; Kabupaten Gresik, Jawa Timur</p>
                    <div class="mt-2 text-[10.5px] text-left inline-block">
                        <table class="border-collapse">
                            <tr><td class="font-bold pr-3">Kecamatan</td><td class="pr-2">:</td><td class="font-bold">{{ strtoupper($currentVillage?->district?->name ?? '-') }}</td></tr>
                            <tr><td class="font-bold pr-3">Desa</td><td class="pr-2">:</td><td class="font-bold">{{ strtoupper($currentVillage?->name ?? '-') }}</td></tr>
                        </table>
                    </div>
                </div>

                <div class="overflow-x-auto print:overflow-visible">

                    {{-- Pengadaan Rekap --}}
                    @if($rekapType === 'pengadaan-rekap')
                    <table class="w-full border-collapse border border-black text-[9.5px] leading-tight text-black">
                        <thead><tr class="text-center font-bold bg-white">
                            <th class="border border-black p-1">NO</th><th class="border border-black p-1">KODE BARANG</th><th class="border border-black p-1">NAMA BARANG</th>
                            <th class="border border-black p-1">QTY</th><th class="border border-black p-1">SATUAN</th><th class="border border-black p-1">HARGA SATUAN (Rp)</th>
                            <th class="border border-black p-1">JUMLAH (Rp)</th><th class="border border-black p-1">SUMBER DANA</th><th class="border border-black p-1">TAHUN</th><th class="border border-black p-1">KONDISI</th>
                        </tr></thead>
                        <tbody>
                        @forelse($items as $item)
                        <tr>
                            <td class="border border-black p-1 text-center">{{ $item['no'] }}</td>
                            <td class="border border-black p-1 text-center whitespace-nowrap">{{ $item['kode'] }}</td>
                            <td class="border border-black p-1 font-medium">{{ $item['nama'] }}</td>
                            <td class="border border-black p-1 text-right">{{ $item['qty'] }}</td>
                            <td class="border border-black p-1 text-center">{{ $item['satuan'] }}</td>
                            <td class="border border-black p-1 text-right whitespace-nowrap">{{ number_format($item['harga'],2) }}</td>
                            <td class="border border-black p-1 text-right whitespace-nowrap font-bold">{{ number_format($item['total'],2) }}</td>
                            <td class="border border-black p-1">{{ $item['sumber'] }}</td>
                            <td class="border border-black p-1 text-center">{{ $item['tahun'] }}</td>
                            <td class="border border-black p-1 text-center">{{ $item['kondisi'] }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="10" class="border border-black p-3 text-center italic">Tidak ada data.</td></tr>
                        @endforelse
                        <tr class="font-bold">
                            <td colspan="6" class="border border-black p-1.5 text-right">JUMLAH TOTAL</td>
                            <td class="border border-black p-1.5 text-right whitespace-nowrap">{{ number_format($totalPrice,2) }}</td>
                            <td colspan="3" class="border border-black p-1.5"></td>
                        </tr>
                        </tbody>
                    </table>
                    @endif

                    {{-- Pengadaan Per Dana --}}
                    @if($rekapType === 'pengadaan-dana')
                    <table class="w-full border-collapse border border-black text-[9.5px] leading-tight text-black">
                        <thead><tr class="text-center font-bold bg-white">
                            <th class="border border-black p-1">NO</th><th class="border border-black p-1">SUMBER DANA</th><th class="border border-black p-1">KODE BARANG</th>
                            <th class="border border-black p-1">NAMA BARANG</th><th class="border border-black p-1">QTY</th><th class="border border-black p-1">SATUAN</th>
                            <th class="border border-black p-1">HARGA (Rp)</th><th class="border border-black p-1">TAHUN</th><th class="border border-black p-1">KONDISI</th>
                        </tr></thead>
                        <tbody>
                        @forelse($items as $item)
                        <tr>
                            <td class="border border-black p-1 text-center">{{ $item['no'] }}</td>
                            <td class="border border-black p-1 font-medium">{{ $item['sumber'] }}</td>
                            <td class="border border-black p-1 text-center whitespace-nowrap">{{ $item['kode'] }}</td>
                            <td class="border border-black p-1">{{ $item['nama'] }}</td>
                            <td class="border border-black p-1 text-right">{{ $item['qty'] }}</td>
                            <td class="border border-black p-1 text-center">{{ $item['satuan'] }}</td>
                            <td class="border border-black p-1 text-right whitespace-nowrap">{{ number_format($item['harga'],2) }}</td>
                            <td class="border border-black p-1 text-center">{{ $item['tahun'] }}</td>
                            <td class="border border-black p-1 text-center">{{ $item['kondisi'] }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="9" class="border border-black p-3 text-center italic">Tidak ada data.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                    @endif

                    {{-- Pemanfaatan --}}
                    @if($rekapType === 'pemanfaatan')
                    <table class="w-full border-collapse border border-black text-[9.5px] leading-tight text-black">
                        <thead><tr class="text-center font-bold bg-white">
                            <th class="border border-black p-1">NO</th><th class="border border-black p-1">KODE</th><th class="border border-black p-1">NAMA ASET</th>
                            <th class="border border-black p-1">PEMANFAAT</th><th class="border border-black p-1">JENIS</th><th class="border border-black p-1">MULAI</th>
                            <th class="border border-black p-1">SELESAI</th><th class="border border-black p-1">NILAI (Rp/Thn)</th><th class="border border-black p-1">KONDISI</th><th class="border border-black p-1">KET.</th>
                        </tr></thead>
                        <tbody>
                        @forelse($items as $item)
                        <tr>
                            <td class="border border-black p-1 text-center">{{ $item['no'] }}</td>
                            <td class="border border-black p-1 text-center whitespace-nowrap">{{ $item['kode'] }}</td>
                            <td class="border border-black p-1 font-medium">{{ $item['nama'] }}</td>
                            <td class="border border-black p-1">{{ $item['pemanfaat'] }}</td>
                            <td class="border border-black p-1 text-center">{{ $item['jenis'] }}</td>
                            <td class="border border-black p-1 text-center">{{ $item['mulai'] }}</td>
                            <td class="border border-black p-1 text-center">{{ $item['selesai'] }}</td>
                            <td class="border border-black p-1 text-right whitespace-nowrap">{{ number_format($item['nilai'],2) }}</td>
                            <td class="border border-black p-1 text-center">{{ $item['kondisi'] }}</td>
                            <td class="border border-black p-1">{{ $item['ket'] }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="10" class="border border-black p-3 text-center italic">Tidak ada data.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                    @endif

                    {{-- Penghapusan --}}
                    @if($rekapType === 'penghapusan')
                    <table class="w-full border-collapse border border-black text-[9.5px] leading-tight text-black">
                        <thead><tr class="text-center font-bold bg-white">
                            <th class="border border-black p-1">NO</th><th class="border border-black p-1">KODE</th><th class="border border-black p-1">NAMA BARANG</th>
                            <th class="border border-black p-1">REG</th><th class="border border-black p-1">QTY</th><th class="border border-black p-1">SATUAN</th>
                            <th class="border border-black p-1">HARGA BUKU (Rp)</th><th class="border border-black p-1">ALASAN PENGHAPUSAN</th><th class="border border-black p-1">NO. SK</th><th class="border border-black p-1">TGL SK</th><th class="border border-black p-1">KET.</th>
                        </tr></thead>
                        <tbody>
                        @forelse($items as $item)
                        <tr>
                            <td class="border border-black p-1 text-center">{{ $item['no'] }}</td>
                            <td class="border border-black p-1 text-center whitespace-nowrap">{{ $item['kode'] }}</td>
                            <td class="border border-black p-1 font-medium">{{ $item['nama'] }}</td>
                            <td class="border border-black p-1 text-center">{{ $item['reg'] }}</td>
                            <td class="border border-black p-1 text-right">{{ $item['qty'] }}</td>
                            <td class="border border-black p-1 text-center">{{ $item['satuan'] }}</td>
                            <td class="border border-black p-1 text-right whitespace-nowrap">{{ number_format($item['harga_buku'],2) }}</td>
                            <td class="border border-black p-1">{{ $item['alasan'] }}</td>
                            <td class="border border-black p-1 text-center">{{ $item['sk_no'] }}</td>
                            <td class="border border-black p-1 text-center">{{ $item['sk_tgl'] }}</td>
                            <td class="border border-black p-1">{{ $item['ket'] }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="11" class="border border-black p-3 text-center italic">Tidak ada data.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                    @endif

                    {{-- Penghapusan Rinci --}}
                    @if($rekapType === 'penghapusan-rinci')
                    <table class="w-full border-collapse border border-black text-[9.5px] leading-tight text-black">
                        <thead><tr class="text-center font-bold bg-white">
                            <th class="border border-black p-1">NO</th><th class="border border-black p-1">KODE</th><th class="border border-black p-1">NAMA BARANG</th>
                            <th class="border border-black p-1">REG</th><th class="border border-black p-1">MERK</th><th class="border border-black p-1">QTY</th>
                            <th class="border border-black p-1">SATUAN</th><th class="border border-black p-1">TAHUN</th><th class="border border-black p-1">HARGA BUKU (Rp)</th>
                            <th class="border border-black p-1">METODE</th><th class="border border-black p-1">NILAI LELANG (Rp)</th><th class="border border-black p-1">NO. SK</th><th class="border border-black p-1">KET.</th>
                        </tr></thead>
                        <tbody>
                        @forelse($items as $item)
                        <tr>
                            <td class="border border-black p-1 text-center">{{ $item['no'] }}</td>
                            <td class="border border-black p-1 text-center whitespace-nowrap">{{ $item['kode'] }}</td>
                            <td class="border border-black p-1 font-medium">{{ $item['nama'] }}</td>
                            <td class="border border-black p-1 text-center">{{ $item['reg'] }}</td>
                            <td class="border border-black p-1">{{ $item['merk'] }}</td>
                            <td class="border border-black p-1 text-right">{{ $item['qty'] }}</td>
                            <td class="border border-black p-1 text-center">{{ $item['satuan'] }}</td>
                            <td class="border border-black p-1 text-center">{{ $item['tahun'] }}</td>
                            <td class="border border-black p-1 text-right whitespace-nowrap">{{ number_format($item['harga_buku'],2) }}</td>
                            <td class="border border-black p-1 text-center">{{ $item['metode'] }}</td>
                            <td class="border border-black p-1 text-right whitespace-nowrap">{{ number_format($item['nilai_lelang'],2) }}</td>
                            <td class="border border-black p-1 text-center">{{ $item['sk_no'] }}</td>
                            <td class="border border-black p-1">{{ $item['ket'] }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="13" class="border border-black p-3 text-center italic">Tidak ada data.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                    @endif
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
                <p>Printed By SIPADES R3 - {{ $meta['title'] }}</p>
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
