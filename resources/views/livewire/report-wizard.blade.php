<div class="max-w-3xl mx-auto px-4 sm:px-6 py-8">

    @if($isSubmitted)
        <!-- SUCCESS SCREEN WITH AUTO-REDIRECT TO GIS MAP -->
        <div 
            class="bg-white rounded-3xl p-8 sm:p-12 text-center border border-slate-200 shadow-xl space-y-6 animate-fade-in"
            x-data="{
                countdown: 3,
                redirectUrl: '{{ route('map') }}?newReport={{ $createdReport?->id }}&lat={{ $createdReport?->latitude }}&lng={{ $createdReport?->longitude }}',
                init() {
                    const timer = setInterval(() => {
                        this.countdown--;
                        if (this.countdown <= 0) {
                            clearInterval(timer);
                            window.location.href = this.redirectUrl;
                        }
                    }, 1000);
                }
            }"
        >
            <div class="relative w-24 h-24 mx-auto">
                <div class="w-24 h-24 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center shadow-lg shadow-emerald-500/20 animate-bounce">
                    <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="absolute -top-1 -right-1 w-7 h-7 rounded-full bg-teal-700 text-white font-extrabold text-xs flex items-center justify-center border-2 border-white shadow-xs" x-text="countdown"></div>
            </div>

            <div class="space-y-2 max-w-md mx-auto">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse mr-1.5"></span>
                    STATUS: TERCATAT DI RADAR SPASIAL
                </span>
                <h2 class="font-heading font-extrabold text-2xl sm:text-3xl text-slate-900 tracking-tight">
                    Laporan Berhasil Terkirim!
                </h2>
                <p class="text-sm text-slate-600 leading-relaxed">
                    Aset <strong class="text-slate-900 font-bold">"{{ $createdReport?->title }}"</strong> telah berhasil dicatat dari koordinat GPS Anda di <strong class="text-teal-700 font-bold">{{ $createdReport?->village?->name ? 'Desa ' . $createdReport->village->name : 'Kabupaten Gresik' }}</strong>.
                </p>
                <div class="pt-1 flex items-center justify-center gap-2 text-[11px] text-slate-500">
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-slate-100 border border-slate-200 font-medium">
                        <svg class="w-3.5 h-3.5 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                        <span>{{ round($createdReport?->latitude ?? $latitude, 5) }}, {{ round($createdReport?->longitude ?? $longitude, 5) }}</span>
                    </span>
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-teal-50 border border-teal-200 text-teal-800 font-bold">
                        <span>Desa {{ $createdReport?->village?->name ?? 'Sukomulyo' }}</span>
                    </span>
                </div>
            </div>

            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 text-xs text-slate-500 max-w-md mx-auto flex items-center justify-center gap-2">
                <div class="w-4 h-4 border-2 border-teal-600 border-t-transparent rounded-full animate-spin"></div>
                <span>Mengalihkan otomatis ke titik aset di Peta Spasial GIS dalam <strong class="text-teal-700 font-extrabold" x-text="countdown">3</strong> detik...</span>
            </div>

            <div class="pt-2 flex flex-col sm:flex-row items-center justify-center gap-3">
                <a :href="redirectUrl" class="w-full sm:w-auto px-6 py-3 rounded-xl bg-teal-700 hover:bg-teal-800 text-white text-xs font-bold transition-all shadow-md active:scale-95 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                    </svg>
                    <span>Lihat di Peta Spasial Sekarang &rarr;</span>
                </a>
                <a href="{{ route('home') }}" class="w-full sm:w-auto px-6 py-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition-colors">
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    @else
        <!-- STEP WIZARD CARD -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-md overflow-hidden">
            
            <!-- Wizard Header & Steps Progress -->
            <div class="bg-slate-900 p-6 text-white space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-[11px] font-bold text-teal-400 uppercase tracking-wider">KENTONGAN AI &bull; Crowdsourcing Warga</span>
                        <h1 class="font-heading font-extrabold text-xl text-white">Laporkan Aset Tidak Produktif</h1>
                    </div>
                    <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-slate-800 text-slate-300 border border-slate-700">
                        Langkah {{ $currentStep }} dari 6
                    </span>
                </div>

                <!-- Progress Bar -->
                <div class="w-full bg-slate-800 h-2 rounded-full overflow-hidden">
                    <div class="bg-teal-500 h-full transition-all duration-300" style="width: {{ ($currentStep / 6) * 100 }}%"></div>
                </div>

                <!-- Step Title Summary -->
                <div class="text-xs text-slate-300 font-medium">
                    @if($currentStep === 1) Langkah 1: Ambil / Unggah Foto Aset
                    @elseif($currentStep === 2) Langkah 2: Pindai Lokasi GPS & Deteksi Wilayah Desa
                    @elseif($currentStep === 3) Langkah 3: Kondisi Aset Saat Ini
                    @elseif($currentStep === 4) Langkah 4: Kategori Fasilitas Aset
                    @elseif($currentStep === 5) Langkah 5: Usulan Pemanfaatan Ekonomi
                    @elseif($currentStep === 6) Langkah 6: Deskripsi & Kirim Laporan
                    @endif
                </div>
            </div>

            <!-- Form Content Body -->
            <div class="p-6 sm:p-8 space-y-6">

                <!-- AI Quick Report Generator Box -->
                <div class="p-4 sm:p-5 rounded-2xl bg-slate-900 border border-teal-500/40 text-white shadow-md space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-lg bg-teal-500/20 text-teal-300 flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <span class="font-heading font-bold text-xs sm:text-sm text-white">Isi Laporan Cepat via Asisten AI</span>
                        </div>
                        <span class="text-[10px] font-bold px-2.5 py-0.5 rounded bg-teal-500/20 text-teal-300 border border-teal-500/30">Otomatisasi AI</span>
                    </div>
                    <p class="text-xs text-slate-300">Tuliskan keterangan aset yang Anda temukan (lokasi, jenis aset, kondisi, usulan), AI akan mengekstrak dan mengisi formulir.</p>
                    
                    <div class="flex flex-col sm:flex-row gap-2">
                        <input 
                            type="text" 
                            wire:model="aiPrompt" 
                            wire:keydown.enter="fillWithAi"
                            placeholder="Contoh: Ada gudang KUD terbengkalai di Manyarejo rusak ringan, cocok buat sentra kerajinan"
                            class="flex-1 px-3.5 py-2.5 rounded-xl bg-slate-800 border border-slate-700 text-white placeholder-slate-400 text-xs focus:ring-2 focus:ring-teal-500 focus:border-teal-500 focus:outline-none"
                        />
                        <button 
                            type="button" 
                            wire:click="fillWithAi" 
                            wire:loading.attr="disabled"
                            class="px-4 py-2.5 rounded-xl bg-teal-700 hover:bg-teal-600 text-white text-xs font-bold transition-all shadow-xs flex items-center justify-center gap-1.5 shrink-0 active:scale-95 disabled:opacity-50 cursor-pointer"
                        >
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            <span wire:loading.remove wire:target="fillWithAi">Ekstrak via AI</span>
                            <span wire:loading wire:target="fillWithAi">Memproses...</span>
                        </button>
                    </div>
                    @error('aiPrompt') <span class="text-rose-400 text-[11px] block">{{ $message }}</span> @enderror
                    @if($aiMessage)
                        <div class="p-2.5 rounded-lg bg-teal-900/50 border border-teal-500/40 text-teal-200 text-xs flex items-center gap-2">
                            <svg class="w-4 h-4 text-teal-300 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>{{ $aiMessage }}</span>
                        </div>
                    @endif
                </div>

                <!-- STEP 1: PHOTOS -->
                @if($currentStep === 1)
                    <div class="space-y-4">
                        <div class="space-y-1">
                            <h3 class="font-heading font-bold text-lg text-slate-900">1. Unggah Foto Kondisi Aset</h3>
                            <p class="text-xs text-slate-500">Ambil foto langsung melalui kamera HP atau pilih dari galeri.</p>
                        </div>

                        <div class="border-2 border-dashed border-slate-200 rounded-2xl p-6 text-center hover:border-teal-400 transition-colors bg-slate-50/50">
                            <input type="file" wire:model="photos" multiple accept="image/*" class="hidden" id="photo-upload" />
                            <label for="photo-upload" class="cursor-pointer flex flex-col items-center space-y-2">
                                <div class="w-12 h-12 rounded-full bg-teal-100 text-teal-600 flex items-center justify-center">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </div>
                                <span class="text-sm font-semibold text-teal-700">Buka Kamera / Pilih Foto</span>
                                <span class="text-[11px] text-slate-400">Dapat memilih lebih dari 1 foto (JPG, PNG)</span>
                            </label>

                            <div wire:loading wire:target="photos" class="text-xs text-teal-600 font-medium mt-2">
                                Sedang memproses foto...
                            </div>
                        </div>

                        <!-- Photo Previews -->
                        @if($photos)
                            <div class="grid grid-cols-3 gap-3 pt-2">
                                @foreach($photos as $photo)
                                    <div class="relative h-24 rounded-xl overflow-hidden border border-slate-200 bg-slate-100">
                                        <img src="{{ $photo->temporaryUrl() }}" class="w-full h-full object-cover" />
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif

                <!-- STEP 2: LOCATION WITH RADAR SCANNING ANIMATION -->
                @if($currentStep === 2)
                    <div class="space-y-5" x-data="{
                        scanning: false,
                        scanStep: 0,
                        detectedVillage: '{{ $villages->firstWhere('id', $village_id)?->name ?? 'Sukomulyo' }}',
                        detectedDistrict: '{{ $districts->firstWhere('id', $district_id)?->name ?? 'Manyar' }}',
                        mapInstance: null,
                        markerInstance: null,

                        initMap() {
                            if (this.mapInstance) return;
                            this.mapInstance = L.map('reportMap', { attributionControl: false }).setView([{{ $latitude }}, {{ $longitude }}], 16);
                            
                            const googleEarth = L.tileLayer('https://mt1.google.com/vt/lyrs=y&x={x}&y={y}&z={z}', {
                                maxZoom: 20,
                                subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
                            }).addTo(this.mapInstance);

                            const streetMap = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                maxZoom: 19
                            });

                            L.control.layers({
                                'Citra Satelit': googleEarth,
                                'Peta Standar': streetMap
                            }, null, { position: 'topright' }).addTo(this.mapInstance);

                            this.markerInstance = L.marker([{{ $latitude }}, {{ $longitude }}], { draggable: true }).addTo(this.mapInstance);

                            this.markerInstance.on('dragend', (e) => {
                                const pos = this.markerInstance.getLatLng();
                                @this.setLocation(pos.lat, pos.lng);
                            });

                            this.mapInstance.on('click', (e) => {
                                this.markerInstance.setLatLng(e.latlng);
                                @this.setLocation(e.latlng.lat, e.latlng.lng);
                            });
                        },

                        scanGPS() {
                            this.scanning = true;
                            this.scanStep = 1;

                            setTimeout(() => { this.scanStep = 2; }, 700);
                            setTimeout(() => {
                                if (navigator.geolocation) {
                                    navigator.geolocation.getCurrentPosition(
                                        (pos) => {
                                            const lat = pos.coords.latitude;
                                            const lng = pos.coords.longitude;
                                            this.scanStep = 3;
                                            @this.setLocation(lat, lng);
                                            if (this.mapInstance) {
                                                this.mapInstance.setView([lat, lng], 17);
                                                this.markerInstance.setLatLng([lat, lng]);
                                            }
                                            setTimeout(() => { this.scanStep = 4; }, 600);
                                            setTimeout(() => { this.scanning = false; }, 1400);
                                        },
                                        (err) => {
                                            // Fallback lokasi Sukomulyo, Gresik
                                            this.scanStep = 3;
                                            @this.setLocation({{ $latitude }}, {{ $longitude }});
                                            setTimeout(() => { this.scanStep = 4; }, 600);
                                            setTimeout(() => { this.scanning = false; }, 1400);
                                        },
                                        { timeout: 8000, enableHighAccuracy: true }
                                    );
                                } else {
                                    this.scanStep = 3;
                                    @this.setLocation({{ $latitude }}, {{ $longitude }});
                                    setTimeout(() => { this.scanStep = 4; }, 600);
                                    setTimeout(() => { this.scanning = false; }, 1400);
                                }
                            }, 1200);
                        }
                    }" x-init="setTimeout(() => initMap(), 150)">
                        
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-2 border-b border-slate-100">
                            <div>
                                <h3 class="font-heading font-bold text-lg text-slate-900">2. Titik Koordinat & Deteksi Lokasi Warga</h3>
                                <p class="text-xs text-slate-500">Sistem otomatis mengambil data koordinat GPS perangkat Anda dan mencocokkannya ke batas desa terkait.</p>
                            </div>
                            
                            <button 
                                type="button"
                                @click="scanGPS()"
                                :disabled="scanning"
                                class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-gradient-to-r from-teal-700 to-slate-900 hover:from-teal-800 hover:to-slate-800 text-white text-xs font-bold shadow-md transition-all active:scale-95 cursor-pointer shrink-0"
                            >
                                <svg class="w-4 h-4 text-amber-300" :class="scanning ? 'animate-spin' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 2v2m0 16v2m10-10h-2M4 12H2m15.071-7.071l-1.414 1.414M8.343 15.657l-1.414 1.414m12.728 0l-1.414-1.414M8.343 8.343L6.929 6.929M12 8a4 4 0 100 8 4 4 0 000-8z" />
                                </svg>
                                <span x-text="scanning ? 'Memindai Radar GPS...' : 'Ambil Lokasi Saya Sekarang'">Ambil Lokasi Saya Sekarang</span>
                            </button>
                        </div>

                        <!-- LIVE SCANNING RADAR ANIMATION OVERLAY -->
                        <div x-show="scanning" x-cloak class="p-5 rounded-2xl bg-slate-900 text-white space-y-3 animate-fade-in border border-teal-500/50 shadow-2xl">
                            <div class="flex items-center gap-3.5">
                                <div class="relative w-12 h-12 flex items-center justify-center shrink-0">
                                    <div class="w-12 h-12 rounded-full bg-teal-400/30 animate-ping absolute"></div>
                                    <div class="w-9 h-9 rounded-full bg-teal-600 flex items-center justify-center text-white relative z-10 shadow-lg border border-teal-300">
                                        <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m0 14v1m8-8h-1M5 12H4m13.657-5.657l-.707.707M7.05 16.95l-.707.707M17.657 17.657l-.707-.707M7.05 7.05l-.707-.707" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1 space-y-1">
                                    <div class="flex items-center justify-between">
                                        <h4 class="font-bold text-xs text-teal-300 uppercase tracking-wider flex items-center gap-1.5">
                                            <span>Radar Spasial & GPS Aktif</span>
                                            <span class="w-2 h-2 rounded-full bg-teal-400 animate-pulse"></span>
                                        </h4>
                                        <span class="text-[10px] text-slate-400 font-mono" x-text="'Langkah ' + scanStep + ' dari 4'"></span>
                                    </div>
                                    <p class="text-[11px] text-slate-300" x-show="scanStep === 1">1. Menghubungi satelit GPS & membaca sensor koordinat perangkat...</p>
                                    <p class="text-[11px] text-teal-200 font-medium" x-show="scanStep === 2">2. Menghitung jarak terhadap simpul batas desa di Gresik...</p>
                                    <p class="text-[11px] text-amber-300 font-semibold" x-show="scanStep === 3">3. Mengunci titik koordinat: {{ round($latitude, 5) }}, {{ round($longitude, 5) }}...</p>
                                    <p class="text-[11px] text-emerald-300 font-bold flex items-center gap-1.5" x-show="scanStep === 4">
                                        <svg class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                        <span>4. Lokasi terdeteksi di Desa {{ $villages->firstWhere('id', $village_id)?->name ?? 'Sukomulyo' }}, Kec. {{ $districts->firstWhere('id', $district_id)?->name ?? 'Manyar' }}!</span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Leaflet Interactive Mini Map -->
                        <div class="relative rounded-2xl overflow-hidden border border-slate-200 shadow-xs">
                            <div id="reportMap" wire:ignore class="h-64 w-full z-0 bg-slate-100"></div>
                            <div class="absolute bottom-3 left-3 z-10 bg-slate-900/90 backdrop-blur-xs text-white px-3 py-1.5 rounded-xl text-[10px] font-mono flex items-center gap-2 border border-slate-700 shadow-md">
                                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                <span>Lat: {{ round($latitude, 5) }}, Lng: {{ round($longitude, 5) }}</span>
                            </div>
                            <div class="absolute top-3 right-3 z-10 bg-white/90 backdrop-blur-xs text-slate-700 px-2.5 py-1 rounded-lg text-[10px] font-semibold border border-slate-200 shadow-xs">
                                Geser pin atau klik peta untuk atur titik
                            </div>
                        </div>

                        <!-- Auto-Resolved Location Inputs -->
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-3 text-xs">
                            <div class="flex items-center justify-between text-slate-700 font-bold">
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-teal-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    </svg>
                                    <span>Hasil Pencocokan Wilayah Administrasi</span>
                                </div>
                                <span class="text-[10px] px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 font-bold flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    <span>Otomatis Terdeteksi</span>
                                </span>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-slate-600 font-semibold mb-1">Kecamatan</label>
                                    <select wire:model.live="district_id" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs bg-white text-slate-800 focus:ring-2 focus:ring-teal-500">
                                        @foreach($districts as $d)
                                            <option value="{{ $d->id }}">{{ $d->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-slate-600 font-semibold mb-1">Desa / Kelurahan Terdeteksi</label>
                                    <select wire:model="village_id" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs bg-white text-slate-800 focus:ring-2 focus:ring-teal-500 font-bold text-teal-900">
                                        @foreach($villages as $v)
                                            <option value="{{ $v->id }}">Desa {{ $v->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="block text-slate-600 font-semibold mb-1">Alamat Lengkap / Patokan Lapangan</label>
                                <input type="text" wire:model="address" placeholder="Contoh: Depan Balai Desa Sukomulyo, Jl. Raya Manyar..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs text-slate-800 bg-white" />
                            </div>
                        </div>

                    </div>
                @endif

                <!-- STEP 3: ASSET CONDITION -->
                @if($currentStep === 3)
                    <div class="space-y-4">
                        <div class="space-y-1">
                            <h3 class="font-heading font-bold text-lg text-slate-900">3. Bagaimana Kondisi Aset Saat Ini?</h3>
                            <p class="text-xs text-slate-500">Pilih opsi yang paling menggambarkan keadaan fisik dan penggunaannya.</p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @php
                                $conditions = [
                                    ['id' => 'tidak_digunakan', 'label' => 'Tidak Digunakan', 'desc' => 'Kosong total dan terkunci, belum ada aktivitas sama sekali.'],
                                    ['id' => 'jarang_digunakan', 'label' => 'Jarang Digunakan', 'desc' => 'Hanya terpakai sesekali dalam hitungan bulan.'],
                                    ['id' => 'kurang_produktif', 'label' => 'Kurang Produktif', 'desc' => 'Digunakan tapi nilai ekonominya sangat rendah.'],
                                    ['id' => 'rusak', 'label' => 'Rusak Sebagian', 'desc' => 'Perlu perbaikan atap, dinding, atau fasilitas.'],
                                    ['id' => 'terbengkalai', 'label' => 'Terbengkalai', 'desc' => 'Mangkrak bertahun-tahun dan ditumbuhi semak liar.'],
                                    ['id' => 'tidak_tahu', 'label' => 'Tidak Tahu Pasti', 'desc' => 'Perlu verifikasi lebih lanjut dari pihak desa.'],
                                ];
                            @endphp

                            @foreach($conditions as $c)
                                <label wire:click="$set('condition', '{{ $c['id'] }}')" class="p-4 rounded-2xl border-2 cursor-pointer transition-all flex flex-col justify-between select-none {{ $condition === $c['id'] ? 'border-teal-600 bg-teal-50/70 shadow-sm ring-1 ring-teal-500' : 'border-slate-200 hover:border-teal-300 hover:bg-slate-50/50' }}">
                                    <div class="flex items-center justify-between">
                                        <span class="font-bold text-sm text-slate-900">{{ $c['label'] }}</span>
                                        <input type="radio" wire:model="condition" value="{{ $c['id'] }}" class="text-teal-600 focus:ring-teal-500 cursor-pointer" />
                                    </div>
                                    <p class="text-xs text-slate-500 mt-2 leading-relaxed">{{ $c['desc'] }}</p>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- STEP 4: CATEGORY -->
                @if($currentStep === 4)
                    <div class="space-y-4">
                        <div class="space-y-1">
                            <h3 class="font-heading font-bold text-lg text-slate-900">4. Apa Kategori Fisik Aset Ini?</h3>
                            <p class="text-xs text-slate-500">Pilih jenis fasilitas untuk mempermudah klasifikasi AI.</p>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                            @foreach($categories as $cat)
                                @php
                                    $isSelected = ((int)$category_id === (int)$cat->id);
                                @endphp
                                <button 
                                    type="button" 
                                    wire:click="$set('category_id', {{ $cat->id }})" 
                                    class="p-4 rounded-2xl border-2 text-center transition-all flex flex-col items-center justify-between gap-3 cursor-pointer relative group {{ $isSelected ? 'border-teal-600 bg-teal-50/80 shadow-md ring-2 ring-teal-500/20 scale-[1.02]' : 'border-slate-200 hover:border-teal-300 hover:bg-slate-50/60 bg-white' }}"
                                >
                                    <!-- Selected Checkmark Badge -->
                                    @if($isSelected)
                                        <div class="absolute top-2.5 right-2.5 w-5 h-5 rounded-full bg-teal-600 text-white flex items-center justify-center shadow-xs">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </div>
                                    @endif

                                    <!-- Category Icon Container -->
                                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center transition-all {{ $isSelected ? 'bg-teal-600 text-white shadow-md shadow-teal-600/30' : 'bg-slate-100 text-teal-700 group-hover:bg-teal-100' }}">
                                        @if($cat->icon === 'building-2' || str_contains(strtolower($cat->name), 'bangunan') || str_contains(strtolower($cat->name), 'gedung'))
                                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                        @elseif($cat->icon === 'map-pin' || str_contains(strtolower($cat->name), 'tanah') || str_contains(strtolower($cat->name), 'pekarangan'))
                                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        @elseif($cat->icon === 'store' || str_contains(strtolower($cat->name), 'pasar') || str_contains(strtolower($cat->name), 'kios'))
                                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                        @elseif($cat->icon === 'trophy' || str_contains(strtolower($cat->name), 'olahraga') || str_contains(strtolower($cat->name), 'terbuka'))
                                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                                        @elseif($cat->icon === 'sprout' || str_contains(strtolower($cat->name), 'pertanian') || str_contains(strtolower($cat->name), 'tambak'))
                                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21V10m0 0a5.002 5.002 0 019.288-2.614M12 10a5.002 5.002 0 00-9.288-2.614"/></svg>
                                        @elseif($cat->icon === 'landmark' || str_contains(strtolower($cat->name), 'wisata') || str_contains(strtolower($cat->name), 'budaya'))
                                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/></svg>
                                        @elseif($cat->icon === 'network' || str_contains(strtolower($cat->name), 'infrastruktur') || str_contains(strtolower($cat->name), 'sarana'))
                                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/></svg>
                                        @elseif($cat->icon === 'graduation-cap' || str_contains(strtolower($cat->name), 'pendidikan') || str_contains(strtolower($cat->name), 'pelatihan'))
                                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5"/></svg>
                                        @else
                                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                        @endif
                                    </div>

                                    <!-- Category Title -->
                                    <span class="font-bold text-xs sm:text-[13px] leading-tight transition-colors {{ $isSelected ? 'text-teal-950 font-extrabold' : 'text-slate-800' }}">
                                        {{ $cat->name }}
                                    </span>
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- STEP 5: SUGGESTED USE -->
                @if($currentStep === 5)
                    <div class="space-y-4">
                        <div class="space-y-1">
                            <h3 class="font-heading font-bold text-lg text-slate-900">5. Menurut Anda, Aset Ini Bisa Dimanfaatkan Untuk Apa?</h3>
                            <p class="text-xs text-slate-500">Pendapat Anda akan dikelompokkan oleh AI menjadi konsensus kebutuhan masyarakat.</p>
                        </div>

                        @php
                            $useOptions = [
                                ['id' => 'UMKM', 'label' => 'Sentra UMKM / Display Produk', 'desc' => 'Kios jualan produk lokal, stan oleh-oleh, kerajinan.'],
                                ['id' => 'Kuliner', 'label' => 'Pusat Kuliner / Food Court', 'desc' => 'Pujasera malam hari, kafe pemuda, warung makan terpadu.'],
                                ['id' => 'Pertanian', 'label' => 'Pertanian / Urban Farming', 'desc' => 'Greenhouse hidroponik, kebun bibit, budidaya perikanan.'],
                                ['id' => 'Pariwisata', 'label' => 'Pariwisata & Spot Foto', 'desc' => 'Taman rekreasi desa, ekowisata, camping ground.'],
                                ['id' => 'Pendidikan', 'label' => 'Balai Pelatihan & Vokasi', 'desc' => 'Ruang kursus digital, pelatihan menjahit, bengkel kerja.'],
                                ['id' => 'Olahraga', 'label' => 'Fasilitas Olahraga / Mini Soccer', 'desc' => 'Lapangan futsal, badminton, sport center desa.'],
                                ['id' => 'Coworking', 'label' => 'Coworking & Creative Hub', 'desc' => 'Ruang kerja pemuda dengan internet cepat.'],
                                ['id' => 'Lainnya', 'label' => 'Fasilitas Publik Lainnya', 'desc' => 'Gudang logistik bersama, bank sampah, pos pelayanan.'],
                            ];
                        @endphp

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach($useOptions as $opt)
                                <label wire:click="$set('suggested_use', '{{ $opt['id'] }}')" class="p-4 rounded-2xl border-2 cursor-pointer transition-all flex flex-col justify-between select-none {{ $suggested_use === $opt['id'] ? 'border-teal-600 bg-teal-50/70 shadow-sm ring-1 ring-teal-500' : 'border-slate-200 hover:border-teal-300 hover:bg-slate-50/50' }}">
                                    <div class="flex items-center justify-between">
                                        <span class="font-bold text-sm text-slate-900">{{ $opt['label'] }}</span>
                                        <input type="radio" wire:model="suggested_use" value="{{ $opt['id'] }}" class="text-teal-600 focus:ring-teal-500 cursor-pointer" />
                                    </div>
                                    <p class="text-xs text-slate-500 mt-1.5 leading-relaxed">{{ $opt['desc'] }}</p>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- STEP 6: DESCRIPTION & SUBMIT -->
                @if($currentStep === 6)
                    <div class="space-y-4">
                        <div class="space-y-1">
                            <h3 class="font-heading font-bold text-lg text-slate-900">6. Beri Nama & Detail Laporan</h3>
                            <p class="text-xs text-slate-500">Lengkapi judul aset dan catatan tambahan sebelum mengirim.</p>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Nama / Sebutan Aset <span class="text-rose-500">*</span></label>
                            <input type="text" wire:model="title" placeholder="Contoh: Gedung Bekas Koperasi RT 03 Sukomulyo" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-teal-500" />
                            @error('title') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Catatan Tambahan (Opsional)</label>
                            <textarea wire:model="description" rows="3" placeholder="Ceritakan riwayat aset, kendala saat ini, atau harapan warga sekitar..." class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-teal-500"></textarea>
                        </div>

                        <!-- Pilihan Kondisi Fisik Aset (Dapat Dipilih / Diubah) -->
                        <div class="pt-1">
                            <label class="block text-xs font-semibold text-slate-700 mb-2">Pilih Kondisi Fisik Aset <span class="text-rose-500">*</span></label>
                            @php
                                $conditions = [
                                    ['id' => 'terbengkalai', 'label' => 'Terbengkalai', 'desc' => 'Lahan/bangunan tidak terawat'],
                                    ['id' => 'rusak', 'label' => 'Rusak', 'desc' => 'Perlu perbaikan fisik'],
                                    ['id' => 'tidak_digunakan', 'label' => 'Tidak Digunakan', 'desc' => 'Kosong & menganggur'],
                                    ['id' => 'kurang_produktif', 'label' => 'Kurang Produktif', 'desc' => 'Hasil belum optimal'],
                                    ['id' => 'jarang_digunakan', 'label' => 'Jarang Digunakan', 'desc' => 'Hanya saat tertentu'],
                                ];
                            @endphp
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                                @foreach($conditions as $cond)
                                    <label class="p-3 rounded-xl border text-left cursor-pointer transition-all flex flex-col justify-between {{ $condition === $cond['id'] ? 'border-teal-600 bg-teal-50 text-teal-900 ring-1 ring-teal-600 shadow-xs' : 'border-slate-200 bg-white hover:border-slate-300 text-slate-700' }}">
                                        <div class="flex items-center justify-between">
                                            <span class="font-bold text-xs">{{ $cond['label'] }}</span>
                                            <input type="radio" wire:model.live="condition" value="{{ $cond['id'] }}" class="text-teal-600 focus:ring-teal-500" />
                                        </div>
                                        <span class="text-[10px] text-slate-500 mt-1">{{ $cond['desc'] }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Pilihan Kategori & Usulan Pemanfaatan (Dapat Dipilih / Diubah) -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">Kategori Fasilitas</label>
                                <select wire:model.live="category_id" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs bg-white text-slate-800 focus:ring-2 focus:ring-teal-500 focus:outline-none cursor-pointer">
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">Usulan Pemanfaatan</label>
                                <select wire:model.live="suggested_use" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs bg-white text-slate-800 focus:ring-2 focus:ring-teal-500 focus:outline-none cursor-pointer">
                                    <option value="UMKM">Sentra UMKM / Display Produk</option>
                                    <option value="Kuliner">Pusat Kuliner / Food Court</option>
                                    <option value="Pertanian">Pertanian / Urban Farming</option>
                                    <option value="Pariwisata">Pariwisata & Rekreasi</option>
                                    <option value="Pendidikan">Balai Pelatihan & Vokasi</option>
                                    <option value="Olahraga">Fasilitas Olahraga</option>
                                    <option value="Coworking">Coworking & Digital Hub</option>
                                    <option value="Lainnya">Fasilitas Publik Lainnya</option>
                                </select>
                            </div>
                        </div>

                        <!-- Pilihan Desa & Input Alamat -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">Wilayah Desa <span class="text-rose-500">*</span></label>
                                <select wire:model.live="village_id" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs bg-white text-slate-800 focus:ring-2 focus:ring-teal-500 focus:outline-none cursor-pointer">
                                    @foreach($villages as $v)
                                        <option value="{{ $v->id }}">Desa {{ $v->name }} ({{ $v->district ? $v->district->name : 'Gresik' }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">Alamat / Patokan Lokasi <span class="text-rose-500">*</span></label>
                                <input type="text" wire:model="address" placeholder="Contoh: Jl. Raya Sukomulyo RT 02 RW 01, Dekat Balai Desa" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs bg-white text-slate-800 focus:ring-2 focus:ring-teal-500 focus:outline-none" />
                                @error('address') <span class="text-rose-500 text-[11px] mt-0.5 block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                @endif

            </div>

            <!-- Wizard Footer Action Buttons -->
            <div class="bg-slate-50 px-6 sm:px-8 py-4 border-t border-slate-200 flex items-center justify-between">
                @if($currentStep > 1)
                    <button type="button" wire:click="prevStep" class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-700 text-xs font-semibold hover:bg-slate-100 transition-colors">
                        &larr; Kembali
                    </button>
                @else
                    <div></div>
                @endif

                @if($currentStep < 6)
                    <button type="button" wire:click="nextStep" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold transition-all shadow-sm active:scale-95 cursor-pointer">
                        <span>Lanjut</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                @else
                    <button type="button" wire:click="submitReport" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-gradient-to-r from-teal-600 to-teal-700 hover:from-teal-700 hover:to-teal-800 text-white text-xs font-bold transition-all shadow-md shadow-teal-600/30 active:scale-95 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        <span>Kirim Laporan Aset</span>
                    </button>
                @endif
            </div>

        </div>
    @endif

</div>
