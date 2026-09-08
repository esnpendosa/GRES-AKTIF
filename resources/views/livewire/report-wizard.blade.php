<div class="max-w-3xl mx-auto px-4 sm:px-6 py-8">

    @if($isSubmitted)
        <!-- SUCCESS SCREEN -->
        <div class="bg-white rounded-3xl p-8 sm:p-12 text-center border border-slate-200 shadow-xl space-y-6 animate-fade-in">
            <div class="w-20 h-20 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto shadow-md shadow-emerald-500/20">
                <i data-lucide="check-circle-2" class="w-10 h-10"></i>
            </div>

            <div class="space-y-2 max-w-md mx-auto">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse mr-1.5"></span>
                    STATUS: MENUNGGU VERIFIKASI
                </span>
                <h2 class="font-heading font-extrabold text-2xl sm:text-3xl text-slate-900 tracking-tight">
                    Terima Kasih!
                </h2>
                <p class="text-sm text-slate-600 leading-relaxed">
                    Kontribusimu sangat berarti untuk membantu desa menemukan fungsi ekonomi baru dari aset yang sebelumnya terlantar.
                </p>
            </div>

            <!-- Gamification Points Reward Box -->
            <div class="p-4 rounded-2xl bg-teal-50 border border-teal-200 inline-flex items-center gap-3 text-left max-w-md mx-auto">
                <div class="w-10 h-10 rounded-xl bg-teal-600 text-white flex items-center justify-center font-bold text-base shadow-xs shrink-0">
                    +20
                </div>
                <div>
                    <p class="text-xs font-bold text-teal-900">Poin Kontribusi Ditambahkan!</p>
                    <p class="text-[11px] text-teal-700">Laporan aset telah tercatat di profil reputasimu.</p>
                </div>
            </div>

            <div class="pt-4 flex flex-col sm:flex-row items-center justify-center gap-3">
                <a href="{{ route('explore') }}" class="w-full sm:w-auto px-6 py-3 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-sm font-semibold transition-colors shadow-sm">
                    Jelajahi Aset Lainnya
                </a>
                <a href="{{ route('home') }}" class="w-full sm:w-auto px-6 py-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold transition-colors">
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
                        <span class="text-[11px] font-bold text-teal-400 uppercase tracking-wider">Crowdsourcing Warga</span>
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
                    @elseif($currentStep === 2) Langkah 2: Tentukan Titik Lokasi & Alamat
                    @elseif($currentStep === 3) Langkah 3: Kondisi Aset Saat Ini
                    @elseif($currentStep === 4) Langkah 4: Kategori Fasilitas Aset
                    @elseif($currentStep === 5) Langkah 5: Usulan Pemanfaatan Ekonomi
                    @elseif($currentStep === 6) Langkah 6: Deskripsi & Kirim Laporan
                    @endif
                </div>
            </div>

            <!-- Form Content Body -->
            <div class="p-6 sm:p-8 space-y-6">

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
                                    <i data-lucide="camera" class="w-6 h-6"></i>
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

                <!-- STEP 2: LOCATION -->
                @if($currentStep === 2)
                    <div class="space-y-4" x-data="{
                        initMap() {
                            const map = L.map('reportMap', { attributionControl: false }).setView([{{ $latitude }}, {{ $longitude }}], 16);
                            
                            // Google Earth Satellite Hybrid + Standard OSM
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

                            const marker = L.marker([{{ $latitude }}, {{ $longitude }}], { draggable: true }).addTo(map);

                            marker.on('dragend', function(e) {
                                const pos = marker.getLatLng();
                                @this.setLocation(pos.lat, pos.lng);
                            });

                            map.on('click', function(e) {
                                marker.setLatLng(e.latlng);
                                @this.setLocation(e.latlng.lat, e.latlng.lng);
                            });
                        }
                    }" x-init="setTimeout(() => initMap(), 100)">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-heading font-bold text-lg text-slate-900">2. Titik Koordinat & Wilayah</h3>
                                <p class="text-xs text-slate-500">Pastikan titik marker sesuai dengan lokasi fisik aset.</p>
                            </div>
                            
                            <button 
                                type="button"
                                @click="
                                    if (navigator.geolocation) {
                                        navigator.geolocation.getCurrentPosition((pos) => {
                                            @this.setLocation(pos.coords.latitude, pos.coords.longitude);
                                        });
                                    }
                                "
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-teal-50 text-teal-700 text-xs font-semibold hover:bg-teal-100 transition-colors"
                            >
                                <i data-lucide="crosshair" class="w-3.5 h-3.5"></i>
                                <span>Deteksi GPS Saya</span>
                            </button>
                        </div>

                        <!-- Leaflet Interactive Mini Map -->
                        <div id="reportMap" wire:ignore class="h-56 w-full rounded-2xl border border-slate-200 z-0"></div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Kecamatan</label>
                                <select wire:model.live="district_id" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs bg-white text-slate-800">
                                    @foreach($districts as $d)
                                        <option value="{{ $d->id }}">{{ $d->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Desa / Kelurahan</label>
                                <select wire:model="village_id" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs bg-white text-slate-800">
                                    @foreach($villages as $v)
                                        <option value="{{ $v->id }}">{{ $v->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Alamat / Patokan Lokasi</label>
                            <input type="text" wire:model="address" placeholder="Contoh: Depan Kantor Balai Desa, Sebelah Timur Lapangan..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs text-slate-800" />
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
                                <label class="p-4 rounded-2xl border-2 cursor-pointer transition-all flex flex-col justify-between {{ $condition === $c['id'] ? 'border-teal-500 bg-teal-50/50 shadow-xs ring-1 ring-teal-500' : 'border-slate-200 hover:border-slate-300' }}">
                                    <div class="flex items-center justify-between">
                                        <span class="font-bold text-sm text-slate-900">{{ $c['label'] }}</span>
                                        <input type="radio" wire:model="condition" value="{{ $c['id'] }}" class="text-teal-600 focus:ring-teal-500" />
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

                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                            @foreach($categories as $cat)
                                <label class="p-3.5 rounded-2xl border-2 cursor-pointer transition-all flex flex-col items-center text-center gap-2 {{ $category_id == $cat->id ? 'border-teal-500 bg-teal-50/50 shadow-xs ring-1 ring-teal-500' : 'border-slate-200 hover:border-slate-300' }}">
                                    <div class="w-10 h-10 rounded-xl bg-slate-100 text-teal-600 flex items-center justify-center">
                                        <i data-lucide="{{ $cat->icon ?? 'building-2' }}" class="w-5 h-5"></i>
                                    </div>
                                    <span class="font-bold text-xs text-slate-800">{{ $cat->name }}</span>
                                    <input type="radio" wire:model="category_id" value="{{ $cat->id }}" class="hidden" />
                                </label>
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
                                <label class="p-4 rounded-2xl border-2 cursor-pointer transition-all flex flex-col justify-between {{ $suggested_use === $opt['id'] ? 'border-teal-500 bg-teal-50/50 shadow-xs ring-1 ring-teal-500' : 'border-slate-200 hover:border-slate-300' }}">
                                    <div class="flex items-center justify-between">
                                        <span class="font-bold text-sm text-slate-900">{{ $opt['label'] }}</span>
                                        <input type="radio" wire:model="suggested_use" value="{{ $opt['id'] }}" class="text-teal-600 focus:ring-teal-500" />
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

                        <!-- Summary Review Box -->
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 text-xs space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="text-slate-500">Usulan Pemanfaatan:</span>
                                <span class="font-bold text-teal-700">{{ $suggested_use }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-slate-500">Kondisi:</span>
                                <span class="font-bold text-slate-800">{{ ucfirst(str_replace('_', ' ', $condition)) }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-slate-500">Imbalan:</span>
                                <span class="font-bold text-emerald-700">+20 Poin Kontribusi Warga</span>
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
                    <button type="button" wire:click="nextStep" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold transition-all shadow-sm active:scale-95">
                        <span>Lanjut</span>
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </button>
                @else
                    <button type="button" wire:click="submitReport" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-gradient-to-r from-teal-600 to-teal-700 hover:from-teal-700 hover:to-teal-800 text-white text-xs font-bold transition-all shadow-md shadow-teal-600/30 active:scale-95">
                        <i data-lucide="send" class="w-4 h-4"></i>
                        <span>Kirim Laporan Aset</span>
                    </button>
                @endif
            </div>

        </div>
    @endif

</div>
