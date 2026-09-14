<div class="py-6 bg-slate-200/80 min-h-screen">

    {{-- Toolbar --}}
    <div class="max-w-[1280px] mx-auto px-4 sm:px-6 mb-6 print:hidden">
        <div class="bg-white rounded-xl border border-slate-300 p-4 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('reports.sipades') }}" class="p-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors" title="Kembali ke Menu SIPADES">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                </a>
                <div>
                    <h2 class="font-bold text-slate-900 text-sm sm:text-base flex items-center gap-2">
                        <span>Laporan Buku Inventaris Aset Desa {{ $meta['title'] }} ({{ $meta['short'] }})</span>
                        <span class="text-[10px] px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 font-bold border border-emerald-300">Format Resmi SIPADES R3</span>
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

        {{-- Customization panel --}}
        <div x-data="{ open: false }" class="mt-2 bg-white rounded-xl border border-slate-300 p-3 shadow-2xs text-xs">
            <button @click="open = !open" class="w-full flex items-center justify-between text-slate-700 font-semibold cursor-pointer">
                <span class="flex items-center gap-1.5"><i data-lucide="sliders" class="w-3.5 h-3.5 text-teal-600"></i> Sesuaikan Data Penandatangan &amp; Tanggal Periode Laporan</span>
                <i data-lucide="chevron-down" class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''"></i>
            </button>
            <div x-show="open" x-cloak class="grid grid-cols-1 sm:grid-cols-4 gap-3 mt-3 pt-3 border-t border-slate-100">
                <div><label class="block text-[10.5px] text-slate-500 mb-1">Periode Tanggal:</label><input type="text" wire:model.live.debounce.300ms="reportDate" class="w-full px-2.5 py-1.5 text-xs rounded border border-slate-200"></div>
                <div><label class="block text-[10.5px] text-slate-500 mb-1">Tanggal Tanda Tangan:</label><input type="text" wire:model.live.debounce.300ms="signDate" class="w-full px-2.5 py-1.5 text-xs rounded border border-slate-200"></div>
                <div><label class="block text-[10.5px] text-slate-500 mb-1">Nama Sekretaris Desa:</label><input type="text" wire:model.live.debounce.300ms="sekretarisName" class="w-full px-2.5 py-1.5 text-xs rounded border border-slate-200"></div>
                <div><label class="block text-[10.5px] text-slate-500 mb-1">Nama Petugas / Pengurus:</label><input type="text" wire:model.live.debounce.300ms="pengurusName" class="w-full px-2.5 py-1.5 text-xs rounded border border-slate-200"></div>
            </div>
        </div>
    </div>

    {{-- Document --}}
    <div class="max-w-[1280px] mx-auto px-2 sm:px-6 space-y-8 print:space-y-0 print:p-0 print:m-0 print:max-w-none select-text">

        {{-- Page 1 --}}
        <div class="page-sheet bg-white p-6 sm:p-10 shadow-2xl print:shadow-none border border-slate-300 print:border-none text-black font-sans text-[10.5px] leading-snug min-h-[640px] flex flex-col justify-between relative">
            <div>
                {{-- Header --}}
                <div class="flex items-start justify-between gap-4 mb-4">
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
                    <div class="flex-1 text-center pr-16">
                        <h1 class="text-sm sm:text-base font-bold uppercase tracking-wider text-black font-sans">BUKU INVENTARIS ASET DESA</h1>
                        <h2 class="text-sm sm:text-base font-bold uppercase tracking-wider text-black font-sans">{{ $meta['title'] }} ({{ $meta['short'] }})</h2>
                        <p class="text-[10.5px] font-normal text-black mt-0.5">Periode Tanggal, {{ $reportDate }}</p>
                    </div>
                </div>

                {{-- Location metadata --}}
                <div class="mb-3 text-[10.5px] text-black">
                    <table class="border-collapse text-left w-auto">
                        <tbody>
                            <tr><td class="font-bold pr-3 py-0.5 w-28">Provinsi</td><td class="pr-2 py-0.5">:</td><td class="font-bold py-0.5">35 . JAWA TIMUR</td></tr>
                            <tr><td class="font-bold pr-3 py-0.5">Kab./Kota</td><td class="pr-2 py-0.5">:</td><td class="font-bold py-0.5">35 . 25 . KABUPATEN GRESIK</td></tr>
                            <tr><td class="font-bold pr-3 py-0.5">Kecamatan</td><td class="pr-2 py-0.5">:</td><td class="font-bold py-0.5">35 . 25 . {{ $currentVillage?->district?->id ?? '1' }} . KECAMATAN {{ strtoupper($currentVillage?->district?->name ?? '-') }}</td></tr>
                            <tr><td class="font-bold pr-3 py-0.5">Desa</td><td class="pr-2 py-0.5">:</td><td class="font-bold py-0.5">{{ $kodeLokasi }} {{ strtoupper($currentVillage?->name ?? '-') }}</td></tr>
                            <tr><td class="font-bold pr-3 py-0.5">No. Kode Lokasi</td><td class="pr-2 py-0.5">:</td><td class="font-bold py-0.5">{{ $kodeLokasi }}</td></tr>
                        </tbody>
                    </table>
                </div>

                {{-- Table KIB B --}}
                @if($kibType === 'b')
                <div class="overflow-x-auto print:overflow-visible">
                    <table class="w-full border-collapse border border-black text-[9.5px] leading-tight text-black">
                        <thead>
                            <tr class="text-center font-bold bg-white">
                                <th class="border border-black p-1 w-6">NO</th>
                                <th class="border border-black p-1">JENIS/NAMA BARANG</th>
                                <th class="border border-black p-1">KODE BARANG</th>
                                <th class="border border-black p-1">REG</th>
                                <th class="border border-black p-1">MERK/TYPE</th>
                                <th class="border border-black p-1">JML</th>
                                <th class="border border-black p-1">SATUAN</th>
                                <th class="border border-black p-1">THN BELI</th>
                                <th class="border border-black p-1">HARGA (Rp)</th>
                                <th class="border border-black p-1">KONDISI</th>
                                <th class="border border-black p-1">ALAMAT</th>
                                <th class="border border-black p-1">KET.</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($items as $idx => $item)
                            <tr>
                                <td class="border border-black p-1 text-center">{{ $idx+1 }}</td>
                                <td class="border border-black p-1">{{ $item['name'] }}</td>
                                <td class="border border-black p-1 text-center whitespace-nowrap">{{ $item['code'] }}</td>
                                <td class="border border-black p-1 text-center">{{ $item['reg'] }}</td>
                                <td class="border border-black p-1">{{ $item['merk'] }}</td>
                                <td class="border border-black p-1 text-right">{{ $item['qty'] }}</td>
                                <td class="border border-black p-1 text-center">{{ $item['unit'] }}</td>
                                <td class="border border-black p-1 text-center">{{ $item['year_buy'] }}</td>
                                <td class="border border-black p-1 text-right whitespace-nowrap">{{ number_format($item['price'],2) }}</td>
                                <td class="border border-black p-1 text-center">{{ $item['condition'] }}</td>
                                <td class="border border-black p-1">{{ $item['address'] }}</td>
                                <td class="border border-black p-1">{{ $item['notes'] }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="12" class="border border-black p-3 text-center italic text-slate-500">Tidak ada data.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @endif

                {{-- Table KIB C --}}
                @if($kibType === 'c')
                <div class="overflow-x-auto print:overflow-visible">
                    <table class="w-full border-collapse border border-black text-[9.5px] leading-tight text-black">
                        <thead>
                            <tr class="text-center font-bold bg-white">
                                <th class="border border-black p-1 w-6">NO</th>
                                <th class="border border-black p-1">JENIS/NAMA BARANG</th>
                                <th class="border border-black p-1">KODE BARANG</th>
                                <th class="border border-black p-1">REG</th>
                                <th class="border border-black p-1">ALAMAT</th>
                                <th class="border border-black p-1">LUAS TANAH (M²)</th>
                                <th class="border border-black p-1">LUAS BGN (M²)</th>
                                <th class="border border-black p-1">LANTAI</th>
                                <th class="border border-black p-1">THN BANGUN</th>
                                <th class="border border-black p-1">HARGA (Rp)</th>
                                <th class="border border-black p-1">KONDISI</th>
                                <th class="border border-black p-1">KET.</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($items as $idx => $item)
                            <tr>
                                <td class="border border-black p-1 text-center">{{ $idx+1 }}</td>
                                <td class="border border-black p-1 font-medium">{{ $item['name'] }}</td>
                                <td class="border border-black p-1 text-center whitespace-nowrap">{{ $item['code'] }}</td>
                                <td class="border border-black p-1 text-center">{{ $item['reg'] }}</td>
                                <td class="border border-black p-1">{{ $item['address'] }}</td>
                                <td class="border border-black p-1 text-right">{{ number_format($item['area_land'],2) }}</td>
                                <td class="border border-black p-1 text-right">{{ number_format($item['area_build'],2) }}</td>
                                <td class="border border-black p-1 text-center">{{ $item['floors'] }}</td>
                                <td class="border border-black p-1 text-center">{{ $item['year_build'] }}</td>
                                <td class="border border-black p-1 text-right whitespace-nowrap">{{ number_format($item['price'],2) }}</td>
                                <td class="border border-black p-1 text-center">{{ $item['condition'] }}</td>
                                <td class="border border-black p-1">{{ $item['notes'] }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="12" class="border border-black p-3 text-center italic text-slate-500">Tidak ada data.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @endif

                {{-- Table KIB D --}}
                @if($kibType === 'd')
                <div class="overflow-x-auto print:overflow-visible">
                    <table class="w-full border-collapse border border-black text-[9.5px] leading-tight text-black">
                        <thead>
                            <tr class="text-center font-bold bg-white">
                                <th class="border border-black p-1 w-6">NO</th>
                                <th class="border border-black p-1">JENIS/NAMA BARANG</th>
                                <th class="border border-black p-1">KODE BARANG</th>
                                <th class="border border-black p-1">REG</th>
                                <th class="border border-black p-1">ALAMAT/LOKASI</th>
                                <th class="border border-black p-1">PANJANG (M)</th>
                                <th class="border border-black p-1">LEBAR (M)</th>
                                <th class="border border-black p-1">THN BANGUN</th>
                                <th class="border border-black p-1">ASAL-USUL</th>
                                <th class="border border-black p-1">HARGA (Rp)</th>
                                <th class="border border-black p-1">KONDISI</th>
                                <th class="border border-black p-1">KET.</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($items as $idx => $item)
                            <tr>
                                <td class="border border-black p-1 text-center">{{ $idx+1 }}</td>
                                <td class="border border-black p-1 font-medium">{{ $item['name'] }}</td>
                                <td class="border border-black p-1 text-center whitespace-nowrap">{{ $item['code'] }}</td>
                                <td class="border border-black p-1 text-center">{{ $item['reg'] }}</td>
                                <td class="border border-black p-1">{{ $item['address'] }}</td>
                                <td class="border border-black p-1 text-right">{{ number_format($item['length'],2) }}</td>
                                <td class="border border-black p-1 text-right">{{ number_format($item['width'],2) }}</td>
                                <td class="border border-black p-1 text-center">{{ $item['year_build'] }}</td>
                                <td class="border border-black p-1">{{ $item['asal'] }}</td>
                                <td class="border border-black p-1 text-right whitespace-nowrap">{{ number_format($item['price'],2) }}</td>
                                <td class="border border-black p-1 text-center">{{ $item['condition'] }}</td>
                                <td class="border border-black p-1">{{ $item['notes'] }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="12" class="border border-black p-3 text-center italic text-slate-500">Tidak ada data.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @endif

                {{-- Table KIB E --}}
                @if($kibType === 'e')
                <div class="overflow-x-auto print:overflow-visible">
                    <table class="w-full border-collapse border border-black text-[9.5px] leading-tight text-black">
                        <thead>
                            <tr class="text-center font-bold bg-white">
                                <th class="border border-black p-1 w-6">NO</th>
                                <th class="border border-black p-1">JENIS/NAMA BARANG</th>
                                <th class="border border-black p-1">KODE BARANG</th>
                                <th class="border border-black p-1">REG</th>
                                <th class="border border-black p-1">JUMLAH</th>
                                <th class="border border-black p-1">SATUAN</th>
                                <th class="border border-black p-1">THN BELI</th>
                                <th class="border border-black p-1">ALAMAT</th>
                                <th class="border border-black p-1">ASAL-USUL</th>
                                <th class="border border-black p-1">HARGA (Rp)</th>
                                <th class="border border-black p-1">KONDISI</th>
                                <th class="border border-black p-1">KET.</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($items as $idx => $item)
                            <tr>
                                <td class="border border-black p-1 text-center">{{ $idx+1 }}</td>
                                <td class="border border-black p-1 font-medium">{{ $item['name'] }}</td>
                                <td class="border border-black p-1 text-center whitespace-nowrap">{{ $item['code'] }}</td>
                                <td class="border border-black p-1 text-center">{{ $item['reg'] }}</td>
                                <td class="border border-black p-1 text-right">{{ $item['qty'] }}</td>
                                <td class="border border-black p-1 text-center">{{ $item['unit'] }}</td>
                                <td class="border border-black p-1 text-center">{{ $item['year_buy'] }}</td>
                                <td class="border border-black p-1">{{ $item['address'] }}</td>
                                <td class="border border-black p-1">{{ $item['asal'] }}</td>
                                <td class="border border-black p-1 text-right whitespace-nowrap">{{ number_format($item['price'],2) }}</td>
                                <td class="border border-black p-1 text-center">{{ $item['condition'] }}</td>
                                <td class="border border-black p-1">{{ $item['notes'] }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="12" class="border border-black p-3 text-center italic text-slate-500">Tidak ada data.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @endif

                {{-- Table KIB F --}}
                @if($kibType === 'f')
                <div class="overflow-x-auto print:overflow-visible">
                    <table class="w-full border-collapse border border-black text-[9.5px] leading-tight text-black">
                        <thead>
                            <tr class="text-center font-bold bg-white">
                                <th class="border border-black p-1 w-6">NO</th>
                                <th class="border border-black p-1">JENIS/NAMA PEKERJAAN</th>
                                <th class="border border-black p-1">KODE</th>
                                <th class="border border-black p-1">REG</th>
                                <th class="border border-black p-1">LOKASI</th>
                                <th class="border border-black p-1">KONTRAKTOR</th>
                                <th class="border border-black p-1">NO. KONTRAK</th>
                                <th class="border border-black p-1">NILAI (Rp)</th>
                                <th class="border border-black p-1">TGL MULAI</th>
                                <th class="border border-black p-1">TGL SELESAI</th>
                                <th class="border border-black p-1">PROGRESS (%)</th>
                                <th class="border border-black p-1">KET.</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($items as $idx => $item)
                            <tr>
                                <td class="border border-black p-1 text-center">{{ $idx+1 }}</td>
                                <td class="border border-black p-1 font-medium">{{ $item['name'] }}</td>
                                <td class="border border-black p-1 text-center whitespace-nowrap">{{ $item['code'] }}</td>
                                <td class="border border-black p-1 text-center">{{ $item['reg'] }}</td>
                                <td class="border border-black p-1">{{ $item['address'] }}</td>
                                <td class="border border-black p-1">{{ $item['contractor'] }}</td>
                                <td class="border border-black p-1 whitespace-nowrap">{{ $item['contract_no'] }}</td>
                                <td class="border border-black p-1 text-right whitespace-nowrap">{{ number_format($item['value'],2) }}</td>
                                <td class="border border-black p-1 text-center">{{ $item['start'] }}</td>
                                <td class="border border-black p-1 text-center">{{ $item['finish'] }}</td>
                                <td class="border border-black p-1 text-center font-bold">{{ $item['progress'] }}%</td>
                                <td class="border border-black p-1">{{ $item['notes'] }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="12" class="border border-black p-3 text-center italic text-slate-500">Tidak ada data.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @endif

                {{-- Table KIR --}}
                @if($kibType === 'kir')
                @foreach($items as $room)
                <div class="mb-6">
                    <h3 class="font-bold text-sm text-black mb-2 uppercase">Ruangan: {{ $room['room'] }}</h3>
                    <table class="w-full border-collapse border border-black text-[9.5px] leading-tight text-black">
                        <thead>
                            <tr class="text-center font-bold bg-white">
                                <th class="border border-black p-1 w-6">NO</th>
                                <th class="border border-black p-1">NAMA BARANG</th>
                                <th class="border border-black p-1">KODE BARANG</th>
                                <th class="border border-black p-1">JUMLAH</th>
                                <th class="border border-black p-1">KONDISI</th>
                                <th class="border border-black p-1">KETERANGAN</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($room['items'] as $ri => $ritem)
                            <tr>
                                <td class="border border-black p-1 text-center">{{ $ri+1 }}</td>
                                <td class="border border-black p-1">{{ $ritem['name'] }}</td>
                                <td class="border border-black p-1 text-center">{{ $ritem['code'] }}</td>
                                <td class="border border-black p-1 text-center">{{ $ritem['qty'] }}</td>
                                <td class="border border-black p-1 text-center">{{ $ritem['condition'] }}</td>
                                <td class="border border-black p-1">{{ $ritem['notes'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endforeach
                @endif
            </div>

            {{-- Page 1 Footer --}}
            <div class="mt-6 pt-1.5 flex items-center justify-between text-[8.5px] text-black border-t border-black/20">
                <p>Printed By SIPADES R3 - Laporan {{ $meta['short'] }}</p>
                <p>Halaman : 1 dari 2</p>
            </div>
        </div>

        <div class="page-break"></div>

        {{-- Page 2 - Totals & Signatures --}}
        <div class="page-sheet bg-white p-6 sm:p-10 shadow-2xl print:shadow-none border border-slate-300 print:border-none text-black font-sans text-[10.5px] leading-snug min-h-[640px] flex flex-col justify-between relative">
            <div>
                @if($kibType !== 'kir')
                <table class="w-full border-collapse border border-black text-[9.5px] text-black">
                    <tbody>
                        <tr class="font-bold">
                            <td colspan="8" class="border border-black p-1.5 text-center uppercase tracking-wider">JUMLAH HARGA</td>
                            <td class="border border-black p-1.5 text-right font-bold whitespace-nowrap">{{ number_format($totalPrice, 2) }}</td>
                            <td colspan="3" class="border border-black p-1.5"></td>
                        </tr>
                    </tbody>
                </table>
                @endif

                {{-- Signatures --}}
                <div class="mt-12 grid grid-cols-2 gap-12 text-[10.5px] text-black">
                    <div class="text-center space-y-1">
                        <p class="font-bold">MENGETAHUI</p>
                        <p class="font-bold">SEKRETARIS DESA</p>
                        <p class="text-[9.5px]">Selaku Pembantu Pengelola Barang Milik Desa</p>
                        <div class="h-24"></div>
                        <p class="font-bold underline uppercase tracking-wide">{{ $sekretarisName }}</p>
                    </div>
                    <div class="text-center space-y-1">
                        <p>{{ $signDate }}</p>
                        <p class="font-bold uppercase tracking-wide">PETUGAS / PENGURUS BARANG MILIK DESA</p>
                        <div class="h-24"></div>
                        <p class="font-bold underline uppercase tracking-wide">{{ $pengurusName }}</p>
                    </div>
                </div>
            </div>

            <div class="mt-6 pt-1.5 flex items-center justify-between text-[8.5px] text-black border-t border-black/20">
                <p>Printed By SIPADES R3 - Laporan {{ $meta['short'] }}</p>
                <p>Halaman : 2 dari 2</p>
            </div>
        </div>
    </div>

    <style>
    @media print {
        @page { size: landscape; margin: 6mm 8mm; }
        html, body { background: #ffffff !important; color: #000000 !important; margin: 0 !important; padding: 0 !important; }
        header, footer, nav, .print\:hidden { display: none !important; }
        .page-sheet { box-shadow: none !important; border: none !important; padding: 0 !important; margin: 0 !important; width: 100% !important; max-width: none !important; min-height: 98vh !important; page-break-inside: avoid !important; }
        .page-break { page-break-after: always !important; break-after: page !important; height: 0 !important; margin: 0 !important; }
        table { border-collapse: collapse !important; width: 100% !important; }
        th, td { border: 1px solid #000000 !important; color: #000000 !important; }
    }
    </style>
</div>
