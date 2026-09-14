<div class="py-6 bg-slate-200/80 min-h-screen">
    
    <!-- Top Action Toolbar (Hidden during printing) -->
    <div class="max-w-[1280px] mx-auto px-4 sm:px-6 mb-6 print:hidden">
        <div class="bg-white rounded-xl border border-slate-300 p-4 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
            
            <div class="flex items-center gap-3">
                <a href="{{ route('reports.villages') }}" class="p-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors" title="Kembali ke Daftar Laporan">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                </a>
                <div>
                    <h2 class="font-bold text-slate-900 text-sm sm:text-base flex items-center gap-2">
                        <span>Laporan Buku Inventaris Aset Desa Tanah (KIB A)</span>
                        <span class="text-[10px] px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 font-bold border border-emerald-300">Format Resmi SIPADES R3</span>
                    </h2>
                    <p class="text-xs text-slate-500">Standar Permendagri & Pengelolaan Aset Desa Kabupaten Gresik</p>
                </div>
            </div>

            <!-- Controls -->
            <div class="flex flex-wrap items-center gap-2">
                <!-- Select Desa -->
                <div class="flex items-center gap-1.5 bg-slate-50 border border-slate-200 rounded-lg px-2.5 py-1">
                    <span class="text-[11px] text-slate-500 font-semibold">Desa:</span>
                    <select 
                        wire:model.live="selectedVillageId" 
                        class="bg-transparent text-xs font-bold text-slate-800 focus:outline-none cursor-pointer"
                    >
                        @foreach($villages as $v)
                            <option value="{{ $v->id }}">{{ $v->name }} ({{ $v->district->name ?? 'Gresik' }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Print Button -->
                <button 
                    onclick="window.print()" 
                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-[#008080] hover:bg-[#006666] text-white text-xs font-bold shadow transition-colors cursor-pointer"
                >
                    <i data-lucide="printer" class="w-3.5 h-3.5"></i>
                    <span>Cetak / Simpan PDF</span>
                </button>
            </div>

        </div>

        <!-- Customization Toggle -->
        <div x-data="{ open: false }" class="mt-2 bg-white rounded-xl border border-slate-300 p-3 shadow-2xs text-xs">
            <button @click="open = !open" class="w-full flex items-center justify-between text-slate-700 font-semibold cursor-pointer">
                <span class="flex items-center gap-1.5">
                    <i data-lucide="sliders" class="w-3.5 h-3.5 text-teal-600"></i>
                    <span>Sesuaikan Data Penandatangan & Tanggal Periode Laporan</span>
                </span>
                <i data-lucide="chevron-down" class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''"></i>
            </button>

            <div x-show="open" x-cloak class="grid grid-cols-1 sm:grid-cols-4 gap-3 mt-3 pt-3 border-t border-slate-100">
                <div>
                    <label class="block text-[10.5px] text-slate-500 mb-1">Periode Tanggal:</label>
                    <input type="text" wire:model.live.debounce.300ms="reportDate" class="w-full px-2.5 py-1.5 text-xs rounded border border-slate-200">
                </div>
                <div>
                    <label class="block text-[10.5px] text-slate-500 mb-1">Tanggal Tanda Tangan:</label>
                    <input type="text" wire:model.live.debounce.300ms="signDate" class="w-full px-2.5 py-1.5 text-xs rounded border border-slate-200">
                </div>
                <div>
                    <label class="block text-[10.5px] text-slate-500 mb-1">Nama Sekretaris Desa:</label>
                    <input type="text" wire:model.live.debounce.300ms="sekretarisName" class="w-full px-2.5 py-1.5 text-xs rounded border border-slate-200">
                </div>
                <div>
                    <label class="block text-[10.5px] text-slate-500 mb-1">Nama Petugas / Pengurus:</label>
                    <input type="text" wire:model.live.debounce.300ms="pengurusName" class="w-full px-2.5 py-1.5 text-xs rounded border border-slate-200">
                </div>
            </div>
        </div>
    </div>

    <!-- DOCUMENT VIEWER CONTAINER (MULTI-PAGE A4 LANDSCAPE EXACT REPLICA) -->
    <div class="max-w-[1280px] mx-auto px-2 sm:px-6 space-y-8 print:space-y-0 print:p-0 print:m-0 print:max-w-none select-text">
        
        <!-- ========================================================================= -->
        <!-- SHEET 1 (HALAMAN 1 DARI 2)                                                -->
        <!-- ========================================================================= -->
        <div class="page-sheet bg-white p-6 sm:p-10 shadow-2xl print:shadow-none border border-slate-300 print:border-none text-black font-sans text-[10.5px] leading-snug min-h-[640px] flex flex-col justify-between relative">
            
            <div>
                <!-- Header: Emblem & Title -->
                <div class="flex items-start justify-between gap-4 mb-4">
                    <!-- Lambang Kabupaten Gresik -->
                    <div class="w-16 h-20 shrink-0 flex flex-col items-center justify-center">
                        <svg viewBox="0 0 100 120" class="w-14 h-18" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M50 5 L90 20 V65 C90 90 50 115 50 115 C50 115 10 90 10 65 V20 L50 5 Z" fill="#2563eb" stroke="#eab308" stroke-width="4"/>
                            <path d="M50 12 L82 24 V63 C82 84 50 106 50 106 C50 106 18 84 18 63 V24 L50 12 Z" fill="#fbbf24"/>
                            <path d="M50 20 L75 30 V60 C75 78 50 96 50 96 C50 96 25 78 25 60 V30 L50 20 Z" fill="#15803d"/>
                            <polygon points="50,26 53,35 62,35 55,40 57,49 50,44 43,49 45,40 38,35 47,35" fill="#ffffff"/>
                            <path d="M42 55 L50 42 L58 55 H54 V75 H46 V55 Z" fill="#ffffff" stroke="#0f172a" stroke-width="1.5"/>
                            <rect x="22" y="80" width="56" height="13" rx="2" fill="#ffffff" stroke="#0f172a" stroke-width="1.5"/>
                            <text x="50" y="90" font-size="8" font-family="Arial, sans-serif" font-weight="900" fill="#0f172a" text-anchor="middle" letter-spacing="0.5">GRESIK</text>
                        </svg>
                    </div>

                    <!-- Center Title -->
                    <div class="flex-1 text-center pr-16">
                        <h1 class="text-sm sm:text-base font-bold uppercase tracking-wider text-black font-sans">
                            BUKU INVENTARIS ASET DESA
                        </h1>
                        <h2 class="text-sm sm:text-base font-bold uppercase tracking-wider text-black font-sans">
                            TANAH (KIB A)
                        </h2>
                        <p class="text-[10.5px] font-normal text-black mt-0.5">
                            Periode Tanggal, {{ $reportDate }}
                        </p>
                    </div>
                </div>

                <!-- Location Metadata Block -->
                <div class="mb-3 text-[10.5px] text-black">
                    <table class="border-collapse text-left w-auto">
                        <tbody>
                            <tr>
                                <td class="font-bold pr-3 py-0.5 w-28">Provinsi</td>
                                <td class="pr-2 py-0.5">:</td>
                                <td class="font-bold py-0.5">35 . JAWA TIMUR</td>
                            </tr>
                            <tr>
                                <td class="font-bold pr-3 py-0.5">Kab./Kota</td>
                                <td class="pr-2 py-0.5">:</td>
                                <td class="font-bold py-0.5">35 . 25 . KABUPATEN GRESIK</td>
                            </tr>
                            <tr>
                                <td class="font-bold pr-3 py-0.5">Kecamatan</td>
                                <td class="pr-2 py-0.5">:</td>
                                <td class="font-bold py-0.5">35 . 25 . {{ $currentVillage?->district?->id ?? '1' }} . KECAMATAN {{ strtoupper($currentVillage?->district?->name ?? 'DUKUN') }}</td>
                            </tr>
                            <tr>
                                <td class="font-bold pr-3 py-0.5">Desa</td>
                                <td class="pr-2 py-0.5">:</td>
                                <td class="font-bold py-0.5">35 . 25 . {{ $currentVillage?->district?->id ?? '1' }} . {{ $currentVillage?->code ? substr($currentVillage->code, -4) : '2003' }} . {{ strtoupper($currentVillage?->name ?? "GEDONGKEDO'AN") }}</td>
                            </tr>
                            <tr>
                                <td class="font-bold pr-3 py-0.5">UPB</td>
                                <td class="pr-2 py-0.5">:</td>
                                <td class="font-bold py-0.5">[ SEMUA UPB ]</td>
                            </tr>
                            <tr>
                                <td class="font-bold pr-3 py-0.5">No. Kode Lokasi</td>
                                <td class="pr-2 py-0.5">:</td>
                                <td class="font-bold py-0.5">{{ $kodeLokasi }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Official SIPADES Table (Page 1 Items) -->
                <div class="overflow-x-auto print:overflow-visible">
                    <table class="w-full border-collapse border border-black text-[9.5px] leading-tight text-black">
                        <thead>
                            <tr class="text-center font-bold bg-white">
                                <th rowspan="2" class="border border-black p-1 align-middle w-6">NO</th>
                                <th rowspan="2" class="border border-black p-1 align-middle w-40">JENIS BARANG /<br>NAMA BARANG</th>
                                <th colspan="2" class="border border-black p-1 align-middle">N O M O R</th>
                                <th colspan="4" class="border border-black p-1 align-middle">I D E N T I T A S</th>
                                <th colspan="3" class="border border-black p-1 align-middle">STATUS TANAH</th>
                                <th rowspan="2" class="border border-black p-1 align-middle w-24">ASAL-USUL<br>BARANG</th>
                                <th rowspan="2" class="border border-black p-1 align-middle w-28">HARGA<br>(Rp)</th>
                                <th rowspan="2" class="border border-black p-1 align-middle w-32">KETERANGAN</th>
                            </tr>
                            <tr class="text-center font-bold bg-white">
                                <th class="border border-black p-0.5 align-middle w-28">KODE BARANG</th>
                                <th class="border border-black p-0.5 align-middle w-12">REG</th>
                                <th class="border border-black p-0.5 align-middle w-16">LUAS (M2)</th>
                                <th class="border border-black p-0.5 align-middle w-12">TAHUN<br>PEROLEH</th>
                                <th class="border border-black p-0.5 align-middle w-12">TAHUN<br>BUKU</th>
                                <th class="border border-black p-0.5 align-middle w-32">ALAMAT</th>
                                <th class="border border-black p-0.5 align-middle w-14">H A K</th>
                                <th colspan="2" class="border border-black p-0.5 align-middle">
                                    <div>SERTIFIKAT</div>
                                    <div class="grid grid-cols-2 border-t border-black mt-0.5 pt-0.5">
                                        <span class="border-r border-black">NOMOR</span>
                                        <span>TGL</span>
                                    </div>
                                </th>
                            </tr>
                            <tr class="text-center font-bold bg-white text-[8.5px]">
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
                                <tr>
                                    <td class="border border-black p-1 text-center align-top">{{ $idx + 1 }}</td>
                                    <td class="border border-black p-1 text-left align-top font-medium">{{ $item['name'] }}</td>
                                    <td class="border border-black p-1 text-center align-top whitespace-nowrap">{{ $item['code'] }}</td>
                                    <td class="border border-black p-1 text-center align-top">{{ $item['reg'] }}</td>
                                    <td class="border border-black p-1 text-right align-top whitespace-nowrap">{{ number_format($item['area'], 2) }}</td>
                                    <td class="border border-black p-1 text-center align-top">{{ $item['year_acquired'] }}</td>
                                    <td class="border border-black p-1 text-center align-top">{{ $item['year_book'] }}</td>
                                    <td class="border border-black p-1 text-left align-top">{{ $item['address'] }}</td>
                                    <td class="border border-black p-1 text-center align-top">{{ $item['hak'] }}</td>
                                    <td class="border border-black p-1 text-center align-top w-10">{{ $item['cert_no'] }}</td>
                                    <td class="border border-black p-1 text-center align-top w-10">{{ $item['cert_date'] }}</td>
                                    <td class="border border-black p-1 text-left align-top">{{ $item['origin'] }}</td>
                                    <td class="border border-black p-1 text-right align-top whitespace-nowrap font-medium">
                                        {{ number_format($item['price'], 2) }}
                                    </td>
                                    <td class="border border-black p-1 text-left align-top">{{ $item['notes'] }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="14" class="border border-black p-3 text-center text-slate-500 italic">
                                        Tidak ada aset tanah terdaftar.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Page 1 Footer -->
            <div class="mt-6 pt-1.5 flex items-center justify-between text-[8.5px] text-black border-t border-black/20">
                <p>Printed By SIPADES R3 - Laporan Buku Inventaris Aset Desa Tanah (KIB A)</p>
                <p>Halaman : 1 dari 2</p>
            </div>

        </div>

        <!-- CSS Page Break between Page 1 and Page 2 -->
        <div class="page-break"></div>

        <!-- ========================================================================= -->
        <!-- SHEET 2 (HALAMAN 2 DARI 2 - TOTALS & SIGNATURES)                          -->
        <!-- ========================================================================= -->
        <div class="page-sheet bg-white p-6 sm:p-10 shadow-2xl print:shadow-none border border-slate-300 print:border-none text-black font-sans text-[10.5px] leading-snug min-h-[640px] flex flex-col justify-between relative">
            
            <div>
                <!-- Table Header Repeated on Page 2 -->
                <div class="overflow-x-auto print:overflow-visible">
                    <table class="w-full border-collapse border border-black text-[9.5px] leading-tight text-black">
                        <thead>
                            <tr class="text-center font-bold bg-white">
                                <th rowspan="2" class="border border-black p-1 align-middle w-6">NO</th>
                                <th rowspan="2" class="border border-black p-1 align-middle w-40">JENIS BARANG /<br>NAMA BARANG</th>
                                <th colspan="2" class="border border-black p-1 align-middle">N O M O R</th>
                                <th colspan="4" class="border border-black p-1 align-middle">I D E N T I T A S</th>
                                <th colspan="3" class="border border-black p-1 align-middle">STATUS TANAH</th>
                                <th rowspan="2" class="border border-black p-1 align-middle w-24">ASAL-USUL<br>BARANG</th>
                                <th rowspan="2" class="border border-black p-1 align-middle w-28">HARGA<br>(Rp)</th>
                                <th rowspan="2" class="border border-black p-1 align-middle w-32">KETERANGAN</th>
                            </tr>
                            <tr class="text-center font-bold bg-white">
                                <th class="border border-black p-0.5 align-middle w-28">KODE BARANG</th>
                                <th class="border border-black p-0.5 align-middle w-12">REG</th>
                                <th class="border border-black p-0.5 align-middle w-16">LUAS (M2)</th>
                                <th class="border border-black p-0.5 align-middle w-12">TAHUN<br>PEROLEH</th>
                                <th class="border border-black p-0.5 align-middle w-12">TAHUN<br>BUKU</th>
                                <th class="border border-black p-0.5 align-middle w-32">ALAMAT</th>
                                <th class="border border-black p-0.5 align-middle w-14">H A K</th>
                                <th colspan="2" class="border border-black p-0.5 align-middle">
                                    <div>SERTIFIKAT</div>
                                    <div class="grid grid-cols-2 border-t border-black mt-0.5 pt-0.5">
                                        <span class="border-r border-black">NOMOR</span>
                                        <span>TGL</span>
                                    </div>
                                </th>
                            </tr>
                            <tr class="text-center font-bold bg-white text-[8.5px]">
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
                            <!-- JUMLAH HARGA Row -->
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

                <!-- Signatures Block on Page 2 -->
                <div class="mt-12 grid grid-cols-2 gap-12 text-[10.5px] text-black">
                    <!-- Left: Sekretaris Desa -->
                    <div class="text-center space-y-1">
                        <p class="font-bold">MENGETAHUI</p>
                        <p class="font-bold">{{ $penandatanganRole }}</p>
                        <p class="text-[9.5px]">Selaku Pembantu Pengelola Barang Milik Desa</p>
                        <div class="h-24"></div>
                        <p class="font-bold underline uppercase tracking-wide">{{ $sekretarisName }}</p>
                    </div>

                    <!-- Right: Petugas Pengurus Barang -->
                    <div class="text-center space-y-1">
                        <p>{{ $signDate }}</p>
                        <p class="font-bold uppercase tracking-wide">PETUGAS / PENGURUS BARANG MILIK DESA</p>
                        <div class="h-24"></div>
                        <p class="font-bold underline uppercase tracking-wide">{{ $pengurusName }}</p>
                    </div>
                </div>
            </div>

            <!-- Page 2 Footer -->
            <div class="mt-6 pt-1.5 flex items-center justify-between text-[8.5px] text-black border-t border-black/20">
                <p>Printed By SIPADES R3 - Laporan Buku Inventaris Aset Desa Tanah (KIB A)</p>
                <p>Halaman : 2 dari 2</p>
            </div>

        </div>

    </div>

    <!-- PRINT STYLING FOR PERFECT 2-PAGE LANDSCAPE EXPORT -->
    <style>
    @media print {
        @page {
            size: landscape;
            margin: 6mm 8mm;
        }
        html, body {
            background: #ffffff !important;
            background-color: #ffffff !important;
            color: #000000 !important;
            font-family: Arial, Helvetica, sans-serif !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        header, footer, nav, .print\:hidden {
            display: none !important;
        }
        .page-sheet {
            box-shadow: none !important;
            border: none !important;
            padding: 0 !important;
            margin: 0 !important;
            width: 100% !important;
            max-width: none !important;
            min-height: 98vh !important;
            page-break-inside: avoid !important;
        }
        .page-break {
            page-break-after: always !important;
            break-after: page !important;
            height: 0 !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        table {
            border-collapse: collapse !important;
            width: 100% !important;
        }
        th, td {
            border: 1px solid #000000 !important;
            color: #000000 !important;
        }
    }
    </style>

</div>
