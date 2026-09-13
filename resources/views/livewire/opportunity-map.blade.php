<div 
    class="h-[calc(100vh-4rem)] flex flex-col md:flex-row overflow-hidden relative" 
    x-data="{
        map: null,
        markersLayer: null,
        activeAsset: null,
        filterDrawerOpen: false,
        assets: @js($assetsPayload),
        
        init() {
            this.initMap();
            
            // Listen to Livewire filter updates
            $wire.on('assets-updated', (data) => {
                this.assets = data.assets || [];
                this.renderMarkers();
            });
        },

        initMap() {
            if (this.map) return;
            
            // Initialize Leaflet with zoom control at bottomleft to avoid topbar overlap
            this.map = L.map('opportunityMapContainer', {
                zoomControl: false,
                attributionControl: false
            }).setView([-7.1566, 112.6555], 12);

            // Add Zoom Control at bottomleft
            L.control.zoom({ position: 'bottomleft' }).addTo(this.map);

            // Google Earth Satellite Hybrid + Standard OSM layer
            const googleEarth = L.tileLayer('https://mt1.google.com/vt/lyrs=y&x={x}&y={y}&z={z}', {
                maxZoom: 20,
                subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
            }).addTo(this.map);

            const streetMap = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19
            });

            L.control.layers({
                '🛰️ Google Earth': googleEarth,
                '🗺️ Peta Jalan': streetMap
            }, null, { position: 'topright' }).addTo(this.map);

            this.markersLayer = L.markerClusterGroup({
                maxClusterRadius: 40,
                spiderfyOnMaxZoom: true,
                showCoverageOnHover: false,
                zoomToBoundsOnClick: true
            });

            this.map.addLayer(this.markersLayer);
            this.renderMarkers();

            // Check if redirected from a fresh report
            @if($focusLat && $focusLng)
                this.map.setView([{{ $focusLat }}, {{ $focusLng }}], 16);
                const pulseIcon = L.divIcon({
                    className: 'new-report-pulse',
                    html: `<div style='background-color: #00c9a7; width: 32px; height: 32px; border-radius: 50%; border: 3px solid white; box-shadow: 0 0 15px #00c9a7; display: flex; align-items: center; justify-content: center; color: white; font-size: 13px; font-weight: 800; animation: bounce 1s infinite;'>📍</div>`,
                    iconSize: [32, 32],
                    iconAnchor: [16, 16]
                });
                L.marker([{{ $focusLat }}, {{ $focusLng }}], { icon: pulseIcon }).addTo(this.map);
            @endif

            // Deselect asset when clicking on empty map area
            this.map.on('click', () => {
                this.activeAsset = null;
            });
            
            // If first asset exists, select it as default inspector
            if (this.assets && this.assets.length > 0) {
                this.activeAsset = this.assets[0];
            }
        },

        renderMarkers() {
            if (!this.markersLayer) return;
            this.markersLayer.clearLayers();

            const items = this.assets || [];
            items.forEach(asset => {
                const customIcon = L.divIcon({
                    className: 'custom-div-icon',
                    html: `<div style='background-color: ${asset.color || '#008080'}; width: 28px; height: 28px; border-radius: 50%; border: 2.5px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.4); display: flex; align-items: center; justify-content: center; color: white; font-size: 11px; font-weight: 800; cursor: pointer;'>${asset.score}</div>`,
                    iconSize: [28, 28],
                    iconAnchor: [14, 14]
                });

                const marker = L.marker([asset.lat, asset.lng], { icon: customIcon });

                marker.on('click', (e) => {
                    if (e && e.originalEvent) {
                        e.originalEvent.stopPropagation();
                    }
                    this.activeAsset = asset;
                    if (window.innerWidth < 768) {
                        this.map.panTo([asset.lat, asset.lng]);
                    }
                    setTimeout(() => {
                        if (window.lucide) window.lucide.createIcons();
                    }, 40);
                });

                this.markersLayer.addLayer(marker);
            });
        }
    }"
    wire:ignore.self
>

    <!-- Top Sector Filter Bar (Floating Sticky) -->
    <div class="absolute top-3 left-4 right-16 z-20 pointer-events-none">
        <div class="max-w-4xl mx-auto flex items-center gap-1.5 overflow-x-auto p-1.5 rounded-2xl bg-white/95 backdrop-blur-md border border-slate-200/90 shadow-md pointer-events-auto">
            <span class="text-xs font-bold text-slate-800 px-2.5 shrink-0 flex items-center gap-1.5">
                <i data-lucide="layers" class="w-4 h-4 text-teal-700"></i>
                <span class="hidden sm:inline">Peluang Sektor:</span>
            </span>

            <button 
                type="button"
                wire:click="setSector('')" 
                class="px-3 py-1.5 rounded-xl text-xs font-bold shrink-0 transition-all {{ empty($selectedSector) ? 'bg-slate-900 text-white shadow-xs' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}"
            >
                Semua Sektor
            </button>
            <button 
                type="button"
                wire:click="setSector('UMKM')" 
                class="px-3 py-1.5 rounded-xl text-xs font-bold shrink-0 transition-all {{ $selectedSector === 'UMKM' ? 'bg-teal-700 text-white shadow-xs' : 'bg-teal-50 hover:bg-teal-100 text-teal-800' }}"
            >
                Sentra UMKM
            </button>
            <button 
                type="button"
                wire:click="setSector('Kuliner')" 
                class="px-3 py-1.5 rounded-xl text-xs font-bold shrink-0 transition-all {{ $selectedSector === 'Kuliner' ? 'bg-amber-600 text-white shadow-xs' : 'bg-amber-50 hover:bg-amber-100 text-amber-900' }}"
            >
                Pusat Kuliner
            </button>
            <button 
                type="button"
                wire:click="setSector('Pertanian')" 
                class="px-3 py-1.5 rounded-xl text-xs font-bold shrink-0 transition-all {{ $selectedSector === 'Pertanian' ? 'bg-emerald-700 text-white shadow-xs' : 'bg-emerald-50 hover:bg-emerald-100 text-emerald-900' }}"
            >
                Pertanian / Tambak
            </button>
            <button 
                type="button"
                wire:click="setSector('Wisata')" 
                class="px-3 py-1.5 rounded-xl text-xs font-bold shrink-0 transition-all {{ $selectedSector === 'Wisata' ? 'bg-purple-700 text-white shadow-xs' : 'bg-purple-50 hover:bg-purple-100 text-purple-900' }}"
            >
                Wisata & Budaya
            </button>
            <button 
                type="button"
                wire:click="setSector('Pendidikan')" 
                class="px-3 py-1.5 rounded-xl text-xs font-bold shrink-0 transition-all {{ $selectedSector === 'Pendidikan' ? 'bg-sky-700 text-white shadow-xs' : 'bg-sky-50 hover:bg-sky-100 text-sky-900' }}"
            >
                Pelatihan Vokasi
            </button>

            <!-- Sumbang Ide Button in Toolbar -->
            <button 
                type="button" 
                wire:click="openIdeaModal()"
                class="px-3.5 py-1.5 rounded-xl bg-teal-700 hover:bg-teal-800 text-white text-xs font-bold shrink-0 flex items-center gap-1.5 shadow-xs transition-colors"
            >
                <i data-lucide="lightbulb" class="w-3.5 h-3.5 text-amber-300"></i>
                <span>+ Sumbang Ide</span>
            </button>

            <!-- Filter Drawer Toggle -->
            <button 
                type="button" 
                @click="filterDrawerOpen = !filterDrawerOpen" 
                class="ml-auto px-3 py-1.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold shrink-0 flex items-center gap-1.5 transition-colors"
            >
                <i data-lucide="sliders-horizontal" class="w-3.5 h-3.5"></i>
                <span class="hidden md:inline">Filter Wilayah</span>
            </button>
        </div>
    </div>

    <!-- Map Canvas (Left / Center) -->
    <div class="flex-1 h-full relative z-0">
        <div id="opportunityMapContainer" wire:ignore class="w-full h-full bg-slate-200"></div>

        <!-- Floating FAB Button: Sumbang Ide (Bottom Right) -->
        <div class="absolute bottom-6 right-6 z-20">
            <button 
                type="button" 
                wire:click="openIdeaModal()"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-full bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs shadow-xl border border-slate-700 hover:scale-105 active:scale-95 transition-all"
            >
                <i data-lucide="lightbulb" class="w-4 h-4 text-amber-400"></i>
                <span>Sumbang Ide Warga (+10 Pts)</span>
            </button>
        </div>

        <!-- Legend Overlay (Bottom Left above zoom) -->
        <div class="absolute bottom-16 left-4 z-20 hidden sm:block bg-white/95 backdrop-blur-md rounded-2xl p-3 border border-slate-200/90 shadow-md text-xs space-y-1.5">
            <p class="font-bold text-[10px] text-slate-400 uppercase tracking-wider">Status Indikator Aset</p>
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                <span class="text-slate-700 text-[11px]">Aktif & Produktif (80-100)</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-sky-600"></span>
                <span class="text-slate-700 text-[11px]">Rencana Re-Aktivasi</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-amber-500"></span>
                <span class="text-slate-700 text-[11px]">Kurang / Jarang Digunakan</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-rose-500"></span>
                <span class="text-slate-700 text-[11px]">Perlu Re-Aktivasi Segera</span>
            </div>
        </div>
    </div>

    <!-- Filter Drawer Modal/Dropdown -->
    <div 
        x-show="filterDrawerOpen" 
        x-cloak 
        @click.outside="filterDrawerOpen = false" 
        class="absolute top-16 right-4 z-30 w-80 bg-white rounded-2xl p-5 border border-slate-200 shadow-2xl space-y-4 animate-scale-in"
    >
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h4 class="font-heading font-bold text-sm text-slate-900">Filter Parameter Spasial</h4>
            <button @click="filterDrawerOpen = false" class="text-slate-400 hover:text-slate-600">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <div class="space-y-3 text-xs">
            <div>
                <label class="block font-semibold text-slate-700 mb-1">Kecamatan</label>
                <select wire:model.live="selectedDistrict" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white text-slate-800">
                    <option value="">Semua Kecamatan (16)</option>
                    @foreach($districts as $d)
                        <option value="{{ $d->name }}">{{ $d->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block font-semibold text-slate-700 mb-1">Kategori Fasilitas</label>
                <select wire:model.live="selectedCategory" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white text-slate-800">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block font-semibold text-slate-700 mb-1">Kondisi Aset</label>
                <select wire:model.live="selectedCondition" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white text-slate-800">
                    <option value="">Semua Kondisi</option>
                    <option value="tidak_digunakan">Tidak Digunakan</option>
                    <option value="kurang_produktif">Kurang Produktif</option>
                    <option value="terbengkalai">Terbengkalai</option>
                </select>
            </div>

            <div>
                <label class="block font-semibold text-slate-700 mb-1">Skor Kelayakan</label>
                <select wire:model.live="selectedScore" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white text-slate-800">
                    <option value="">Semua Skor</option>
                    <option value="high">Sangat Layak (71 - 100)</option>
                    <option value="medium">Cukup Layak (41 - 70)</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Active Asset Inspector (Desktop Right Sidebar / Mobile Bottom Sheet) -->
    <div 
        x-show="activeAsset" 
        x-cloak 
        :class="activeAsset ? 'flex' : '!hidden'"
        class="w-full md:w-96 bg-white border-t md:border-t-0 md:border-l border-slate-200 p-5 overflow-y-auto z-20 shadow-xl flex-col justify-between shrink-0"
    >
        <div class="space-y-4" x-show="activeAsset">
            <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                <span class="text-[10px] font-bold px-2.5 py-0.5 rounded-md bg-slate-100 text-slate-700 uppercase tracking-wide" x-text="activeAsset?.category"></span>
                <button 
                    type="button" 
                    @click.stop="activeAsset = null; $wire.selectAsset(null)" 
                    class="p-1.5 rounded-lg text-slate-400 hover:text-slate-800 hover:bg-slate-100 transition-all cursor-pointer"
                    title="Tutup Panel"
                >
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Preview Image -->
            <div class="h-44 rounded-xl overflow-hidden bg-slate-100 border border-slate-200 relative">
                <img :src="activeAsset?.image" :alt="activeAsset?.name" class="w-full h-full object-cover" />
                <span class="absolute top-2 right-2 text-[10px] font-bold px-2.5 py-0.5 rounded-md bg-slate-900/85 text-white backdrop-blur" x-text="activeAsset?.condition"></span>
            </div>

            <div class="space-y-1">
                <h3 class="font-heading font-bold text-base text-slate-900 leading-snug" x-text="activeAsset?.name"></h3>
                <p class="text-xs text-slate-500 flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-teal-700 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span>Desa <span x-text="activeAsset?.village"></span>, Kec. <span x-text="activeAsset?.district"></span></span>
                </p>
            </div>

            <!-- Feasibility & Potential Score Box -->
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 space-y-2.5">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-xs font-semibold text-slate-700 block">Indeks Kelayakan & Potensi</span>
                        <span class="text-[10px] text-teal-700 font-bold">Grade A &bull; Sangat Layak</span>
                    </div>
                    <div class="flex items-baseline gap-0.5">
                        <span class="font-extrabold text-2xl text-slate-900 font-heading" x-text="activeAsset?.score"></span>
                        <span class="text-xs font-bold text-slate-400">/ 100</span>
                    </div>
                </div>

                <div class="pt-2 border-t border-slate-200 text-xs space-y-1">
                    <span class="text-[11px] text-slate-500 font-medium block">Rekomendasi Utama:</span>
                    <span class="font-bold text-slate-900 text-teal-900" x-text="activeAsset?.target_use"></span>
                </div>
            </div>

            <!-- CTA Buttons -->
            <div class="space-y-2 pt-1">
                <button 
                    type="button"
                    wire:click="openIdeaModal(activeAsset ? activeAsset.id : null)" 
                    class="w-full py-2.5 px-4 rounded-xl bg-teal-700 hover:bg-teal-800 text-white font-bold text-xs flex items-center justify-center gap-2 shadow-xs transition-colors cursor-pointer"
                >
                    <svg class="w-4 h-4 text-amber-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                    </svg>
                    <span>+ Usulkan Ide Pemanfaatan</span>
                </button>

                <a 
                    :href="activeAsset?.url" 
                    class="w-full py-2.5 px-4 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 text-slate-800 font-semibold text-xs flex items-center justify-center gap-2 transition-colors cursor-pointer"
                >
                    <span>Lihat Detail & Voting Ide Warga</span>
                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>
            </div>
        </div>
    </div>

    <!-- GLOBAL IDEA SUBMISSION MODAL ON MAP -->
    @if($showGlobalIdeaModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
            <div class="bg-white rounded-2xl p-6 sm:p-7 max-w-lg w-full border border-slate-200 shadow-2xl space-y-4 animate-scale-in">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-xl bg-teal-50 text-teal-700 flex items-center justify-center border border-teal-100">
                            <i data-lucide="lightbulb" class="w-4 h-4 text-amber-500"></i>
                        </div>
                        <div>
                            <h3 class="font-heading font-bold text-base text-slate-900">Sumbang Ide Gagasan Warga</h3>
                            <p class="text-[11px] text-slate-500">Aspirasi pemanfaatan aset desa bernilai ekonomi</p>
                        </div>
                    </div>
                    <button wire:click="$set('showGlobalIdeaModal', false)" class="text-slate-400 hover:text-slate-600">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                @if($ideaSubmitted)
                    <div class="p-5 rounded-xl bg-teal-50 border border-teal-200 text-center space-y-2">
                        <div class="w-10 h-10 rounded-full bg-teal-700 text-white flex items-center justify-center mx-auto">
                            <i data-lucide="check" class="w-5 h-5"></i>
                        </div>
                        <h4 class="font-bold text-sm text-teal-900">Gagasan Berhasil Dikirim!</h4>
                        <p class="text-xs text-teal-800">Terima kasih atas kontribusi Anda. Anda memperoleh <strong>+10 Poin Warga</strong> dan usulan ini langsung masuk ke radar konsensus publik.</p>
                        <button wire:click="$set('showGlobalIdeaModal', false)" class="mt-2 px-5 py-2 rounded-xl bg-teal-700 hover:bg-teal-800 text-white text-xs font-bold transition-colors">
                            Tutup
                        </button>
                    </div>
                @else
                    <form wire:submit.prevent="submitIdea" class="space-y-3.5 text-xs">
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Pilih Objek Aset Desa</label>
                            <select wire:model="ideaAssetId" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white text-slate-800">
                                @foreach(\App\Models\Asset::all() as $ast)
                                    <option value="{{ $ast->id }}">{{ $ast->name }} (Desa {{ $ast->village ? $ast->village->name : 'Gresik' }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Kategori Rencana Pemanfaatan</label>
                            <select wire:model="ideaCategory" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white text-slate-800">
                                <option value="UMKM">Sentra UMKM / Display Produk Lokal</option>
                                <option value="Kuliner">Pusat Kuliner / Pujasera Warga</option>
                                <option value="Pertanian">Pertanian / Urban Farming / Budidaya Tambak</option>
                                <option value="Wisata">Pariwisata / Ekowisata & Rekreasi</option>
                                <option value="Pendidikan">Balai Pelatihan Kerja & Vokasi</option>
                                <option value="Olahraga">Fasilitas Olahraga / Sport Center</option>
                                <option value="Coworking">Coworking Space & Digital Hub</option>
                                <option value="Lainnya">Fasilitas Publik Lainnya</option>
                            </select>
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Judul Gagasan Ide <span class="text-rose-500">*</span></label>
                            <input type="text" wire:model="ideaTitle" placeholder="Contoh: Sentra Olahan Bandeng & Pudak Khas Gresik" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs" />
                            @error('ideaTitle') <span class="text-rose-500 text-[11px] mt-0.5 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Deskripsi Singkat Rencana Pemanfaatan</label>
                            <textarea wire:model="ideaDescription" rows="3" placeholder="Jelaskan bagaimana ide ini bisa meningkatkan pendapatan warga atau BUMDes..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs"></textarea>
                        </div>

                        <div class="p-3 rounded-xl bg-teal-50 border border-teal-200 text-teal-900 flex items-center justify-between">
                            <span class="font-medium">Reward Poin Partisipasi:</span>
                            <span class="font-bold">+10 Poin Warga</span>
                        </div>

                        <div class="flex items-center justify-end gap-2.5 pt-2">
                            <button type="button" wire:click="$set('showGlobalIdeaModal', false)" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100">
                                Batal
                            </button>
                            <button type="submit" class="px-5 py-2.5 rounded-xl text-xs font-bold bg-teal-700 hover:bg-teal-800 text-white shadow-xs">
                                Kirim Gagasan Ide
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    @endif

</div>
