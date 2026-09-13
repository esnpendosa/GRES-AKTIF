<div class="py-8 bg-[#f8fafc] min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <!-- Header Banner -->
        <div class="bg-gradient-to-r from-slate-900 via-teal-950 to-slate-900 rounded-2xl p-6 sm:p-8 text-white shadow-xl relative overflow-hidden border border-teal-800/40">
            <div class="absolute -right-10 -bottom-10 w-72 h-72 bg-teal-500/10 rounded-full blur-3xl pointer-events-none"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-teal-500/20 text-teal-300 border border-teal-500/30 text-xs font-semibold mb-3">
                        <i data-lucide="building-2" class="w-3.5 h-3.5"></i>
                        <span>Pemerintah Kabupaten Gresik &bull; Rekapitulasi Kewilayahan</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight font-heading">
                        Laporan & Rekapitulasi Aset per Desa
                    </h1>
                    <p class="text-slate-300 text-xs sm:text-sm mt-1 max-w-2xl leading-relaxed">
                        Transparansi agregasi inventaris aset daerah, pemetaan aset non-aktif, aspirasi netizen inklusif, dan status pengaktifan ekonomi baru di setiap desa se-Kabupaten Gresik.
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ route('reports.kib_a') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-semibold text-xs transition-colors border border-white/20 backdrop-blur-xs">
                        <i data-lucide="file-text" class="w-4 h-4 text-amber-300"></i>
                        <span>Buku Inventaris (KIB A)</span>
                    </a>
                    <a href="{{ route('map') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-teal-600 hover:bg-teal-500 text-white font-semibold text-xs transition-colors shadow-sm">
                        <i data-lucide="map" class="w-4 h-4"></i>
                        <span>Buka Peta Spasial GIS</span>
                    </a>
                </div>
            </div>

            <!-- Stats Bar -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4 mt-6 pt-6 border-t border-slate-700/60">
                <div class="bg-slate-800/60 backdrop-blur rounded-xl p-3.5 border border-slate-700/50">
                    <p class="text-[11px] text-slate-400 font-medium">Total Desa / Kelurahan</p>
                    <p class="text-xl sm:text-2xl font-bold text-white mt-0.5">{{ number_format($totalVillages) }}</p>
                </div>
                <div class="bg-slate-800/60 backdrop-blur rounded-xl p-3.5 border border-slate-700/50">
                    <p class="text-[11px] text-teal-400 font-medium">Total Aset Terdaftar</p>
                    <p class="text-xl sm:text-2xl font-bold text-teal-300 mt-0.5">{{ number_format($totalAssets) }}</p>
                </div>
                <div class="bg-slate-800/60 backdrop-blur rounded-xl p-3.5 border border-slate-700/50">
                    <p class="text-[11px] text-sky-400 font-medium">Laporan Masuk Warga</p>
                    <p class="text-xl sm:text-2xl font-bold text-sky-300 mt-0.5">{{ number_format($totalReports) }}</p>
                </div>
                <div class="bg-slate-800/60 backdrop-blur rounded-xl p-3.5 border border-slate-700/50">
                    <p class="text-[11px] text-amber-400 font-medium">Aspirasi & Ide AI</p>
                    <p class="text-xl sm:text-2xl font-bold text-amber-300 mt-0.5">{{ number_format($totalSuggestions) }}</p>
                </div>
            </div>
        </div>

        <!-- Filter & Search Controls -->
        <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-xs">
            <div class="grid grid-cols-1 sm:grid-cols-12 gap-3">
                <div class="sm:col-span-5 relative">
                    <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3 top-3"></i>
                    <input 
                        type="text" 
                        wire:model.live.debounce.300ms="search" 
                        placeholder="Cari nama desa/kelurahan..." 
                        class="w-full pl-9 pr-4 py-2 text-xs rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-teal-600 focus:border-transparent"
                    >
                </div>

                <div class="sm:col-span-4">
                    <select 
                        wire:model.live="selectedDistrict" 
                        class="w-full px-3 py-2 text-xs rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-teal-600 bg-white"
                    >
                        <option value="">Semua Kecamatan (16 Kecamatan)</option>
                        @foreach($districts as $d)
                            <option value="{{ $d->id }}">{{ $d->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="sm:col-span-3">
                    <select 
                        wire:model.live="statusFilter" 
                        class="w-full px-3 py-2 text-xs rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-teal-600 bg-white"
                    >
                        <option value="all">Semua Status</option>
                        <option value="has_assets">Memiliki Aset Terdata</option>
                        <option value="has_reports">Memiliki Laporan Warga</option>
                        <option value="needs_attention">Aset Butuh Optimalisasi</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Village Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($villages as $village)
                <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-xs hover:shadow-md transition-shadow flex flex-col justify-between">
                    <div>
                        <!-- Card Header -->
                        <div class="flex items-start justify-between gap-2 pb-3 border-b border-slate-100">
                            <div>
                                <h3 class="font-bold text-slate-900 text-sm hover:text-teal-700 transition-colors">
                                    Desa {{ $village->name }}
                                </h3>
                                <p class="text-[11px] text-slate-500 flex items-center gap-1 mt-0.5">
                                    <i data-lucide="map-pin" class="w-3 h-3 text-slate-400"></i>
                                    Kec. {{ $village->district->name ?? 'Gresik' }}
                                </p>
                            </div>
                            <span class="text-[10px] px-2 py-0.5 rounded-full font-bold {{ $village->non_active_assets_count > 0 ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-emerald-50 text-emerald-700 border border-emerald-200' }}">
                                {{ $village->non_active_assets_count > 0 ? $village->non_active_assets_count . ' Perlu Optimalisasi' : 'Kondisi Baik' }}
                            </span>
                        </div>

                        <!-- Card Metrics -->
                        <div class="grid grid-cols-3 gap-2 py-3.5 my-1 bg-slate-50 rounded-lg p-2.5 text-center">
                            <div>
                                <p class="text-[10px] text-slate-500 font-medium">Total Aset</p>
                                <p class="text-sm font-extrabold text-slate-800">{{ $village->assets_count }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] text-slate-500 font-medium">Non-Aktif/Rusak</p>
                                <p class="text-sm font-extrabold text-rose-600">{{ $village->non_active_assets_count }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] text-slate-500 font-medium">Laporan Warga</p>
                                <p class="text-sm font-extrabold text-teal-700">{{ $village->reports_count }}</p>
                            </div>
                        </div>

                        <!-- Valuation & Details -->
                        <div class="space-y-1.5 text-xs text-slate-600 mt-2">
                            <div class="flex items-center justify-between text-[11px]">
                                <span class="text-slate-500">Estimasi Valuasi Aset:</span>
                                <span class="font-bold text-slate-800">Rp {{ number_format($village->total_asset_value ?? 0, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between gap-2 mt-4">
                        <button 
                            wire:click="showDetail({{ $village->id }})" 
                            class="flex-1 py-1.5 px-3 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition-colors text-center"
                        >
                            Rincian Data
                        </button>
                        <a 
                            href="{{ route('reports.kib_a', $village->id) }}" 
                            class="py-1.5 px-2.5 rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-800 text-xs font-semibold transition-colors flex items-center gap-1"
                            title="Cetak Format SIPADES (KIB A)"
                        >
                            <i data-lucide="printer" class="w-3.5 h-3.5"></i>
                            <span>KIB A</span>
                        </a>
                        <a 
                            href="{{ route('map') }}?village={{ $village->id }}" 
                            class="p-1.5 rounded-lg bg-teal-50 hover:bg-teal-100 text-teal-700 transition-colors"
                            title="Buka di Peta"
                        >
                            <i data-lucide="map" class="w-4 h-4"></i>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white rounded-xl border border-slate-200 p-8 text-center">
                    <i data-lucide="inbox" class="w-10 h-10 text-slate-300 mx-auto mb-2"></i>
                    <p class="font-bold text-slate-700 text-sm">Tidak ada data desa ditemukan</p>
                    <p class="text-xs text-slate-500 mt-1">Coba ubah kata kunci pencarian atau filter kecamatan Anda.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $villages->links() }}
        </div>

        <!-- Detail Modal -->
        @if($selectedVillageDetail)
            <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
                <div class="bg-white rounded-2xl max-w-2xl w-full p-6 shadow-2xl border border-slate-200 relative animate-in fade-in zoom-in-95 duration-200">
                    <button 
                        wire:click="closeDetail" 
                        class="absolute top-4 right-4 p-1.5 rounded-full text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition-colors"
                    >
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>

                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-700 flex items-center justify-center font-bold">
                            <i data-lucide="building" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900 text-base">
                                Rekapitulasi Desa {{ $selectedVillageDetail['village']->name }}
                            </h3>
                            <p class="text-xs text-slate-500">
                                Kecamatan {{ $selectedVillageDetail['village']->district->name ?? 'Gresik' }} &bull; Kabupaten Gresik
                            </p>
                        </div>
                    </div>

                    <!-- Summary Stats inside Modal -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 bg-slate-50 p-3 rounded-xl mb-4 text-center">
                        <div>
                            <span class="text-[10px] text-slate-500">Aset Aktif</span>
                            <p class="font-bold text-sm text-emerald-600">{{ $selectedVillageDetail['active_assets'] }}</p>
                        </div>
                        <div>
                            <span class="text-[10px] text-slate-500">Aset Non-Aktif</span>
                            <p class="font-bold text-sm text-rose-600">{{ $selectedVillageDetail['non_active_assets'] }}</p>
                        </div>
                        <div>
                            <span class="text-[10px] text-slate-500">Laporan Warga</span>
                            <p class="font-bold text-sm text-sky-600">{{ $selectedVillageDetail['village']->reports->count() }}</p>
                        </div>
                        <div>
                            <span class="text-[10px] text-slate-500">Ide & Aspirasi Netizen</span>
                            <p class="font-bold text-sm text-amber-600">{{ $selectedVillageDetail['suggestions_count'] }}</p>
                        </div>
                    </div>

                    <!-- Assets List -->
                    <div class="space-y-3">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-700">Daftar Inventaris Aset Desa:</h4>
                        <div class="max-h-60 overflow-y-auto space-y-2 pr-1">
                            @forelse($selectedVillageDetail['village']->assets as $asset)
                                <div class="p-2.5 rounded-lg border border-slate-100 bg-slate-50/50 flex items-center justify-between text-xs">
                                    <div>
                                        <p class="font-bold text-slate-800">{{ $asset->name }}</p>
                                        <p class="text-[10px] text-slate-500">{{ $asset->category->name ?? 'Aset' }} &bull; Luas: {{ $asset->area_sqm ?? '-' }} m²</p>
                                    </div>
                                    <div class="text-right">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $asset->condition === 'baik' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                            {{ ucfirst(str_replace('_', ' ', $asset->condition)) }}
                                        </span>
                                        <p class="text-[10px] text-slate-500 mt-0.5">Rp {{ number_format($asset->estimated_value ?? 0, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-xs text-slate-400 italic py-2 text-center">Belum ada aset terdaftar di desa ini.</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="mt-6 pt-4 border-t border-slate-100 flex flex-wrap items-center justify-end gap-2">
                        <button 
                            wire:click="closeDetail" 
                            class="px-4 py-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold"
                        >
                            Tutup
                        </button>
                        <a 
                            href="{{ route('reports.kib_a', $selectedVillageDetail['village']->id) }}" 
                            class="px-4 py-2 rounded-lg bg-amber-600 hover:bg-amber-700 text-white text-xs font-semibold flex items-center gap-1.5"
                        >
                            <i data-lucide="printer" class="w-3.5 h-3.5"></i>
                            <span>Cetak Format SIPADES (KIB A)</span>
                        </a>
                        <a 
                            href="{{ route('map') }}?village={{ $selectedVillageDetail['village']->id }}" 
                            class="px-4 py-2 rounded-lg bg-teal-700 hover:bg-teal-800 text-white text-xs font-semibold flex items-center gap-1.5"
                        >
                            <i data-lucide="map" class="w-3.5 h-3.5"></i>
                            <span>Buka di Peta Spasial</span>
                        </a>
                    </div>
                </div>
            </div>
        @endif

    </div>
</div>
