<div class="py-8 bg-[#f8fafc] min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        {{-- Header Banner --}}
        <div class="bg-gradient-to-r from-slate-900 via-teal-950 to-slate-900 rounded-2xl p-6 sm:p-8 text-white shadow-xl relative overflow-hidden border border-teal-800/40">
            <div class="absolute -right-10 -bottom-10 w-72 h-72 bg-teal-500/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-teal-500/20 text-teal-300 border border-teal-500/30 text-xs font-semibold mb-3">
                        <i data-lucide="file-stack" class="w-3.5 h-3.5"></i>
                        <span>SIPADES R3 &bull; Sistem Pengelolaan Aset Desa</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight font-heading">Laporan Aset Desa (SIPADES)</h1>
                    <p class="text-slate-300 text-xs sm:text-sm mt-1 max-w-2xl leading-relaxed">
                        Pusat navigasi seluruh laporan aset desa sesuai standar Permendagri: Pengadaan, Inventarisasi, Penatausahaan, Pemanfaatan, dan Penghapusan.
                    </p>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <div class="flex items-center gap-1.5 bg-white/10 border border-white/20 rounded-lg px-3 py-1.5">
                        <i data-lucide="building-2" class="w-3.5 h-3.5 text-teal-300"></i>
                        <select wire:model.live="selectedVillageId" class="bg-transparent text-white text-xs font-bold focus:outline-none cursor-pointer">
                            @foreach($villages as $v)
                                <option value="{{ $v->id }}" class="text-slate-800">{{ $v->name }} &mdash; Kec. {{ $v->district->name ?? '' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <a href="{{ route('reports.villages') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-teal-600 hover:bg-teal-500 text-white font-semibold text-xs transition-colors shadow-sm">
                        <i data-lucide="table" class="w-4 h-4"></i>
                        <span>Rekap Per Desa</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- Interactive Quick Guide Banner --}}
        <div x-data="{ openGuide: true }" class="bg-gradient-to-r from-teal-50 via-cyan-50 to-sky-50 rounded-2xl border border-teal-200/80 p-5 shadow-xs transition-all">
            <div class="flex items-center justify-between cursor-pointer" @click="openGuide = !openGuide">
                <div class="flex items-center gap-2.5">
                    <div class="w-7 h-7 rounded-lg bg-teal-600 text-white flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-heading font-bold text-xs sm:text-sm text-slate-900">Panduan Praktis Pengoperasian SIPADES</h3>
                        <p class="text-[11px] text-slate-600">3 langkah mudah melihat, mengaudit, dan mencetak dokumen legal aset desa.</p>
                    </div>
                </div>
                <button type="button" class="text-xs font-bold text-teal-800 flex items-center gap-1 hover:text-teal-950">
                    <span x-text="openGuide ? 'Tutup Panduan' : 'Buka Panduan'"></span>
                    <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': openGuide }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                </button>
            </div>

            <div x-show="openGuide" x-transition class="grid grid-cols-1 md:grid-cols-3 gap-3.5 mt-4 pt-4 border-t border-teal-200/60 text-xs">
                <!-- Step 1 -->
                <div class="p-3.5 rounded-xl bg-white/90 border border-teal-100 shadow-2xs space-y-1.5">
                    <div class="flex items-center gap-2">
                        <span class="w-5 h-5 rounded-full bg-teal-600 text-white font-black text-[10px] flex items-center justify-center">1</span>
                        <h4 class="font-bold text-slate-900 text-xs">Pilih Desa Sasaran</h4>
                    </div>
                    <p class="text-[11px] text-slate-600 leading-relaxed">
                        Gunakan tombol dropdown di pojok kanan banner atas (saat ini: <strong>{{ $currentVillage ? $currentVillage->name : 'Sukomulyo' }}</strong>) untuk memilih desa yang ingin Anda periksa datanya.
                    </p>
                </div>

                <!-- Step 2 -->
                <div class="p-3.5 rounded-xl bg-white/90 border border-teal-100 shadow-2xs space-y-1.5">
                    <div class="flex items-center gap-2">
                        <span class="w-5 h-5 rounded-full bg-teal-600 text-white font-black text-[10px] flex items-center justify-center">2</span>
                        <h4 class="font-bold text-slate-900 text-xs">Pilih Kategori Dokumen</h4>
                    </div>
                    <p class="text-[11px] text-slate-600 leading-relaxed">
                        Klik salah satu menu laporan di bawah:
                        <br>&bull; <strong class="text-blue-700">Pengadaan</strong>: Belanja aset & asal sumber dana.
                        <br>&bull; <strong class="text-amber-700">KIB & LHI</strong>: Buku induk tanah (KIB A), gedung, alat mesin.
                        <br>&bull; <strong class="text-rose-700">Pemanfaatan</strong>: Aset sewa BUMDes / penghapusan.
                    </p>
                </div>

                <!-- Step 3 -->
                <div class="p-3.5 rounded-xl bg-white/90 border border-teal-100 shadow-2xs space-y-1.5">
                    <div class="flex items-center gap-2">
                        <span class="w-5 h-5 rounded-full bg-teal-600 text-white font-black text-[10px] flex items-center justify-center">3</span>
                        <h4 class="font-bold text-slate-900 text-xs">Cetak / Ekspor Laporan</h4>
                    </div>
                    <p class="text-[11px] text-slate-600 leading-relaxed">
                        Di dalam halaman detail tabel, Anda dapat memverifikasi rincian aset dan mengunduh format resmi Permendagri dengan tombol <strong>Cetak PDF</strong> atau <strong>Ekspor Excel</strong> untuk audit SPJ.
                    </p>
                </div>
            </div>
        </div>

        {{-- Menu Groups --}}
        @foreach($menus as $group)
            @php
                $colorMap = [
                    'blue'  => ['header' => 'bg-blue-50 border-blue-200',   'icon' => 'text-blue-600',    'title' => 'text-blue-900'],
                    'amber' => ['header' => 'bg-amber-50 border-amber-200', 'icon' => 'text-amber-600',   'title' => 'text-amber-900'],
                    'rose'  => ['header' => 'bg-rose-50 border-rose-200',   'icon' => 'text-rose-600',    'title' => 'text-rose-900'],
                    'teal'  => ['header' => 'bg-teal-50 border-teal-200',   'icon' => 'text-teal-600',    'title' => 'text-teal-900'],
                ];
                $c = $colorMap[$group['color']] ?? $colorMap['teal'];
            @endphp
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs">
                <div class="flex items-center gap-3 px-5 py-4 border-b {{ $c['header'] }} border-opacity-70">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-white/60">
                        <i data-lucide="{{ $group['icon'] }}" class="w-4.5 h-4.5 {{ $c['icon'] }}"></i>
                    </div>
                    <h2 class="font-bold text-sm {{ $c['title'] }} tracking-tight">{{ $group['label'] }}</h2>
                    <span class="ml-auto text-[10px] font-bold text-slate-500">{{ count($group['items']) }} Laporan</span>
                </div>
                <div class="divide-y divide-slate-100">
                    @foreach($group['items'] as $item)
                        @php
                            $badgeColors = [
                                'teal'    => 'bg-teal-100 text-teal-800 border-teal-300',
                                'amber'   => 'bg-amber-100 text-amber-800 border-amber-300',
                                'blue'    => 'bg-blue-100 text-blue-800 border-blue-300',
                                'indigo'  => 'bg-indigo-100 text-indigo-800 border-indigo-300',
                                'purple'  => 'bg-purple-100 text-purple-800 border-purple-300',
                                'emerald' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
                                'rose'    => 'bg-rose-100 text-rose-800 border-rose-300',
                            ];
                            $bc = $badgeColors[$item['color']] ?? $badgeColors['teal'];
                        @endphp
                        <a
                            href="{{ route($item['route'], ['villageId' => $selectedVillageId]) }}"
                            class="flex items-center gap-4 px-5 py-3.5 hover:bg-slate-50 transition-colors group"
                        >
                            <div class="w-7 h-7 rounded-md bg-slate-100 group-hover:bg-teal-50 flex items-center justify-center flex-shrink-0 transition-colors">
                                <i data-lucide="{{ $item['icon'] }}" class="w-3.5 h-3.5 text-slate-500 group-hover:text-teal-600 transition-colors"></i>
                            </div>
                            <span class="flex-1 text-xs text-slate-700 font-medium group-hover:text-slate-900 transition-colors">{{ $item['label'] }}</span>
                            <span class="text-[9.5px] px-2 py-0.5 rounded border font-bold flex-shrink-0 {{ $bc }}">{{ $item['badge'] }}</span>
                            <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-300 group-hover:text-teal-500 flex-shrink-0 transition-colors"></i>
                        </a>
                    @endforeach
                </div>
            </div>
        @endforeach

        <div class="pb-4"></div>
    </div>
</div>
