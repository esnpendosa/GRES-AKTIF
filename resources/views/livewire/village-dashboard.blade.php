<div class="space-y-4" wire:poll.10s>

    <!-- TOP KPI METRIC STRIP (DYNAMIC DATABASE INTEGRATION) -->
    <div class="bg-white rounded-2xl p-4 border border-slate-200/90 shadow-xs">
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 divide-y md:divide-y-0 md:divide-x divide-slate-100 items-center">
            
            <!-- Group 1: ASSET HEALTH -->
            <div class="pr-0 md:pr-4 space-y-2">
                <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-800">KESEHATAN ASET</span>
                <div class="space-y-1.5 text-xs">
                    <div class="flex items-center justify-between gap-3">
                        <span class="text-slate-500 text-[11px] w-24 shrink-0">Kesesuaian KIB</span>
                        <div class="flex-1 bg-slate-100 h-2.5 rounded-full overflow-hidden">
                            <div class="bg-blue-600 h-full rounded-full transition-all" style="width: {{ $compliancePercentage }}%"></div>
                        </div>
                        <span class="text-blue-600 font-bold text-xs w-9 text-right">{{ $compliancePercentage }}%</span>
                    </div>
                    <div class="flex items-center justify-between gap-3">
                        <span class="text-slate-500 text-[11px] w-24 shrink-0">Produktivitas</span>
                        <div class="flex-1 bg-slate-100 h-2.5 rounded-full overflow-hidden">
                            <div class="bg-emerald-600 h-full rounded-full transition-all" style="width: {{ $operationalPercentage }}%"></div>
                        </div>
                        <span class="text-emerald-600 font-bold text-xs w-9 text-right">{{ $operationalPercentage }}%</span>
                    </div>
                </div>
            </div>

            <!-- Group 2: TASKS & VERIFICATION -->
            <div class="px-0 md:px-4 space-y-2 pt-3 md:pt-0">
                <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-800">TUGAS & LAPORAN</span>
                <div class="grid grid-cols-3 gap-2 text-center">
                    <div>
                        <span class="text-[10px] text-slate-500 block mb-1">Verifikasi</span>
                        <span class="inline-flex items-center justify-center w-full py-1 rounded-lg bg-teal-600 text-white font-bold text-xs">{{ $pendingReportsCount }}</span>
                    </div>
                    <div>
                        <span class="text-[10px] text-slate-500 block mb-1">Total Laporan</span>
                        <span class="inline-flex items-center justify-center w-full py-1 rounded-lg bg-blue-600 text-white font-bold text-xs">{{ $totalReportsCount }}</span>
                    </div>
                    <div>
                        <span class="text-[10px] text-slate-500 block mb-1">Aset Rusak</span>
                        <span class="inline-flex items-center justify-center w-full py-1 rounded-lg bg-amber-600 text-white font-bold text-xs">{{ $unusedAssets }}</span>
                    </div>
                </div>
            </div>

            <!-- Group 3: ASSET VALUE -->
            <div class="px-0 md:px-4 space-y-2 pt-3 md:pt-0">
                <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-800">VALUASI ASET DESA</span>
                <div class="grid grid-cols-2 gap-2 text-center text-xs">
                    <div>
                        <span class="text-[10px] text-slate-500 block mb-0.5">Estimasi Nilai</span>
                        <span class="inline-block px-2 py-1 bg-slate-100 rounded text-slate-800 font-bold text-[11px]">Rp {{ number_format($totalValuation / 1000000, 0, ',', '.') }} Jt</span>
                    </div>
                    <div>
                        <span class="text-[10px] text-slate-500 block mb-0.5">Total Luas</span>
                        <span class="inline-block px-2 py-1 bg-slate-100 rounded text-teal-700 font-bold text-[11px]">{{ number_format($totalArea, 0, ',', '.') }} m²</span>
                    </div>
                </div>
            </div>

            <!-- Group 4: TOTAL ASSETS -->
            <div class="pl-0 md:pl-4 space-y-2 pt-3 md:pt-0">
                <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-800">TOTAL INVENTARIS</span>
                <div class="flex items-center gap-1.5 justify-between">
                    <div class="text-center">
                        <span class="text-[10px] text-slate-500 block mb-0.5">Total</span>
                        <span class="inline-block px-2 py-1 border-2 border-teal-500 text-teal-700 font-extrabold text-xs rounded-md">{{ $totalAssets }}</span>
                    </div>
                    <div class="text-center">
                        <span class="text-[10px] text-slate-500 block mb-0.5">Aktif</span>
                        <span class="inline-block px-2.5 py-1 bg-emerald-600 text-white font-extrabold text-xs rounded-md">{{ $productiveAssets }}</span>
                    </div>
                    <div class="text-center">
                        <span class="text-[10px] text-slate-500 block mb-0.5">Non-Aktif</span>
                        <span class="inline-block px-2.5 py-1 bg-amber-500 text-white font-extrabold text-xs rounded-md">{{ $underutilizedAssets + $unusedAssets }}</span>
                    </div>
                    @if(!auth()->user()?->isDistrictAdmin())
                        <button type="button" wire:click="openCreateAssetModal" class="w-7 h-7 rounded-full border border-slate-300 hover:border-teal-600 hover:text-teal-600 flex items-center justify-center text-slate-400 transition-colors shrink-0 self-end mb-0.5 cursor-pointer" title="Tambah Aset">
                            <i data-lucide="plus" class="w-4 h-4"></i>
                        </button>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <!-- MAIN BODY (CENTER MAP & DATA TABLE + RIGHT ANALYTICS SIDEBAR) -->
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-5 items-start">
        
        <!-- LEFT & CENTER (8 COLS: GIS MAP WITH LIVE FLOATING CARDS + DATA TABLE) -->
        <div class="xl:col-span-8 space-y-4">
            
            <!-- GIS MAP CONTAINER WITH FLOATING CARDS OVERLAY -->
            <div class="relative bg-white rounded-2xl border border-slate-200/90 overflow-hidden shadow-xs" x-data="{ cardInspection: true, cardRoutine: true }">
                
                <!-- Data aset & batas desa untuk peta (dibaca oleh JS di admin.blade.php) -->
                <div id="village-asset-data" wire:ignore
                     data-assets="{{ json_encode($assets->filter(fn($a) => $a->latitude && $a->longitude)->map(fn($a) => [
                         'lat'       => (float) $a->latitude,
                         'lng'       => (float) $a->longitude,
                         'name'      => $a->name,
                         'condition' => $a->condition ?? 'produktif',
                         'category'  => optional($a->category)->name ?? '-',
                         'id'        => $a->id,
                     ])->values()) }}"
                     data-boundary="{{ json_encode($village?->getBoundaryCoords() ?? []) }}"
                     data-village-name="{{ $village?->name ?? 'Desa' }}"
                     class="hidden"></div>

                <!-- Leaflet Map Container -->
                <div 
                    id="referenceVillageMap" 
                    wire:ignore 
                    class="h-[380px] w-full z-0 bg-[#e5e9ec]"
                ></div>

                <!-- TOMBOL PULIHKAN KARTU JIKA DITUTUP -->
                <button 
                    type="button" 
                    @click="cardInspection = true; cardRoutine = true" 
                    x-show="!cardInspection || !cardRoutine" 
                    class="absolute top-3 left-14 z-10 px-2.5 py-1 bg-white/95 hover:bg-white text-slate-700 text-[11px] font-bold rounded-lg shadow-md border border-slate-200 flex items-center gap-1.5 transition-all cursor-pointer select-none"
                    title="Buka kembali kartu informasi"
                >
                    <svg class="w-3.5 h-3.5 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Tampilkan Kartu Info</span>
                </button>

                <!-- FLOATING OVERLAY CARD 1: LIVE INSPECTION (TOP-RIGHT ON MAP) -->
                <div 
                    class="absolute top-3 right-3 z-10 w-80 bg-white/95 backdrop-blur-xs rounded-xl border border-[#00c9a7] shadow-xl p-3 text-xs space-y-2 select-none transition-all" 
                    x-show="cardInspection" 
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                >
                    <div class="flex items-start justify-between">
                        <div class="flex items-center gap-1.5">
                            <span class="px-1.5 py-0.5 bg-[#00c9a7] text-white font-extrabold text-[10px] rounded uppercase">TERVERIFIKASI</span>
                            <span class="px-1.5 py-0.5 bg-cyan-500 text-white font-bold text-[10px] rounded uppercase">INSPEKSI</span>
                            <span class="px-1.5 py-0.5 bg-slate-100 text-slate-700 font-bold text-[10px] rounded uppercase">DALAM PROSES</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-5 h-5 rounded-full bg-slate-200 overflow-hidden shrink-0">
                                <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=100&q=80" alt="Avatar" class="w-full h-full object-cover"/>
                            </div>
                            <button 
                                type="button" 
                                @click="cardInspection = false" 
                                class="w-5 h-5 rounded-full bg-slate-100 hover:bg-rose-100 text-slate-400 hover:text-rose-600 flex items-center justify-center transition-colors cursor-pointer shrink-0" 
                                title="Tutup Kartu Ini"
                            >
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <img src="https://images.unsplash.com/photo-1513694203232-719a280e022f?auto=format&fit=crop&w=300&q=80" alt="Asset" class="w-14 h-12 rounded-lg object-cover shrink-0 border border-slate-100"/>
                        <div class="overflow-hidden">
                            <h4 class="font-bold text-slate-900 truncate">Gedung Pertemuan Sukomulyo</h4>
                            <p class="text-[11px] text-slate-500 truncate">Desa Sukomulyo, Manyar</p>
                        </div>
                    </div>

                    <div class="flex items-center justify-between text-[10px] text-slate-400 pt-1 border-t border-slate-100">
                        <span>ID Aset: 1850</span>
                        <a href="{{ route('assets.show', 'gedung-serbaguna-desa-sukomulyo') }}" class="font-bold text-teal-700 hover:underline">Detail Aset &rarr;</a>
                    </div>
                </div>

                <!-- FLOATING OVERLAY CARD 2: LIVE ROUTINE PROGRESS (BOTTOM-RIGHT ON MAP) -->
                <div 
                    class="absolute bottom-3 right-3 z-10 w-80 bg-white/95 backdrop-blur-xs rounded-xl border border-blue-500 shadow-xl p-3 text-xs space-y-2 select-none transition-all" 
                    x-show="cardRoutine" 
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                >
                    <div class="flex items-start justify-between">
                        <div class="flex items-center gap-1.5">
                            <span class="px-1.5 py-0.5 bg-blue-600 text-white font-extrabold text-[10px] rounded uppercase">PROGRAM KERJA</span>
                            <span class="px-1.5 py-0.5 bg-slate-100 text-slate-700 font-bold text-[10px] rounded uppercase">TERJADWAL</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="flex -space-x-1.5 shrink-0">
                                <img src="https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=80&q=80" class="w-5 h-5 rounded-full border border-white object-cover" />
                                <img src="https://images.unsplash.com/photo-1570295999919-56ceb5ecca61?auto=format&fit=crop&w=80&q=80" class="w-5 h-5 rounded-full border border-white object-cover" />
                                <span class="w-5 h-5 rounded-full bg-slate-200 text-slate-600 text-[9px] font-bold flex items-center justify-center border border-white">+3</span>
                            </div>
                            <button 
                                type="button" 
                                @click="cardRoutine = false" 
                                class="w-5 h-5 rounded-full bg-slate-100 hover:bg-rose-100 text-slate-400 hover:text-rose-600 flex items-center justify-center transition-colors cursor-pointer shrink-0" 
                                title="Tutup Kartu Ini"
                            >
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-lg bg-blue-50 text-blue-700 font-extrabold flex flex-col items-center justify-center shrink-0 border border-blue-100">
                            <span class="text-xs leading-none">45%</span>
                            <span class="text-[8px] text-blue-500 font-normal">PROGRES</span>
                        </div>
                        <div class="overflow-hidden">
                            <h4 class="font-bold text-slate-900 truncate">Revitalisasi Sentra UMKM BUMDes</h4>
                            <p class="text-[11px] text-slate-500">14 Pekerjaan &bull; Target: Nov 2026</p>
                        </div>
                    </div>

                    <div class="flex items-center justify-between text-[10px] text-slate-400 pt-1 border-t border-slate-100">
                        <span class="text-blue-600 font-bold">PANTAU LANGSUNG</span>
                        <span>ID: 1427</span>
                    </div>
                </div>

            </div>

            <!-- INVENTARIS ASET DESA (OTORITAS PENGELOLAAN & EDIT DESA) -->
            <div class="bg-white rounded-2xl border border-slate-200/90 overflow-hidden shadow-xs">
                
                <div class="p-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-teal-600"></span>
                            <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-800">INVENTARIS ASET DESA {{ strtoupper($village->name ?? 'SUKOMULYO') }}</h3>
                        </div>
                        <p class="text-[11px] text-slate-500 mt-0.5">Otoritas penuh Pemerintah Desa untuk mengelola, mengubah, dan menambah aset desa.</p>
                    </div>

                    <div class="flex items-center gap-2 shrink-0">
                        <a 
                            href="{{ route('reports.sipades', ['villageId' => $village->id ?? 1]) }}" 
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-800 font-bold text-xs border border-amber-200 shadow-xs transition-colors whitespace-nowrap"
                        >
                            <i data-lucide="file-stack" class="w-3.5 h-3.5"></i>
                            <span>Laporan SIPADES</span>
                        </a>

                        @if(!auth()->user()?->isDistrictAdmin())
                            <button 
                                type="button" 
                                wire:click="openCreateAssetModal" 
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-teal-700 hover:bg-teal-800 text-white font-bold text-xs shadow-xs transition-colors whitespace-nowrap"
                            >
                                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                                <span>+ Tambah Aset Desa</span>
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Table Container -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <!-- Reference Soft Teal Table Header -->
                        <thead class="bg-[#80cbc4]/70 text-slate-800 font-extrabold text-[11px] uppercase tracking-wider">
                            <tr>
                                <th class="py-2.5 px-4 font-extrabold whitespace-nowrap">NAMA ASET</th>
                                <th class="py-2.5 px-4 font-extrabold whitespace-nowrap">KATEGORI</th>
                                <th class="py-2.5 px-4 font-extrabold whitespace-nowrap">KONDISI</th>
                                <th class="py-2.5 px-4 font-extrabold whitespace-nowrap">SKOR AI</th>
                                <th class="py-2.5 px-4 font-extrabold text-right whitespace-nowrap">{{ auth()->user()?->isDistrictAdmin() ? 'STATUS' : 'AKSI PEMDES' }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @forelse($assets as $a)
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="py-3 px-4 font-semibold text-slate-900">
                                        <div class="flex items-center gap-2">
                                            <span>{{ $a->name }}</span>
                                            <a href="{{ route('assets.show', $a->slug) }}" class="text-teal-600 hover:text-teal-800" title="Lihat Detail Publik">
                                                <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                                            </a>
                                        </div>
                                        <span class="text-[10px] text-slate-400 block">{{ $a->area }} m² &bull; {{ $a->target_activation_use ?? 'Belum ditentukan' }}</span>
                                    </td>
                                    <td class="py-3 px-4 text-slate-600 font-medium">
                                        <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 text-[10px] font-bold">
                                            {{ $a->category->name ?? 'Fasilitas Umum' }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold {{ $a->condition === 'produktif' ? 'bg-emerald-100 text-emerald-800' : ($a->condition === 'kurang_produktif' ? 'bg-amber-100 text-amber-800' : 'bg-rose-100 text-rose-800') }}">
                                            {{ ucfirst(str_replace('_', ' ', $a->condition)) }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-teal-800 font-extrabold text-xs">
                                        {{ $a->potential_score ?? 85 }}/100
                                    </td>
                                    <td class="py-3 px-4 text-right space-x-1 whitespace-nowrap">
                                        @if(auth()->user()?->isDistrictAdmin())
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-sky-50 text-sky-700 text-[10px] font-bold border border-sky-200">
                                                <i data-lucide="eye" class="w-3 h-3 text-sky-600"></i>
                                                <span>Pengawasan</span>
                                            </span>
                                        @else
                                            <button 
                                                type="button" 
                                                wire:click="editAsset({{ $a->id }})" 
                                                class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-teal-50 hover:bg-teal-100 text-teal-800 font-bold text-[11px] border border-teal-200 transition-colors"
                                            >
                                                <i data-lucide="edit-2" class="w-3 h-3"></i>
                                                <span>Ubah</span>
                                            </button>
                                            <button 
                                                type="button" 
                                                wire:click="deleteAsset({{ $a->id }})" 
                                                wire:confirm="Yakin ingin menghapus aset desa ini?" 
                                                class="inline-flex items-center p-1 rounded-md text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors"
                                                title="Hapus Aset"
                                            >
                                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-6 text-center text-slate-400">Belum ada aset desa yang terdaftar.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>

        </div>

        <!-- MODAL KELOLA / UBAH ASET DESA -->
        @if($showAssetModal)
            <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs animate-fade-in">
                <div class="bg-white rounded-2xl max-w-xl w-full border border-slate-200 shadow-2xl p-6 space-y-4 max-h-[90vh] overflow-y-auto">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <div>
                            <h3 class="font-heading font-bold text-base text-slate-900">
                                {{ $editingAssetId ? 'Ubah Data Aset Desa' : 'Tambah Aset Desa Baru' }}
                            </h3>
                            <p class="text-[11px] text-slate-500">Pemerintah Desa: {{ $village->name ?? 'Sukomulyo' }}</p>
                        </div>
                        <button type="button" wire:click="$set('showAssetModal', false)" class="text-slate-400 hover:text-slate-600 p-1">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </button>
                    </div>

                    <form wire:submit="saveAsset" class="space-y-3.5 text-xs">
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Nama Aset Desa *</label>
                            <input type="text" wire:model="assetName" required class="w-full px-3.5 py-2 rounded-xl border border-slate-200 bg-white text-slate-800 text-xs focus:ring-2 focus:ring-teal-500" placeholder="Contoh: Gedung Serbaguna Desa Sukomulyo" />
                            @error('assetName') <span class="text-rose-500 text-[11px]">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Kategori Fasilitas *</label>
                                <select wire:model="assetCategoryId" required class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white text-slate-800 text-xs">
                                    @foreach($categories as $c)
                                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Kondisi Aset *</label>
                                <select wire:model="assetCondition" required class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white text-slate-800 text-xs">
                                    <option value="tidak_digunakan">Tidak Digunakan / Kosong</option>
                                    <option value="kurang_produktif">Kurang Produktif / Jarang Dipakai</option>
                                    <option value="terbengkalai">Terbengkalai</option>
                                    <option value="produktif">Produktif</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Luas Lahan / Bangunan (m²) *</label>
                                <input type="number" wire:model="assetArea" required class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white text-slate-800 text-xs" />
                            </div>

                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Rencana Fungsi Utama *</label>
                                <select wire:model="assetTargetUse" required class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white text-slate-800 text-xs">
                                    <option value="Sentra UMKM">Sentra UMKM</option>
                                    <option value="Pusat Kuliner & Pujasera">Pusat Kuliner & Pujasera</option>
                                    <option value="Pertanian / Greenhouse">Pertanian / Greenhouse</option>
                                    <option value="Balai Pelatihan & Vokasi">Balai Pelatihan & Vokasi</option>
                                    <option value="Ekowisata & Edukasi">Ekowisata & Edukasi</option>
                                    <option value="Gudang Logistik BUMDes">Gudang Logistik BUMDes</option>
                                </select>
                            </div>
                        </div>

                        <!-- PETA PEMILIHAN LOKASI ASET INTERAKTIF -->
                        <div class="space-y-1.5 pt-1" x-data="modalAssetPicker({
                            lat: {{ (float) ($assetLatitude ?? ($village?->latitude ?? -7.1350)) }},
                            lng: {{ (float) ($assetLongitude ?? ($village?->longitude ?? 112.6020)) }}
                        })">
                            <div class="flex items-center justify-between mb-1">
                                <label class="font-semibold text-slate-700 flex items-center gap-1.5">
                                    <i data-lucide="map-pin" class="w-3.5 h-3.5 text-teal-600"></i>
                                    <span>Pilih Titik Lokasi Aset di Peta *</span>
                                </label>
                                <button type="button" @click="useGPS()" class="inline-flex items-center gap-1 text-[11px] font-bold text-teal-700 hover:text-teal-900 bg-teal-50 hover:bg-teal-100 px-2.5 py-1 rounded-lg border border-teal-200 transition-colors cursor-pointer">
                                    <span>📍 Lokasi GPS Saya</span>
                                </button>
                            </div>

                            <div class="relative w-full h-48 rounded-xl border border-slate-200 overflow-hidden bg-slate-100 shadow-inner">
                                <div x-ref="modalMapContainer" wire:ignore class="w-full h-full z-0"></div>
                                <div class="absolute bottom-2 left-2 z-10 bg-white/95 backdrop-blur-xs px-2.5 py-1 rounded-md text-[10px] font-semibold text-slate-700 shadow border border-slate-200 pointer-events-none">
                                    Klik di peta atau geser pin 📍 untuk menentukan posisi aset
                                </div>
                            </div>

                            <div class="flex items-center justify-between text-[11px] bg-slate-50 p-2 rounded-xl border border-slate-200 text-slate-600">
                                <span>Lat: <strong class="text-teal-800" x-text="lat"></strong></span>
                                <span>Lng: <strong class="text-teal-800" x-text="lng"></strong></span>
                                <span class="text-[10px] text-slate-400">Otomatis sinkron</span>
                            </div>
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Alamat / Patokan Lokasi Lengkap</label>
                            <input type="text" wire:model="assetAddress" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 bg-white text-slate-800 text-xs focus:ring-2 focus:ring-teal-500" placeholder="Contoh: Jl. Sukomulyo Barat No. 12, RT 02 RW 01" />
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Deskripsi / Catatan Tambahan</label>
                            <textarea wire:model="assetDescription" rows="3" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 bg-white text-slate-800 text-xs" placeholder="Keterangan kondisi fisik, fasilitas yang tersedia, dsb..."></textarea>
                        </div>

                        <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
                            <button type="button" wire:click="$set('showAssetModal', false)" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs transition-colors">
                                Batal
                            </button>
                            <button type="submit" class="px-5 py-2 rounded-xl bg-teal-700 hover:bg-teal-800 text-white font-bold text-xs shadow-xs transition-colors">
                                {{ $editingAssetId ? 'Simpan Perubahan' : 'Tambah Aset' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        <!-- RIGHT ANALYTICS SIDEBAR (4 COLS: CATEGORIES + 2 DONUT CHARTS) -->
        <div class="xl:col-span-4 space-y-4">
            
            <!-- 1. ASSET CATEGORIES HORIZONTAL BARS -->
            <div class="bg-white rounded-2xl p-4 border border-slate-200/90 shadow-xs space-y-3">
                <h3 class="text-[11px] font-extrabold uppercase tracking-wider text-slate-800">KATEGORI ASET DESA</h3>
                
                <div class="space-y-2 text-xs">
                    <!-- Category 1 -->
                    <div>
                        <div class="flex justify-between text-[11px] text-slate-600 mb-0.5">
                            <span class="font-medium">Seni & Kebudayaan Desa</span>
                        </div>
                        <div class="w-full bg-slate-100 h-3 rounded">
                            <div class="bg-[#80cbc4] h-full rounded" style="width: 25%"></div>
                        </div>
                        <span class="text-[10px] text-slate-500">3 Unit</span>
                    </div>

                    <!-- Category 2 -->
                    <div>
                        <div class="flex justify-between text-[11px] text-slate-600 mb-0.5">
                            <span class="font-medium">Fasilitas Olahraga & Rekreasi</span>
                        </div>
                        <div class="w-full bg-slate-100 h-3 rounded">
                            <div class="bg-[#80cbc4] h-full rounded" style="width: 48%"></div>
                        </div>
                        <span class="text-[10px] text-slate-500">6 Unit</span>
                    </div>

                    <!-- Category 3 -->
                    <div>
                        <div class="flex justify-between text-[11px] text-slate-600 mb-0.5">
                            <span class="font-medium">Gedung & Balai Pertemuan</span>
                        </div>
                        <div class="w-full bg-slate-100 h-3 rounded">
                            <div class="bg-[#80cbc4] h-full rounded" style="width: 40%"></div>
                        </div>
                        <span class="text-[10px] text-slate-500">5 Unit</span>
                    </div>

                    <!-- Category 4 -->
                    <div>
                        <div class="flex justify-between text-[11px] text-slate-600 mb-0.5">
                            <span class="font-medium">Monumen & Destinasi Wisata</span>
                        </div>
                        <div class="w-full bg-slate-100 h-3 rounded">
                            <div class="bg-[#80cbc4] h-full rounded" style="width: 40%"></div>
                        </div>
                        <span class="text-[10px] text-slate-500">5 Unit</span>
                    </div>

                    <!-- Category 5 -->
                    <div>
                        <div class="flex justify-between text-[11px] text-slate-600 mb-0.5">
                            <span class="font-medium">Taman Terbuka & Ruang Hijau</span>
                        </div>
                        <div class="w-full bg-slate-100 h-3 rounded">
                            <div class="bg-[#80cbc4] h-full rounded" style="width: 85%"></div>
                        </div>
                        <span class="text-[10px] text-slate-500">5 Unit</span>
                    </div>

                    <!-- Category 6 -->
                    <div>
                        <div class="flex justify-between text-[11px] text-slate-600 mb-0.5">
                            <span class="font-medium">Keamanan & Pos Pantau Desa</span>
                        </div>
                        <div class="w-full bg-slate-100 h-3 rounded">
                            <div class="bg-[#80cbc4] h-full rounded" style="width: 38%"></div>
                        </div>
                        <span class="text-[10px] text-slate-500">5 Unit</span>
                    </div>

                    <!-- Category 7 -->
                    <div>
                        <div class="flex justify-between text-[11px] text-slate-600 mb-0.5">
                            <span class="font-medium">Kios Usaha & Sentra UMKM</span>
                        </div>
                        <div class="w-full bg-slate-100 h-3 rounded">
                            <div class="bg-[#80cbc4] h-full rounded" style="width: 65%"></div>
                        </div>
                        <span class="text-[10px] text-slate-500">5 Unit</span>
                    </div>

                    <!-- Category 8 -->
                    <div>
                        <div class="flex justify-between text-[11px] text-slate-600 mb-0.5">
                            <span class="font-medium">Sarana Olahraga Publik</span>
                        </div>
                        <div class="w-full bg-slate-100 h-3 rounded">
                            <div class="bg-[#80cbc4] h-full rounded" style="width: 38%"></div>
                        </div>
                        <span class="text-[10px] text-slate-500">5 Unit</span>
                    </div>
                </div>
            </div>

            <!-- 2. TOTAL INSURANCE VALUE BREAKDOWN (DONUT CHART) -->
            <div class="bg-white rounded-2xl p-4 border border-slate-200/90 shadow-xs space-y-3">
                <h3 class="text-[11px] font-extrabold uppercase tracking-wider text-slate-800">KOMPOSISI VALUASI ASET</h3>
                
                <div 
                    class="flex items-center justify-between"
                    x-data="{
                        init() {
                            const options = {
                                series: [65, 35],
                                chart: { type: 'donut', height: 160, sparkline: { enabled: true } },
                                colors: ['#00c9a7', '#008080'],
                                labels: ['TANAH & PROPERTI', 'SARANA & PRASARANA'],
                                plotOptions: {
                                    pie: {
                                        donut: {
                                            size: '68%',
                                            labels: { show: false }
                                        }
                                    }
                                },
                                stroke: { width: 0 },
                                tooltip: { enabled: true }
                            };
                            new ApexCharts(this.$refs.donut1, options).render();
                        }
                    }"
                >
                    <div x-ref="donut1" class="w-32 h-32 shrink-0"></div>
                    
                    <div class="space-y-2 text-[10px] font-bold text-slate-700">
                        <div class="flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-[#00c9a7] shrink-0"></span>
                            <span>TANAH & PROPERTI</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-[#008080] shrink-0"></span>
                            <span>SARANA & PRASARANA</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. ASSET CONDITION (DONUT CHART) -->
            <div class="bg-white rounded-2xl p-4 border border-slate-200/90 shadow-xs space-y-3">
                <h3 class="text-[11px] font-extrabold uppercase tracking-wider text-slate-800">STATUS KONDISI ASET</h3>
                
                <div 
                    class="flex items-center justify-between"
                    x-data="{
                        init() {
                            const options = {
                                series: [45, 15, 20, 20],
                                chart: { type: 'donut', height: 160, sparkline: { enabled: true } },
                                colors: ['#008080', '#ff5722', '#00c9a7', '#60a5fa'],
                                labels: ['BAIK / PRODUKTIF', 'RUSAK / TERLANTAR', 'BARU TERDATA', 'KURANG PRODUKTIF'],
                                plotOptions: {
                                    pie: {
                                        donut: {
                                            size: '68%',
                                            labels: { show: false }
                                        }
                                    }
                                },
                                stroke: { width: 0 },
                                tooltip: { enabled: true }
                            };
                            new ApexCharts(this.$refs.donut2, options).render();
                        }
                    }"
                >
                    <div x-ref="donut2" class="w-32 h-32 shrink-0"></div>
                    
                    <div class="grid grid-cols-1 gap-1.5 text-[10px] font-bold text-slate-700">
                        <div class="flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-[#008080] shrink-0"></span>
                            <span>BAIK / PRODUKTIF</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-[#ff5722] shrink-0"></span>
                            <span>RUSAK / TERLANTAR</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-[#60a5fa] shrink-0"></span>
                            <span>BARU TERDATA</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-[#00c9a7] shrink-0"></span>
                            <span>KURANG PRODUKTIF</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>
