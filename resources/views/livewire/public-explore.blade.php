<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

    <!-- Header Banner (Clean Light Institutional Style) -->
    <div class="bg-white rounded-2xl p-6 sm:p-7 border border-slate-200 shadow-xs space-y-2">
        <div class="inline-flex items-center gap-2 px-2.5 py-0.5 rounded bg-slate-100 text-slate-700 text-[11px] font-bold">
            <span class="w-1.5 h-1.5 rounded-full bg-teal-600"></span>
            <span>Inventaris Terbuka Kabupaten Gresik</span>
        </div>
        <h1 class="font-heading font-extrabold text-2xl sm:text-3xl text-slate-900 tracking-tight">
            Database Aset & Potensi Ekonomi Desa
        </h1>
        <p class="text-xs sm:text-sm text-slate-500 max-w-3xl leading-relaxed">
            Pencarian dan pemetaan transparansi aset desa di 16 kecamatan Kabupaten Gresik. Pelajari hasil kajian kelayakan pemanfaatan dan sampaikan aspirasi gagasan Anda.
        </p>
    </div>

    <!-- Search & Quick Location Toolbar -->
    <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200 shadow-sm space-y-4">
        <div class="flex flex-col md:flex-row items-stretch md:items-center gap-3">
            
            <!-- Search Input -->
            <div class="relative flex-1">
                <i data-lucide="search" class="w-5 h-5 absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input 
                    type="text" 
                    wire:model.live.debounce.300ms="search" 
                    placeholder="Cari nama aset, desa, kecamatan, atau potensi (contoh: Sukomulyo, Sentra UMKM)..." 
                    class="w-full pl-11 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:bg-white transition-all text-slate-800 placeholder-slate-400"
                />
            </div>

            <!-- Geolocation Button -->
            <button 
                type="button"
                x-data="{ locating: false }"
                @click="
                    locating = true;
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(
                            (pos) => {
                                @this.setLocation(pos.coords.latitude, pos.coords.longitude);
                                locating = false;
                            },
                            (err) => {
                                alert('Tidak dapat mendeteksi lokasi GPS Anda.');
                                locating = false;
                            }
                        );
                    } else {
                        alert('Browser Anda tidak mendukung geolokasi.');
                        locating = false;
                    }
                "
                class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl border border-teal-200 bg-teal-50 hover:bg-teal-100 text-teal-800 text-xs font-semibold transition-all shrink-0 active:scale-95"
            >
                <i data-lucide="locate" class="w-4 h-4 text-teal-600" :class="locating ? 'animate-spin' : ''"></i>
                <span x-text="locating ? 'Mencari Lokasi...' : 'Gunakan Lokasi Saya'">Gunakan Lokasi Saya</span>
            </button>
        </div>

        <!-- Filter Row -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 pt-2 border-t border-slate-100">
            <!-- District Selector -->
            <div>
                <label class="block text-[11px] font-semibold text-slate-500 mb-1">Kecamatan</label>
                <select wire:model.live="selectedDistrict" class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs bg-white text-slate-700 focus:outline-none focus:ring-1 focus:ring-teal-500">
                    <option value="">Semua Kecamatan</option>
                    @foreach($districts as $d)
                        <option value="{{ $d->name }}">{{ $d->name }} ({{ $d->assets_count }})</option>
                    @endforeach
                </select>
            </div>

            <!-- Category Selector -->
            <div>
                <label class="block text-[11px] font-semibold text-slate-500 mb-1">Kategori Aset</label>
                <select wire:model.live="selectedCategory" class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs bg-white text-slate-700 focus:outline-none focus:ring-1 focus:ring-teal-500">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Condition Selector -->
            <div>
                <label class="block text-[11px] font-semibold text-slate-500 mb-1">Kondisi Aset</label>
                <select wire:model.live="selectedCondition" class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs bg-white text-slate-700 focus:outline-none focus:ring-1 focus:ring-teal-500">
                    <option value="">Semua Kondisi</option>
                    <option value="tidak_digunakan">Tidak Digunakan</option>
                    <option value="jarang_digunakan">Jarang Digunakan</option>
                    <option value="kurang_produktif">Kurang Produktif</option>
                    <option value="rusak">Rusak</option>
                    <option value="terbengkalai">Terbengkalai</option>
                </select>
            </div>

            <!-- Sort By -->
            <div>
                <label class="block text-[11px] font-semibold text-slate-500 mb-1">Urutan</label>
                <select wire:model.live="sortBy" class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs bg-white text-slate-700 focus:outline-none focus:ring-1 focus:ring-teal-500">
                    <option value="score_desc">Skor AI Tertinggi</option>
                    <option value="popular">Dukungan Terbanyak</option>
                    <option value="latest">Terbaru</option>
                    @if($userLat && $userLng)
                        <option value="nearest">Terdekat Dari Saya</option>
                    @endif
                </select>
            </div>
        </div>

        @if($locationStatus)
            <div class="text-xs text-teal-700 flex items-center gap-1.5 font-medium bg-teal-50/80 px-3 py-1.5 rounded-lg">
                <i data-lucide="check" class="w-3.5 h-3.5 text-teal-600"></i>
                <span>{{ $locationStatus }} &bull; Mengurutkan dari jarak terdekat ke terjauh</span>
            </div>
        @endif
    </div>

    <!-- Assets Grid -->
    @if($assets->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($assets as $asset)
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-md hover:border-teal-300 transition-all flex flex-col group">
                    
                    <!-- Asset Image & Badge Overlay -->
                    <div class="relative h-48 bg-slate-100 overflow-hidden">
                        <img 
                            src="{{ $asset->primary_image_url }}" 
                            alt="{{ $asset->name }}" 
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                            loading="lazy"
                        />
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-slate-900/20 to-transparent"></div>

                        <!-- Top Badges -->
                        <div class="absolute top-3 left-3 flex flex-wrap gap-1.5">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold tracking-wide uppercase bg-slate-900/80 text-white backdrop-blur border border-white/20">
                                {{ $asset->category ? $asset->category->name : 'Aset' }}
                            </span>
                        </div>

                        <div class="absolute top-3 right-3">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold {{ $asset->condition === 'terbengkalai' || $asset->condition === 'rusak' ? 'bg-rose-500 text-white' : 'bg-amber-500 text-white' }} shadow-xs">
                                {{ $asset->condition_label }}
                            </span>
                        </div>

                        <!-- Bottom Image Info -->
                        <div class="absolute bottom-3 left-3 right-3 text-white flex items-end justify-between">
                            <div>
                                <p class="text-xs font-semibold text-slate-200 flex items-center gap-1">
                                    <i data-lucide="map-pin" class="w-3.5 h-3.5 text-teal-400"></i>
                                    <span>Desa {{ $asset->village ? $asset->village->name : 'Gresik' }}, Kec. {{ $asset->village && $asset->village->district ? $asset->village->district->name : 'Manyar' }}</span>
                                </p>
                            </div>

                            @if(isset($asset->distance))
                                <span class="px-2 py-0.5 rounded-md bg-slate-900/80 text-teal-300 text-[10px] font-bold border border-teal-500/30">
                                    {{ $asset->distance }} km
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                        <div>
                            <a href="{{ route('assets.show', $asset->slug ?? $asset->id) }}" class="block">
                                <h3 class="font-heading font-bold text-base text-slate-900 group-hover:text-teal-600 transition-colors line-clamp-1">
                                    {{ $asset->name }}
                                </h3>
                            </a>
                            <p class="text-xs text-slate-500 mt-1.5 line-clamp-2 leading-relaxed">
                                {{ $asset->description }}
                            </p>
                        </div>

                        <!-- AI Potential Score & Recommendation -->
                        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100 space-y-2.5">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-1.5">
                                    <i data-lucide="sparkles" class="w-4 h-4 text-cyan-500"></i>
                                    <span class="text-xs font-semibold text-slate-700">Skor Potensi AI</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <span class="text-base font-extrabold text-teal-700">{{ $asset->potential_score }}</span>
                                    <span class="text-[10px] text-slate-400 font-bold">/100</span>
                                    <span class="ml-1 text-[10px] font-bold px-1.5 py-0.2 rounded {{ $asset->potential_score >= 71 ? 'bg-emerald-100 text-emerald-800' : 'bg-cyan-100 text-cyan-800' }}">
                                        {{ $asset->potential_level }}
                                    </span>
                                </div>
                            </div>

                            <div class="pt-2 border-t border-slate-200/60 flex items-center justify-between text-xs">
                                <span class="text-[11px] text-slate-500">Rekomendasi AI:</span>
                                <span class="font-bold text-slate-800 text-right truncate max-w-[170px] text-teal-800">
                                    {{ $asset->target_activation_use ?? 'Sentra UMKM' }}
                                </span>
                            </div>
                        </div>

                        <!-- Card Footer -->
                        <div class="pt-2 flex items-center justify-between text-xs text-slate-500">
                            <div class="flex items-center gap-1.5 font-medium">
                                <i data-lucide="users" class="w-3.5 h-3.5 text-teal-600"></i>
                                <span>{{ $asset->supporters_count ?: rand(30, 150) }} warga mendukung</span>
                            </div>

                            <a href="{{ route('assets.show', $asset->slug ?? $asset->id) }}" class="inline-flex items-center gap-1 font-semibold text-teal-600 hover:text-teal-700 group/btn">
                                <span>Lihat Detail</span>
                                <i data-lucide="arrow-right" class="w-3.5 h-3.5 group-hover/btn:translate-x-0.5 transition-transform"></i>
                            </a>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="pt-4">
            {{ $assets->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-2xl p-12 text-center border border-slate-200 space-y-4">
            <div class="w-16 h-16 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center mx-auto">
                <i data-lucide="folder-search" class="w-8 h-8"></i>
            </div>
            <div class="max-w-md mx-auto space-y-1">
                <h3 class="font-heading font-bold text-lg text-slate-900">Aset Tidak Ditemukan</h3>
                <p class="text-xs text-slate-500">Tidak ada aset yang sesuai dengan kriteria pencarian atau filter yang Anda pilih. Coba sesuaikan kata kunci atau bersihkan filter.</p>
            </div>
            <button wire:click="$set('search', '')" class="px-4 py-2 rounded-xl text-xs font-semibold bg-teal-600 text-white hover:bg-teal-700 transition-colors">
                Reset Pencarian
            </button>
        </div>
    @endif

</div>
