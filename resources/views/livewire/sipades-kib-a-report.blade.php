<div class="py-6 bg-slate-100 min-h-screen">
    
    <!-- Action Toolbar (Hidden during printing) -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-6 print:hidden">
        <div class="bg-white rounded-2xl border border-slate-200 p-4 sm:p-5 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
            
            <div class="flex items-center gap-3">
                <a href="{{ route('reports.villages') }}" class="p-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors" title="Kembali ke Laporan Desa">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                </a>
                <div>
                    <h2 class="font-bold text-slate-900 text-sm sm:text-base flex items-center gap-2">
                        <span>Format Laporan Resmi SIPADES R3 (KIB A)</span>
                        <span class="text-[10px] px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 border border-emerald-200 font-bold">Standard Kemendagri</span>
                    </h2>
                    <p class="text-xs text-slate-500">Buku Inventaris Aset Desa - Tanah (KIB A) Kabupaten Gresik</p>
                </div>
            </div>

            <!-- Controls -->
            <div class="flex flex-wrap items-center gap-2">
                <!-- Select Desa -->
                <select 
                    wire:model.live="selectedVillageId" 
                    class="px-3 py-1.5 text-xs rounded-lg border border-slate-200 bg-white font-medium focus:ring-2 focus:ring-teal-600 focus:outline-none"
                >
                    @foreach($villages as $v)
                        <option value="{{ $v->id }}">Desa {{ $v->name }} (Kec. {{ $v->district->name ?? 'Gresik' }})</option>
                    @endforeach
                </select>

                <!-- Print Button -->
                <button 
                    onclick="window.print()" 
                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-teal-700 hover:bg-teal-800 text-white text-xs font-bold shadow-xs transition-colors cursor-pointer"
                >
                    <i data-lucide="printer" class="w-3.5 h-3.5"></i>
                    <span>Cetak / Unduh PDF</span>
                </button>
            </div>

        </div>

        <!-- Quick customization accordion -->
        <div x-data="{ expanded: false }" class="bg-white rounded-xl border border-slate-200 p-3 mt-3 text-xs">
            <button @click="expanded = !expanded" class="w-full flex items-center justify-between text-slate-700 font-semibold">
                <span class="flex items-center gap-1.5">
                    <i data-lucide="sliders" class="w-3.5 h-3.5 text-teal-600"></i>
                    <span>Pengaturan Penandatangan & Tanggal Dokumen</span>
                </span>
                <i data-lucide="chevron-down" class="w-4 h-4 transition-transform" :class="expanded ? 'rotate-180' : ''"></i>
            </button>
            
            <div x-show="expanded" x-cloak class="grid grid-cols-1 sm:grid-cols-4 gap-3 mt-3 pt-3 border-t border-slate-100">
                <div>
                    <label class="block text-[11px] text-slate-500 mb-1">Periode Laporan:</label>
                    <input type="text" wire:model.live.debounce.300ms="reportDate" class="w-full px-2.5 py-1.5 text-xs rounded border border-slate-200">
                </div>
                <div>
                    <label class="block text-[11px] text-slate-500 mb-1">Tanggal Tanda Tangan:</label>
                    <input type="text" wire:model.live.debounce.300ms="signDate" class="w-full px-2.5 py-1.5 text-xs rounded border border-slate-200">
                </div>
                <div>
                    <label class="block text-[11px] text-slate-500 mb-1">Nama Sekretaris / Kepala Desa:</label>
                    <input type="text" wire:model.live.debounce.300ms="sekretarisName" class="w-full px-2.5 py-1.5 text-xs rounded border border-slate-200">
                </div>
                <div>
                    <label class="block text-[11px] text-slate-500 mb-1">Nama Petugas / Pengurus Barang:</label>
                    <input type="text" wire:model.live.debounce.300ms="pengurusName" class="w-full px-2.5 py-1.5 text-xs rounded border border-slate-200">
                </div>
            </div>
        </div>
    </div>

    <!-- PRINT CONTAINER (EXACT 1:1 REPLICATION OF SIPADES R3 KIB A PDF) -->
    <div class="max-w-[1360px] mx-auto px-4 sm:px-6 lg:px-8 print:p-0 print:m-0 print:max-w-none">
        
        <div class="bg-white p-6 sm:p-10 shadow-lg print:shadow-none print:p-4 rounded-xl print:rounded-none border border-slate-200 print:border-none text-black font-sans text-[11px] leading-tight select-text">
            
            <!-- 1. Document Header -->
            <div class="flex items-start justify-between gap-6 mb-6">
                <!-- Lambang Gresik (Authentic Official Emblem SVG) -->
                <div class="w-20 h-24 shrink-0 flex flex-col items-center justify-center">
                    <svg viewBox="0 0 100 120" class="w-16 h-20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <!-- Shield Outer Border -->
                        <path d="M50 5 L90 20 V65 C90 90 50 115 50 115 C50 115 10 90 10 65 V20 L50 5 Z" fill="#2563eb" stroke="#eab308" stroke-width="4"/>
                        <!-- Yellow Inner Shield -->
                        <path d="M50 12 L82 24 V63 C82 84 50 106 50 106 C50 106 18 84 18 63 V24 L50 12 Z" fill="#fbbf24"/>
                        <!-- Center Green Field -->
                        <path d="M50 20 L75 30 V60 C75 78 50 96 50 96 C50 96 25 78 25 60 V30 L50 20 Z" fill="#15803d"/>
                        <!-- Giri Monument & Star Motif -->
                        <polygon points="50,26 53,35 62,35 55,40 57,49 50,44 43,49 45,40 38,35 47,35" fill="#ffffff"/>
                        <path d="M42 55 L50 42 L58 55 H54 V75 H46 V55 Z" fill="#ffffff" stroke="#0f172a" stroke-width="1.5"/>
                        <!-- Gresik Banner at Bottom -->
                        <rect x="22" y="80" width="56" height="13" rx="2" fill="#ffffff" stroke="#0f172a" stroke-width="1.5"/>
                        <text x="50" y="90" font-size="8" font-family="Arial, sans-serif" font-weight="900" fill="#0f172a" text-anchor="middle" letter-spacing="0.5">GRESIK</text>
                    </svg>
                </div>

                <!-- Center Title Header -->
                <div class="flex-1 text-center pr-20">
                    <h1 class="text-base font-bold uppercase tracking-wider font-sans text-black">
                        BUKU INVENTARIS ASET DESA
                    </h1>
                    <h2 class="text-base font-bold uppercase tracking-wider font-sans text-black">
                        TANAH (KIB A)
                    </h2>
                    <p class="text-[11px] font-medium text-black mt-1">
                        Periode Tanggal, {{ $reportDate }}
                    </p>
                </div>
            </div>

            <!-- 2. Location Metadata Table Block -->
            <div class="mb-4 text-[11px] font-sans text-black">
                <table class="border-collapse text-left w-auto">
                    <tbody>
                        <tr>
                            <td class="font-bold pr-2 py-0.5 w-32">Provinsi</td>
                            <td class="pr-2 py-0.5">:</td>
                            <td class="font-bold py-0.5">35 . JAWA TIMUR</td>
                        </tr>
                        <tr>
                            <td class="font-bold pr-2 py-0.5">Kab./Kota</td>
                            <td class="pr-2 py-0.5">:</td>
                            <td class="font-bold py-0.5">35 . 25 . KABUPATEN GRESIK</td>
                        </tr>
                        <tr>
                            <td class="font-bold pr-2 py-0.5">Kecamatan</td>
                            <td class="pr-2 py-0.5">:</td>
                            <td class="font-bold py-0.5">35 . 25 . {{ $currentVillage?->district?->id ?? '1' }} . KECAMATAN {{ strtoupper($currentVillage?->district?->name ?? 'DUKUN') }}</td>
                        </tr>
                        <tr>
                            <td class="font-bold pr-2 py-0.5">Desa</td>
                            <td class="pr-2 py-0.5">:</td>
                            <td class="font-bold py-0.5">35 . 25 . {{ $currentVillage?->district?->id ?? '1' }} . {{ $currentVillage?->code ? substr($currentVillage->code, -4) : '2003' }} . {{ strtoupper($currentVillage?->name ?? "GEDONGKEDO'AN") }}</td>
                        </tr>
                        <tr>
                            <td class="font-bold pr-2 py-0.5">UPB</td>
                            <td class="pr-2 py-0.5">:</td>
                            <td class="font-bold py-0.5">[ SEMUA UPB ]</td>
                        </tr>
                        <tr>
                            <td class="font-bold pr-2 py-0.5">No. Kode Lokasi</td>
                            <td class="pr-2 py-0.5">:</td>
                            <td class="font-bold py-0.5">{{ $kodeLokasi }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- 3. Official SIPADES R3 Table -->
            <div class="overflow-x-auto print:overflow-visible">
                <table class="w-full border-collapse border border-black text-[10px] leading-tight">
                    <thead>
                        <!-- Header Level 1 -->
                        <tr class="text-center font-bold text-black bg-white">
                            <th rowspan="2" class="border border-black p-1.5 align-middle w-7">NO</th>
                            <th rowspan="2" class="border border-black p-1.5 align-middle w-48">JENIS BARANG /<br>NAMA BARANG</th>
                            <th colspan="2" class="border border-black p-1.5 align-middle">N O M O R</th>
                            <th colspan="4" class="border border-black p-1.5 align-middle">I D E N T I T A S</th>
                            <th colspan="3" class="border border-black p-1.5 align-middle">STATUS TANAH</th>
                            <th rowspan="2" class="border border-black p-1.5 align-middle w-28">ASAL-USUL<br>BARANG</th>
                            <th rowspan="2" class="border border-black p-1.5 align-middle w-32">HARGA<br>(Rp)</th>
                            <th rowspan="2" class="border border-black p-1.5 align-middle w-36">KETERANGAN</th>
                        </tr>
                        
                        <!-- Header Level 2 -->
                        <tr class="text-center font-bold text-black bg-white">
                            <th class="border border-black p-1 align-middle w-32">KODE BARANG</th>
                            <th class="border border-black p-1 align-middle w-14">REG</th>
                            <th class="border border-black p-1 align-middle w-20">LUAS (M2)</th>
                            <th class="border border-black p-1 align-middle w-14">TAHUN<br>PEROLEH</th>
                            <th class="border border-black p-1 align-middle w-14">TAHUN<br>BUKU</th>
                            <th class="border border-black p-1 align-middle w-36">ALAMAT</th>
                            <th class="border border-black p-1 align-middle w-16">H A K</th>
                            <th colspan="2" class="border border-black p-1 align-middle">
                                <div>SERTIFIKAT</div>
                                <div class="grid grid-cols-2 border-t border-black mt-0.5 pt-0.5">
                                    <span class="border-r border-black">NOMOR</span>
                                    <span>TGL</span>
                                </div>
                            </th>
                        </tr>

                        <!-- Column Index Numbers (1 to 8) -->
                        <tr class="text-center font-bold text-black bg-white text-[9px]">
                            <th class="border border-black py-0.5">1</th>
                            <th class="border border-black py-0.5">2</th>
                            <th colspan="2" class="border border-black py-0.5">3</th>
                            <th colspan="4" class="border border-black py-0.5">4</th>
                            <th colspan="3" class="border border-black py-0.5">5</th>
                            <th class="border border-black py-0.5">6</th>
                            <th class="border border-black py-0.5">7</th>
                            <th class="border border-black py-0.5">8</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($assets as $idx => $item)
                            <tr class="hover:bg-slate-50 print:hover:bg-transparent">
                                <td class="border border-black p-1 text-center align-top font-medium">{{ $idx + 1 }}</td>
                                <td class="border border-black p-1 text-left align-top font-medium">{{ $item['name'] }}</td>
                                <td class="border border-black p-1 text-center align-top whitespace-nowrap">{{ $item['code'] }}</td>
                                <td class="border border-black p-1 text-center align-top">{{ $item['reg'] }}</td>
                                <td class="border border-black p-1 text-right align-top">{{ number_format($item['area'], 2) }}</td>
                                <td class="border border-black p-1 text-center align-top">{{ $item['year_acquired'] }}</td>
                                <td class="border border-black p-1 text-center align-top">{{ $item['year_book'] }}</td>
                                <td class="border border-black p-1 text-left align-top">{{ $item['address'] }}</td>
                                <td class="border border-black p-1 text-center align-top">{{ $item['hak'] }}</td>
                                <td class="border border-black p-1 text-center align-top w-12">{{ $item['cert_no'] }}</td>
                                <td class="border border-black p-1 text-center align-top w-12">{{ $item['cert_date'] }}</td>
                                <td class="border border-black p-1 text-left align-top">{{ $item['origin'] }}</td>
                                <td class="border border-black p-1 text-right align-top font-medium whitespace-nowrap">
                                    {{ number_format($item['price'], 2) }}
                                </td>
                                <td class="border border-black p-1 text-left align-top">{{ $item['notes'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="14" class="border border-black p-4 text-center text-slate-500 italic">
                                    Tidak ada data aset tanah terdaftar untuk desa ini.
                                </td>
                            </tr>
                        @endforelse

                        <!-- Summary Row (JUMLAH HARGA) -->
                        <tr class="font-bold bg-white text-black">
                            <td colspan="12" class="border border-black p-1.5 text-center uppercase tracking-wider font-bold">
                                JUMLAH HARGA
                            </td>
                            <td class="border border-black p-1.5 text-right font-bold whitespace-nowrap">
                                {{ number_format($totalPrice, 2) }}
                            </td>
                            <td class="border border-black p-1.5"></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- 4. Sign-off / Signature Block (Page 2 / Bottom of Report) -->
            <div class="mt-12 grid grid-cols-2 gap-8 text-[11px] font-sans text-black break-inside-avoid">
                <!-- Left Signatory: Sekretaris Desa / Pembantu Pengelola -->
                <div class="text-center space-y-1">
                    <p class="font-bold">MENGETAHUI</p>
                    <p class="font-bold">{{ $penandatanganRole }}</p>
                    <p class="text-[10px]">Selaku Pembantu Pengelola Barang Milik Desa</p>
                    <div class="h-20"></div>
                    <p class="font-bold underline uppercase tracking-wide">{{ $sekretarisName }}</p>
                </div>

                <!-- Right Signatory: Petugas / Pengurus Barang Milik Desa -->
                <div class="text-center space-y-1">
                    <p>{{ $signDate }}</p>
                    <p class="font-bold uppercase tracking-wide">PETUGAS / PENGURUS BARANG MILIK DESA</p>
                    <div class="h-20"></div>
                    <p class="font-bold underline uppercase tracking-wide">{{ $pengurusName }}</p>
                </div>
            </div>

            <!-- 5. Official SIPADES Footer -->
            <div class="mt-12 pt-2 border-t border-slate-300 flex items-center justify-between text-[9px] text-black italic">
                <p>Printed By SIPADES R3 - Laporan Buku Inventaris Aset Desa Tanah (KIB A)</p>
                <p>Halaman : 1 dari 1</p>
            </div>

        </div>
    </div>

</div>

<!-- PRINT STYLING -->
<style>
@media print {
    @page {
        size: landscape;
        margin: 8mm 10mm;
    }
    body {
        background-color: #ffffff !important;
        color: #000000 !important;
        font-family: Arial, Helvetica, sans-serif !important;
    }
    header, footer, nav, .print\:hidden {
        display: none !important;
    }
    table {
        page-break-inside: auto;
    }
    tr {
        page-break-inside: avoid;
        page-break-after: auto;
    }
    thead {
        display: table-header-group;
    }
    tfoot {
        display: table-footer-group;
    }
}
</style>
