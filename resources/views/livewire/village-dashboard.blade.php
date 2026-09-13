<div class="space-y-4">

    <!-- TOP KPI METRIC STRIP (EXACT REFERENCE DESIGN) -->
    <div class="bg-white rounded-2xl p-4 border border-slate-200/90 shadow-xs">
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 divide-y md:divide-y-0 md:divide-x divide-slate-100 items-center">
            
            <!-- Group 1: ASSET HEALTH -->
            <div class="pr-0 md:pr-4 space-y-2">
                <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-800">ASSET HEALTH</span>
                <div class="space-y-1.5 text-xs">
                    <div class="flex items-center justify-between gap-3">
                        <span class="text-slate-500 text-[11px] w-20 shrink-0">Compliance</span>
                        <div class="flex-1 bg-slate-100 h-2.5 rounded-full overflow-hidden">
                            <div class="bg-blue-600 h-full rounded-full" style="width: 60%"></div>
                        </div>
                        <span class="text-blue-600 font-bold text-xs w-8 text-right">60%</span>
                    </div>
                    <div class="flex items-center justify-between gap-3">
                        <span class="text-slate-500 text-[11px] w-20 shrink-0">Operational</span>
                        <div class="flex-1 bg-slate-100 h-2.5 rounded-full overflow-hidden">
                            <div class="bg-[#ff5722] h-full rounded-full" style="width: 20%"></div>
                        </div>
                        <span class="text-[#ff5722] font-bold text-xs w-8 text-right">20%</span>
                    </div>
                </div>
            </div>

            <!-- Group 2: TASKS -->
            <div class="px-0 md:px-4 space-y-2 pt-3 md:pt-0">
                <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-800">TASKS</span>
                <div class="grid grid-cols-3 gap-2 text-center">
                    <div>
                        <span class="text-[10px] text-slate-500 block mb-1">Inspections</span>
                        <span class="inline-flex items-center justify-center w-full py-1 rounded-lg bg-[#00c9a7] text-white font-bold text-xs">12</span>
                    </div>
                    <div>
                        <span class="text-[10px] text-slate-500 block mb-1">Jobs</span>
                        <span class="inline-flex items-center justify-center w-full py-1 rounded-lg bg-[#0284c7] text-white font-bold text-xs">44</span>
                    </div>
                    <div>
                        <span class="text-[10px] text-slate-500 block mb-1">Defects</span>
                        <span class="inline-flex items-center justify-center w-full py-1 rounded-lg bg-[#ff5722] text-white font-bold text-xs">5</span>
                    </div>
                </div>
            </div>

            <!-- Group 3: ASSET VALUE -->
            <div class="px-0 md:px-4 space-y-2 pt-3 md:pt-0">
                <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-800">ASSET VALUE</span>
                <div class="grid grid-cols-3 gap-2 text-center text-xs">
                    <div>
                        <span class="text-[10px] text-slate-500 block mb-0.5">Purchase</span>
                        <span class="inline-block px-2 py-1 bg-slate-100 rounded text-slate-800 font-bold text-[11px]">Rp 50M</span>
                    </div>
                    <div>
                        <span class="text-[10px] text-slate-500 block mb-0.5">Insurance</span>
                        <span class="inline-block px-2 py-1 bg-slate-100 rounded text-slate-800 font-bold text-[11px]">Rp 85M</span>
                    </div>
                    <div>
                        <span class="text-[10px] text-slate-500 block mb-0.5">Difference</span>
                        <span class="inline-block px-2 py-1 bg-slate-100 rounded text-emerald-700 font-bold text-[11px]">+15.15%</span>
                    </div>
                </div>
            </div>

            <!-- Group 4: TOTAL ASSETS -->
            <div class="pl-0 md:pl-4 space-y-2 pt-3 md:pt-0">
                <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-800">TOTAL ASSETS</span>
                <div class="flex items-center gap-1.5 justify-between">
                    <div class="text-center">
                        <span class="text-[10px] text-slate-500 block mb-0.5">Count</span>
                        <span class="inline-block px-2 py-1 border-2 border-cyan-500 text-cyan-600 font-extrabold text-xs rounded-md">73</span>
                    </div>
                    <div class="text-center">
                        <span class="text-[10px] text-slate-500 block mb-0.5">Active</span>
                        <span class="inline-block px-2.5 py-1 bg-teal-600 text-white font-extrabold text-xs rounded-md">28</span>
                    </div>
                    <div class="text-center">
                        <span class="text-[10px] text-slate-500 block mb-0.5">Inactive</span>
                        <span class="inline-block px-2.5 py-1 bg-cyan-500 text-white font-extrabold text-xs rounded-md">0</span>
                    </div>
                    <div class="text-center">
                        <span class="text-[10px] text-slate-500 block mb-0.5">Disposed</span>
                        <span class="inline-block px-2.5 py-1 bg-slate-400 text-white font-extrabold text-xs rounded-md">45</span>
                    </div>
                    <a href="{{ route('reports.create') }}" class="w-7 h-7 rounded-full border border-slate-300 hover:border-teal-600 hover:text-teal-600 flex items-center justify-center text-slate-400 transition-colors shrink-0 self-end mb-0.5" title="Tambah Aset">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                    </a>
                </div>
            </div>

        </div>
    </div>

    <!-- MAIN BODY (CENTER MAP & DATA TABLE + RIGHT ANALYTICS SIDEBAR) -->
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-5 items-start">
        
        <!-- LEFT & CENTER (8 COLS: GIS MAP WITH LIVE FLOATING CARDS + DATA TABLE) -->
        <div class="xl:col-span-8 space-y-4">
            
            <!-- GIS MAP CONTAINER WITH FLOATING CARDS OVERLAY -->
            <div class="relative bg-white rounded-2xl border border-slate-200/90 overflow-hidden shadow-xs">
                
                <!-- Leaflet Map Container -->
                <div 
                    id="referenceVillageMap" 
                    wire:ignore 
                    class="h-[380px] w-full z-0 bg-[#e5e9ec]"
                    x-data="{
                        init() {
                            const map = L.map('referenceVillageMap', {
                                zoomControl: true,
                                attributionControl: false
                            }).setView([-7.1350, 112.6020], 15);

                            // Google Earth Satellite Hybrid + Standard OSM layer
                            const googleEarth = L.tileLayer('https://mt1.google.com/vt/lyrs=y&x={x}&y={y}&z={z}', {
                                maxZoom: 20,
                                subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
                            }).addTo(map);

                            const streetMap = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                maxZoom: 19
                            });

                            L.control.layers({
                                '🛰️ Google Earth': googleEarth,
                                '🗺️ Peta Jalan': streetMap
                            }, null, { position: 'topright' }).addTo(map);

                            // Authentic GIS Geo-boundary Polygon (Sukomulyo Zone)
                            const polygonCoords = [
                                [-7.1320, 112.5980],
                                [-7.1300, 112.6040],
                                [-7.1380, 112.6080],
                                [-7.1410, 112.6020],
                                [-7.1360, 112.5960]
                            ];
                            L.polygon(polygonCoords, {
                                color: '#0284c7',
                                weight: 2,
                                fillColor: '#0284c7',
                                fillOpacity: 0.06,
                                dashArray: '4, 4'
                            }).addTo(map);

                            // Reference style teal and orange dots
                            const markers = [
                                { lat: -7.1330, lng: 112.6000, color: '#00c9a7' },
                                { lat: -7.1310, lng: 112.6030, color: '#00c9a7' },
                                { lat: -7.1325, lng: 112.6050, color: '#00c9a7' },
                                { lat: -7.1370, lng: 112.6010, color: '#ff5722' },
                                { lat: -7.1390, lng: 112.6040, color: '#00c9a7' },
                                { lat: -7.1400, lng: 112.6070, color: '#00c9a7' },
                                { lat: -7.1345, lng: 112.6065, color: '#00c9a7' },
                                { lat: -7.1365, lng: 112.5990, color: '#00c9a7' },
                            ];

                            markers.forEach(m => {
                                const dotIcon = L.divIcon({
                                    className: 'custom-dot',
                                    html: `<div style='background-color: ${m.color}; width: 10px; height: 10px; border-radius: 50%; border: 2px solid white; box-shadow: 0 1px 4px rgba(0,0,0,0.3);'></div>`,
                                    iconSize: [10, 10],
                                    iconAnchor: [5, 5]
                                });
                                L.marker([m.lat, m.lng], { icon: dotIcon }).addTo(map);
                            });
                        }
                    }"
                ></div>

                <!-- FLOATING OVERLAY CARD 1: LIVE INSPECTION (TOP-RIGHT ON MAP) -->
                <div class="absolute top-4 right-4 z-10 w-80 bg-white rounded-xl border border-[#00c9a7] shadow-xl p-3 text-xs space-y-2 select-none" x-data="{ open: true }" x-show="open">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center gap-2">
                            <span class="px-1.5 py-0.5 bg-[#00c9a7] text-white font-extrabold text-[10px] rounded uppercase">LIVE</span>
                            <span class="px-1.5 py-0.5 bg-cyan-500 text-white font-bold text-[10px] rounded uppercase">INS</span>
                            <span class="px-1.5 py-0.5 bg-slate-100 text-slate-700 font-bold text-[10px] rounded uppercase">IN PROGRESS</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <div class="w-5 h-5 rounded-full bg-slate-200 overflow-hidden">
                                <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=100&q=80" alt="Avatar" class="w-full h-full object-cover"/>
                            </div>
                            <button @click="open = false" class="text-slate-400 hover:text-slate-600">
                                <i data-lucide="x" class="w-3.5 h-3.5"></i>
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
                        <span>ID: 1850</span>
                        <a href="{{ route('assets.show', 'gedung-serbaguna-desa-sukomulyo') }}" class="font-bold text-teal-600 hover:underline">Detail Aset &rarr;</a>
                    </div>
                </div>

                <!-- FLOATING OVERLAY CARD 2: LIVE ROUTINE PROGRESS (CENTER-LEFT ON MAP) -->
                <div class="absolute bottom-4 right-8 z-10 w-84 bg-white rounded-xl border border-blue-500 shadow-xl p-3 text-xs space-y-2 select-none" x-data="{ open: true }" x-show="open">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center gap-2">
                            <span class="px-1.5 py-0.5 bg-blue-600 text-white font-extrabold text-[10px] rounded uppercase">ROUTINE</span>
                            <span class="px-1.5 py-0.5 bg-slate-100 text-slate-700 font-bold text-[10px] rounded uppercase">TO DO</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <div class="flex -space-x-1.5">
                                <img src="https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=80&q=80" class="w-5 h-5 rounded-full border border-white object-cover" />
                                <img src="https://images.unsplash.com/photo-1570295999919-56ceb5ecca61?auto=format&fit=crop&w=80&q=80" class="w-5 h-5 rounded-full border border-white object-cover" />
                                <span class="w-5 h-5 rounded-full bg-slate-200 text-slate-600 text-[9px] font-bold flex items-center justify-center border border-white">+3</span>
                            </div>
                            <button @click="open = false" class="text-slate-400 hover:text-slate-600 ml-1">
                                <i data-lucide="x" class="w-3.5 h-3.5"></i>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-lg bg-blue-50 text-blue-700 font-extrabold flex flex-col items-center justify-center shrink-0 border border-blue-100">
                            <span class="text-xs leading-none">45%</span>
                            <span class="text-[8px] text-blue-500 font-normal">COMPLETE</span>
                        </div>
                        <div class="overflow-hidden">
                            <h4 class="font-bold text-slate-900 truncate">Revitalisasi Sentra UMKM BUMDes</h4>
                            <p class="text-[11px] text-slate-500">14 Jobs &bull; Target: Nov 2026</p>
                        </div>
                    </div>

                    <div class="flex items-center justify-between text-[10px] text-slate-400 pt-1 border-t border-slate-100">
                        <span class="text-blue-600 font-bold">LIVE 05:11</span>
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

                    <button 
                        type="button" 
                        wire:click="openCreateAssetModal" 
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-teal-700 hover:bg-teal-800 text-white font-bold text-xs shadow-xs transition-colors shrink-0"
                    >
                        <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                        <span>+ Tambah Aset Desa</span>
                    </button>
                </div>

                <!-- Table Container -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <!-- Reference Soft Teal Table Header -->
                        <thead class="bg-[#80cbc4]/70 text-slate-800 font-extrabold text-[11px] uppercase tracking-wider">
                            <tr>
                                <th class="py-2.5 px-4 font-extrabold">NAMA ASET</th>
                                <th class="py-2.5 px-4 font-extrabold">KATEGORI</th>
                                <th class="py-2.5 px-4 font-extrabold">KONDISI</th>
                                <th class="py-2.5 px-4 font-extrabold">SKOR AI</th>
                                <th class="py-2.5 px-4 font-extrabold text-right">AKSI PEMDES</th>
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
                                    <td class="py-3 px-4 text-right space-x-1">
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

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Alamat / Patokan Lokasi</label>
                            <input type="text" wire:model="assetAddress" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 bg-white text-slate-800 text-xs" placeholder="Contoh: Jl. Sukomulyo Barat No. 12" />
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
                <h3 class="text-[11px] font-extrabold uppercase tracking-wider text-slate-800">ASSET CATEGORIES</h3>
                
                <div class="space-y-2 text-xs">
                    <!-- Category 1 -->
                    <div>
                        <div class="flex justify-between text-[11px] text-slate-600 mb-0.5">
                            <span class="font-medium">Art & Kebudayaan</span>
                        </div>
                        <div class="w-full bg-slate-100 h-3 rounded">
                            <div class="bg-[#80cbc4] h-full rounded" style="width: 25%"></div>
                        </div>
                        <span class="text-[10px] text-slate-500">3</span>
                    </div>

                    <!-- Category 2 -->
                    <div>
                        <div class="flex justify-between text-[11px] text-slate-600 mb-0.5">
                            <span class="font-medium">Fitness & Olahraga Desa</span>
                        </div>
                        <div class="w-full bg-slate-100 h-3 rounded">
                            <div class="bg-[#80cbc4] h-full rounded" style="width: 48%"></div>
                        </div>
                        <span class="text-[10px] text-slate-500">6</span>
                    </div>

                    <!-- Category 3 -->
                    <div>
                        <div class="flex justify-between text-[11px] text-slate-600 mb-0.5">
                            <span class="font-medium">Bangunan & Balai Pertemuan</span>
                        </div>
                        <div class="w-full bg-slate-100 h-3 rounded">
                            <div class="bg-[#80cbc4] h-full rounded" style="width: 40%"></div>
                        </div>
                        <span class="text-[10px] text-slate-500">5</span>
                    </div>

                    <!-- Category 4 -->
                    <div>
                        <div class="flex justify-between text-[11px] text-slate-600 mb-0.5">
                            <span class="font-medium">Monuments & Wisata Religi</span>
                        </div>
                        <div class="w-full bg-slate-100 h-3 rounded">
                            <div class="bg-[#80cbc4] h-full rounded" style="width: 40%"></div>
                        </div>
                        <span class="text-[10px] text-slate-500">5</span>
                    </div>

                    <!-- Category 5 -->
                    <div>
                        <div class="flex justify-between text-[11px] text-slate-600 mb-0.5">
                            <span class="font-medium">Play Equipment & Taman Terbuka</span>
                        </div>
                        <div class="w-full bg-slate-100 h-3 rounded">
                            <div class="bg-[#80cbc4] h-full rounded" style="width: 85%"></div>
                        </div>
                        <span class="text-[10px] text-slate-500">5</span>
                    </div>

                    <!-- Category 6 -->
                    <div>
                        <div class="flex justify-between text-[11px] text-slate-600 mb-0.5">
                            <span class="font-medium">Security & Pos Pantau</span>
                        </div>
                        <div class="w-full bg-slate-100 h-3 rounded">
                            <div class="bg-[#80cbc4] h-full rounded" style="width: 38%"></div>
                        </div>
                        <span class="text-[10px] text-slate-500">5</span>
                    </div>

                    <!-- Category 7 -->
                    <div>
                        <div class="flex justify-between text-[11px] text-slate-600 mb-0.5">
                            <span class="font-medium">Signage & Kios Usaha</span>
                        </div>
                        <div class="w-full bg-slate-100 h-3 rounded">
                            <div class="bg-[#80cbc4] h-full rounded" style="width: 65%"></div>
                        </div>
                        <span class="text-[10px] text-slate-500">5</span>
                    </div>

                    <!-- Category 8 -->
                    <div>
                        <div class="flex justify-between text-[11px] text-slate-600 mb-0.5">
                            <span class="font-medium">Sports Facilities</span>
                        </div>
                        <div class="w-full bg-slate-100 h-3 rounded">
                            <div class="bg-[#80cbc4] h-full rounded" style="width: 38%"></div>
                        </div>
                        <span class="text-[10px] text-slate-500">5</span>
                    </div>
                </div>
            </div>

            <!-- 2. TOTAL INSURANCE VALUE BREAKDOWN (DONUT CHART) -->
            <div class="bg-white rounded-2xl p-4 border border-slate-200/90 shadow-xs space-y-3">
                <h3 class="text-[11px] font-extrabold uppercase tracking-wider text-slate-800">TOTAL INSURANCE VALUE BREAKDOWN</h3>
                
                <div 
                    class="flex items-center justify-between"
                    x-data="{
                        init() {
                            const options = {
                                series: [65, 35],
                                chart: { type: 'donut', height: 160, sparkline: { enabled: true } },
                                colors: ['#00c9a7', '#008080'],
                                labels: ['LAND & PROPERTY', 'INFRASTRUCTURE & AMENITIES'],
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
                            <span>LAND & PROPERTY</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-[#008080] shrink-0"></span>
                            <span>INFRASTRUCTURE & AMENITIES</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. ASSET CONDITION (DONUT CHART) -->
            <div class="bg-white rounded-2xl p-4 border border-slate-200/90 shadow-xs space-y-3">
                <h3 class="text-[11px] font-extrabold uppercase tracking-wider text-slate-800">ASSET CONDITION</h3>
                
                <div 
                    class="flex items-center justify-between"
                    x-data="{
                        init() {
                            const options = {
                                series: [45, 15, 20, 20],
                                chart: { type: 'donut', height: 160, sparkline: { enabled: true } },
                                colors: ['#008080', '#ff5722', '#00c9a7', '#60a5fa'],
                                labels: ['GOOD', 'POOR', 'NEW', 'FAIR'],
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
                            <span>GOOD</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-[#ff5722] shrink-0"></span>
                            <span>POOR</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-[#60a5fa] shrink-0"></span>
                            <span>NEW</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-[#00c9a7] shrink-0"></span>
                            <span>FAIR</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>
