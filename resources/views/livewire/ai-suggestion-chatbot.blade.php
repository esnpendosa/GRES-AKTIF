<div>
    <!-- Floating Trigger Button (Bottom Right) - Glow & Radar Ping -->
    <div class="fixed bottom-6 right-6 z-40 flex items-center gap-2 print:hidden">
        <!-- Quick Action Pill: Laporkan Aset -->
        <button 
            type="button" 
            wire:click="startReportMode" 
            class="hidden sm:inline-flex items-center gap-2 px-3.5 py-2.5 rounded-full bg-rose-600 hover:bg-rose-500 text-white font-bold text-xs shadow-xl border border-rose-400/40 hover:scale-105 active:scale-95 transition-all cursor-pointer animate-bounce [animation-duration:3s]"
            title="Laporkan Aset dengan Animasi & GPS"
        >
            <span class="w-2 h-2 rounded-full bg-white animate-ping"></span>
            <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            <span>Lapor Aset (GPS)</span>
        </button>

        <!-- Main Chatbot Trigger Button -->
        <button 
            type="button" 
            wire:click="$toggle('isModalOpen')" 
            class="group inline-flex items-center gap-2.5 px-4 py-3 rounded-full bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs shadow-2xl border border-teal-500/40 hover:border-teal-400 hover:scale-105 active:scale-95 transition-all cursor-pointer"
            title="Buka Asisten AI KENTONGAN"
        >
            <div class="w-7 h-7 rounded-full bg-teal-500/20 text-teal-300 flex items-center justify-center relative">
                <svg class="w-4 h-4 text-teal-300 group-hover:rotate-12 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
                <span class="absolute -top-0.5 -right-0.5 w-2 h-2 rounded-full bg-teal-400 animate-ping"></span>
            </div>
            <span class="tracking-wide">Tanya AI</span>
        </button>
    </div>

    <!-- ChatGPT Style Modal / Window -->
    @if($isModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-2 sm:p-4 bg-slate-950/75 backdrop-blur-sm animate-fade-in print:hidden"
             x-data="{
                countdown: 3,
                timer: null,
                init() {
                    $nextTick(() => { if (typeof lucide !== 'undefined') lucide.createIcons(); });
                    $wire.on('start-gps-scan', () => {
                        this.scanGps();
                    });
                    $wire.on('report-completed-fly-map', (event) => {
                        const data = event[0] || event;
                        this.startCountdown(data.url);
                    });
                },
                scanGps() {
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(
                            (pos) => {
                                $wire.setLocation(pos.coords.latitude, pos.coords.longitude);
                            },
                            (err) => {
                                // Fallback Manyar Sidomukti coordinates
                                $wire.setLocation(-7.1350, 112.6020);
                            },
                            { enableHighAccuracy: true, timeout: 7000 }
                        );
                    } else {
                        $wire.setLocation(-7.1350, 112.6020);
                    }
                },
                startCountdown(url) {
                    this.countdown = 3;
                    if (this.timer) clearInterval(this.timer);
                    this.timer = setInterval(() => {
                        this.countdown--;
                        if (this.countdown <= 0) {
                            clearInterval(this.timer);
                            window.location.href = url;
                        }
                    }, 1000);
                }
             }">
            <div class="bg-white rounded-3xl w-full border border-slate-200 shadow-2xl flex flex-col overflow-hidden transition-all duration-300 {{ $isMaximized ? 'h-[96vh] max-w-[96vw]' : 'h-[88vh] max-h-[740px] max-w-3xl' }}"
                 x-init="$nextTick(() => { if (typeof lucide !== 'undefined') lucide.createIcons(); })"
                 x-on:livewire:update.window="$nextTick(() => { if (typeof lucide !== 'undefined') lucide.createIcons(); })"
                 x-on:livewire:updated.window="$nextTick(() => { if (typeof lucide !== 'undefined') lucide.createIcons(); })">
                
                <!-- Top Navigation Bar -->
                <div class="bg-slate-900 px-4 py-3 text-white flex items-center justify-between gap-3 shrink-0 border-b border-slate-800">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-xl bg-teal-600/30 border border-teal-500/50 text-teal-300 flex items-center justify-center shadow-xs shrink-0">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="font-heading font-extrabold text-sm text-white">CROWDASET AI</h3>
                                <span class="flex items-center gap-1 text-[10px] text-teal-400 font-medium">
                                    <span class="w-1.5 h-1.5 rounded-full bg-teal-400 animate-pulse"></span>
                                    <span>Kabupaten Gresik</span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Mode Tabs in Header -->
                    <div class="flex items-center gap-1.5 bg-slate-800/90 p-1 rounded-xl border border-white/5">
                        <button 
                            type="button" 
                            wire:click="$set('isReportMode', false)" 
                            class="px-2.5 py-1 rounded-lg text-xs font-semibold transition-all cursor-pointer {{ !$isReportMode ? 'bg-[#008080] text-white shadow-xs' : 'text-slate-400 hover:text-white' }}"
                        >
                            <span class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                <span class="hidden sm:inline">Konsultasi AI</span>
                            </span>
                        </button>
                        <button 
                            type="button" 
                            wire:click="startReportMode" 
                            class="px-2.5 py-1 rounded-lg text-xs font-semibold transition-all cursor-pointer relative {{ $isReportMode ? 'bg-rose-600 text-white shadow-xs' : 'text-slate-400 hover:text-white' }}"
                        >
                            <span class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span>Lapor Cerdas</span>
                                <span class="w-1.5 h-1.5 rounded-full bg-rose-400 animate-ping"></span>
                            </span>
                        </button>
                    </div>

                    <!-- Actions: Reset, Maximize, Close -->
                    <div class="flex items-center gap-1">
                        <button 
                            type="button" 
                            wire:click="resetChat" 
                            class="p-1.5 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800 transition-colors cursor-pointer"
                            title="Mulai Ulang"
                        >
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        </button>

                        <button 
                            type="button" 
                            wire:click="toggleMaximize" 
                            class="p-1.5 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800 transition-colors cursor-pointer"
                            title="{{ $isMaximized ? 'Perkecil' : 'Perbesar Layar Penuh' }}"
                        >
                            @if($isMaximized)
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 14h6m0 0v6m0-6L3 21m17-7h-6m0 0v6m0-6l7 7m-7-17l7-7m-7 7h6m-6 0V3M10 10l-7-7m7 7H4m6 0V4"/></svg>
                            @else
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 3h6m0 0v6m0-6l-7 7M9 21H3m0 0v-6m0 6l7-7M3 9V3m0 0h6M3 3l7 7m11 11v-6m0 6h-6m6 0l-7-7"/></svg>
                            @endif
                        </button>

                        <button 
                            type="button" 
                            wire:click="$set('isModalOpen', false)" 
                            class="p-1.5 rounded-xl text-slate-400 hover:text-white hover:bg-rose-500/20 hover:text-rose-400 transition-colors cursor-pointer"
                            title="Tutup"
                        >
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>

                {{-- ========================================================================= --}}
                {{-- MODE 1: INTERACTIVE ANIMATED REPORTING FLOW (Ketika Lapor Dipilih)        --}}
                {{-- ========================================================================= --}}
                @if($isReportMode)
                    <div class="flex-1 flex flex-col min-h-0 bg-slate-900 text-white overflow-y-auto">
                        
                        <!-- Animated Progress Stepper Bar -->
                        <div class="bg-slate-950/80 border-b border-white/10 px-4 py-3 shrink-0">
                            <div class="flex items-center justify-between max-w-xl mx-auto relative">
                                <!-- Connecting Progress Line -->
                                <div class="absolute top-1/2 left-4 right-4 -translate-y-1/2 h-0.5 bg-slate-800 -z-0"></div>
                                <div class="absolute top-1/2 left-4 -translate-y-1/2 h-0.5 bg-gradient-to-r from-teal-500 to-rose-500 transition-all duration-500 -z-0"
                                     style="width: {{ ($reportStep - 1) * 33.33 }}%;"></div>

                                <!-- Step 1 -->
                                <div class="flex flex-col items-center gap-1 relative z-10">
                                    <button type="button" wire:click="goToStep(1)" 
                                            class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold transition-all {{ $reportStep >= 1 ? 'bg-teal-500 text-white ring-4 ring-teal-500/20' : 'bg-slate-800 text-slate-400' }}">
                                        1
                                    </button>
                                    <span class="text-[9.5px] {{ $reportStep === 1 ? 'text-teal-400 font-bold' : 'text-slate-400' }}">Keterangan</span>
                                </div>

                                <!-- Step 2 -->
                                <div class="flex flex-col items-center gap-1 relative z-10">
                                    <button type="button" wire:click="goToStep(2)" 
                                            class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold transition-all {{ $reportStep >= 2 ? 'bg-teal-500 text-white ring-4 ring-teal-500/20' : 'bg-slate-800 text-slate-400' }}">
                                        2
                                    </button>
                                    <span class="text-[9.5px] {{ $reportStep === 2 ? 'text-teal-400 font-bold' : 'text-slate-400' }}">Radar GPS</span>
                                </div>

                                <!-- Step 3 -->
                                <div class="flex flex-col items-center gap-1 relative z-10">
                                    <button type="button" wire:click="goToStep(3)" 
                                            class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold transition-all {{ $reportStep >= 3 ? 'bg-teal-500 text-white ring-4 ring-teal-500/20' : 'bg-slate-800 text-slate-400' }}">
                                        3
                                    </button>
                                    <span class="text-[9.5px] {{ $reportStep === 3 ? 'text-teal-400 font-bold' : 'text-slate-400' }}">Analisis AI</span>
                                </div>

                                <!-- Step 4 -->
                                <div class="flex flex-col items-center gap-1 relative z-10">
                                    <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold transition-all {{ $reportStep >= 4 ? 'bg-rose-500 text-white ring-4 ring-rose-500/20' : 'bg-slate-800 text-slate-400' }}">
                                        4
                                    </div>
                                    <span class="text-[9.5px] {{ $reportStep === 4 ? 'text-rose-400 font-bold' : 'text-slate-400' }}">Peta Spasial</span>
                                </div>
                            </div>
                        </div>

                        <!-- Stepper Content Views -->
                        <div class="flex-1 p-5 max-w-2xl w-full mx-auto flex flex-col justify-center">

                            {{-- ========================================== --}}
                            {{-- STEP 1: PANDUAN CARA LAPOR & INPUT DETAIL   --}}
                            {{-- ========================================== --}}
                            @if($reportStep === 1)
                                <div class="space-y-4 animate-fade-in">
                                    
                                    <!-- Animasi Diagram Alur: Bagaimana Cara Melaporkan -->
                                    <div>
                                        <div class="flex items-center gap-2 mb-2">
                                            <span class="w-2 h-2 rounded-full bg-teal-400 animate-pulse"></span>
                                            <h4 class="text-xs font-extrabold uppercase tracking-wider text-teal-400">Alur Pelaporan Pintar Berbasis AI</h4>
                                        </div>

                                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                                            <!-- Card 1: Foto & Kondisi -->
                                            <div class="p-2.5 rounded-2xl bg-white/5 border border-white/10 hover:border-teal-500/50 transition-all text-center group">
                                                <div class="w-8 h-8 rounded-xl bg-teal-500/20 text-teal-400 flex items-center justify-center mx-auto mb-1.5 group-hover:scale-110 transition-transform">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                                </div>
                                                <p class="text-[11px] font-bold text-slate-200">1. Foto & Kondisi</p>
                                                <p class="text-[9.5px] text-slate-400 mt-0.5">Identifikasi aset tidur/rusak</p>
                                            </div>

                                            <!-- Card 2: Scan Satelit -->
                                            <div class="p-2.5 rounded-2xl bg-white/5 border border-white/10 hover:border-teal-500/50 transition-all text-center group">
                                                <div class="w-8 h-8 rounded-xl bg-sky-500/20 text-sky-400 flex items-center justify-center mx-auto mb-1.5 group-hover:scale-110 transition-transform">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="3"/><line x1="22" y1="12" x2="18" y2="12"/><line x1="6" y1="12" x2="2" y2="12"/><line x1="12" y1="6" x2="12" y2="2"/><line x1="12" y1="22" x2="12" y2="18"/></svg>
                                                </div>
                                                <p class="text-[11px] font-bold text-slate-200">2. Scan Satelit</p>
                                                <p class="text-[9.5px] text-slate-400 mt-0.5">Kunci GPS & batas desa</p>
                                            </div>

                                            <!-- Card 3: Analisis AI -->
                                            <div class="p-2.5 rounded-2xl bg-white/5 border border-white/10 hover:border-teal-500/50 transition-all text-center group">
                                                <div class="w-8 h-8 rounded-xl bg-purple-500/20 text-purple-400 flex items-center justify-center mx-auto mb-1.5 group-hover:scale-110 transition-transform">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                                                </div>
                                                <p class="text-[11px] font-bold text-slate-200">3. Analisis AI</p>
                                                <p class="text-[9.5px] text-slate-400 mt-0.5">Solusi & potensi BUMDes</p>
                                            </div>

                                            <!-- Card 4: Peta GIS -->
                                            <div class="p-2.5 rounded-2xl bg-white/5 border border-white/10 hover:border-rose-500/50 transition-all text-center group">
                                                <div class="w-8 h-8 rounded-xl bg-rose-500/20 text-rose-400 flex items-center justify-center mx-auto mb-1.5 group-hover:scale-110 transition-transform">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"/><line x1="8" y1="2" x2="8" y2="18"/><line x1="16" y1="6" x2="16" y2="22"/></svg>
                                                </div>
                                                <p class="text-[11px] font-bold text-slate-200">4. Peta GIS</p>
                                                <p class="text-[9.5px] text-slate-400 mt-0.5">Terpetakan instan di Gresik</p>
                                            </div>
                                        </div>

                                    </div>

                                    <!-- Form Input -->
                                    <div class="bg-white/5 border border-white/10 rounded-2xl p-4 space-y-3">
                                        <div>
                                            <label class="block text-xs font-semibold text-slate-300 mb-1">Nama / Identifikasi Aset</label>
                                            <input 
                                                type="text" 
                                                wire:model="reportTitle" 
                                                placeholder="Contoh: Gedung Tua KUD, Lahan Kosong Perempatan, Pasar Hewan..."
                                                class="w-full px-3.5 py-2.5 rounded-xl bg-slate-800 border border-white/10 text-white text-xs placeholder:text-slate-500 focus:outline-none focus:ring-2 focus:ring-teal-500"
                                            />
                                        </div>

                                        <div>
                                            <label class="block text-xs font-semibold text-slate-300 mb-1">Kondisi Aset Saat Ini</label>
                                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                                                @foreach([
                                                    'terbengkalai' => ['label' => 'Terbengkalai', 'color' => 'border-rose-500 bg-rose-500/20 text-rose-300'],
                                                    'rusak' => ['label' => 'Rusak Berat', 'color' => 'border-amber-500 bg-amber-500/20 text-amber-300'],
                                                    'tidak_digunakan' => ['label' => 'Kosong', 'color' => 'border-blue-500 bg-blue-500/20 text-blue-300'],
                                                    'kurang_produktif' => ['label' => 'Kurang Produktif', 'color' => 'border-teal-500 bg-teal-500/20 text-teal-300'],
                                                ] as $condKey => $condInfo)
                                                    <button 
                                                        type="button" 
                                                        wire:click="$set('reportCondition', '{{ $condKey }}')"
                                                        class="p-2 rounded-xl text-xs font-semibold border transition-all text-center cursor-pointer {{ $reportCondition === $condKey ? $condInfo['color'] . ' ring-2 ring-white/20' : 'border-white/10 bg-slate-800/60 text-slate-400 hover:text-white' }}">
                                                        {{ $condInfo['label'] }}
                                                    </button>
                                                @endforeach
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-semibold text-slate-300 mb-1">Keterangan / Apa yang Anda Lihat di Lokasi?</label>
                                            <textarea 
                                                wire:model="reportDescription" 
                                                rows="3" 
                                                placeholder="Ceritakan detailnya (misal: sudah tidak terpakai 4 tahun, banyak ilalang, dekat pemukiman warga, cocok dijadikan sentra UMKM)..."
                                                class="w-full px-3.5 py-2.5 rounded-xl bg-slate-800 border border-white/10 text-white text-xs placeholder:text-slate-500 focus:outline-none focus:ring-2 focus:ring-teal-500 leading-relaxed"></textarea>
                                        </div>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="flex items-center justify-between gap-3 pt-1">
                                        <button 
                                            type="button" 
                                            wire:click="cancelReportMode" 
                                            class="px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold transition-colors cursor-pointer">
                                            Batal
                                        </button>
                                        <button 
                                            type="button" 
                                            wire:click="goToStep(2)" 
                                            class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-teal-500 to-[#008080] hover:from-teal-400 hover:to-teal-600 text-white text-xs font-bold shadow-lg shadow-teal-500/30 flex items-center gap-2 transition-all cursor-pointer">
                                             <span>Lanjut: Pindai Lokasi GPS & Desa</span>
                                             <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                         </button>
                                     </div>

                                 </div>
                             @endif


                            {{-- ========================================== --}}
                            {{-- STEP 2: RADAR DETEKSI GPS & WILAYAH DESA    --}}
                            {{-- ========================================== --}}
                            @if($reportStep === 2)
                                <div class="space-y-4 animate-fade-in text-center">
                                    
                                    <div>
                                        <h3 class="font-heading font-extrabold text-base text-white">Pemindaian Lokasi Satelit GPS</h3>
                                        <p class="text-xs text-slate-400 mt-0.5">Sistem mendeteksi koordinat Anda dan memetakan desa di Kabupaten Gresik</p>
                                    </div>

                                    <!-- Interactive Radar Scanning Animation Widget -->
                                    <div class="relative w-48 h-48 sm:w-56 sm:h-56 mx-auto flex items-center justify-center">
                                        <!-- Concentric Radar Rings -->
                                        <div class="absolute inset-0 rounded-full border border-teal-500/20 animate-ping [animation-duration:3s]"></div>
                                        <div class="absolute inset-4 rounded-full border border-teal-500/30"></div>
                                        <div class="absolute inset-10 rounded-full border border-teal-500/40"></div>
                                        <div class="absolute inset-16 rounded-full border border-teal-500/50"></div>
                                        
                                        <!-- Crosshair Lines -->
                                        <div class="absolute inset-x-0 top-1/2 -translate-y-1/2 h-px bg-teal-500/30"></div>
                                        <div class="absolute inset-y-0 left-1/2 -translate-x-1/2 w-px bg-teal-500/30"></div>

                                        <!-- Rotating Radar Sweep Beam -->
                                        <div class="absolute inset-0 rounded-full bg-gradient-to-tr from-teal-500/20 via-transparent to-transparent animate-spin [animation-duration:2.5s]"></div>

                                        <!-- Center Beacon / GPS Pin -->
                                        <div class="relative z-10 flex flex-col items-center">
                                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-teal-400 to-[#008080] text-white flex items-center justify-center shadow-lg shadow-teal-500/50">
                                                <svg class="w-6 h-6 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="3"/><line x1="22" y1="12" x2="18" y2="12"/><line x1="6" y1="12" x2="2" y2="12"/><line x1="12" y1="6" x2="12" y2="2"/><line x1="12" y1="22" x2="12" y2="18"/></svg>
                                            </div>
                                            <span class="text-[9px] font-bold text-teal-300 mt-1 bg-slate-900/90 px-2 py-0.5 rounded-md border border-teal-500/40">
                                                {{ $locationLocked ? 'GPS TERKUNCI' : 'MEMINDAI...' }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Status Message -->
                                    <div class="bg-white/5 border border-teal-500/30 rounded-2xl p-3.5 max-w-md mx-auto text-left space-y-2">
                                        <div class="flex items-center justify-between text-xs">
                                            <span class="text-slate-400 flex items-center gap-1.5">
                                                <svg class="w-3.5 h-3.5 text-teal-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                                <span>Wilayah Administrasi:</span>
                                            </span>
                                            <span class="font-bold text-teal-300">
                                                Desa {{ $detectedVillageName ?? 'Manyar Sidomukti' }}
                                            </span>
                                        </div>
                                        <div class="flex items-center justify-between text-xs">
                                            <span class="text-slate-400">Kecamatan:</span>
                                            <span class="font-bold text-slate-200">{{ $detectedDistrictName ?? 'Manyar' }}, Gresik</span>
                                        </div>
                                        <div class="flex items-center justify-between text-xs font-mono">
                                            <span class="text-slate-400">Titik Koordinat:</span>
                                            <span class="text-teal-400">{{ $userLat ?? '-7.1350' }}, {{ $userLng ?? '112.6020' }}</span>
                                        </div>
                                    </div>

                                    <!-- Optional Manual Village Override Selector -->
                                    <div class="max-w-md mx-auto flex items-center gap-2 text-xs">
                                        <span class="text-slate-400 shrink-0">Bukan desa Anda?</span>
                                        <select 
                                            wire:model.live="detectedVillageId" 
                                            class="flex-1 px-3 py-1.5 rounded-xl bg-slate-800 border border-white/10 text-slate-200 text-xs focus:ring-2 focus:ring-teal-500">
                                            @foreach($villages as $v)
                                                <option value="{{ $v->id }}">Desa {{ $v->name }} (Kec. {{ $v->district?->name ?? 'Gresik' }})</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="flex items-center justify-between gap-3 pt-2 max-w-md mx-auto">
                                        <button 
                                            type="button" 
                                            wire:click="goToStep(1)" 
                                            class="px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold transition-colors cursor-pointer">
                                            Kembali
                                        </button>
                                        <button 
                                            type="button" 
                                            wire:click="goToStep(3)" 
                                            class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-teal-500 to-[#008080] hover:from-teal-400 hover:to-teal-600 text-white text-xs font-bold shadow-lg shadow-teal-500/30 flex items-center gap-2 transition-all cursor-pointer">
                                             <span>Analisis AI & Rekomendasi</span>
                                             <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                                         </button>
                                     </div>

                                 </div>
                             @endif


                            {{-- ========================================== --}}
                            {{-- STEP 3: ANALISIS CERDAS AI & KONFIRMASI    --}}
                            {{-- ========================================== --}}
                            @if($reportStep === 3)
                                <div class="space-y-4 animate-fade-in">
                                    
                                    <div class="text-center">
                                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-teal-500/20 text-teal-300 text-xs font-bold border border-teal-500/40 mb-2">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                                            <span>Analisis Cerdas CROWDASET AI</span>
                                        </div>
                                        <h3 class="font-heading font-extrabold text-base text-white">Konfirmasi Laporan Aset Resmi</h3>
                                        <p class="text-xs text-slate-400">AI telah menstrukturkan data laporan Anda untuk diteruskan ke sistem pemerintah daerah</p>
                                    </div>

                                    <!-- Structured Report Card -->
                                    <div class="bg-white/5 border border-white/10 rounded-2xl p-4 space-y-3 text-xs">
                                        <div class="flex items-start justify-between gap-3 pb-3 border-b border-white/10">
                                            <div>
                                                <span class="text-[10px] font-bold text-teal-400 uppercase tracking-wider">Nama Aset</span>
                                                <h4 class="font-heading font-bold text-sm text-white mt-0.5">
                                                    {{ $pendingReport['title'] ?? $reportTitle }}
                                                </h4>
                                            </div>
                                            <span class="px-2.5 py-1 rounded-lg bg-rose-500/20 text-rose-300 border border-rose-500/40 text-[10px] font-bold uppercase shrink-0">
                                                {{ ucwords(str_replace('_', ' ', $reportCondition)) }}
                                            </span>
                                        </div>

                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                            <div class="p-2.5 rounded-xl bg-slate-800/60 border border-white/5">
                                                <span class="text-[10px] text-slate-400 block">Kategori Terdeteksi:</span>
                                                <span class="font-bold text-slate-200 mt-0.5 block">{{ $pendingReport['category_name'] ?? 'Lahan / Bangunan Desa' }}</span>
                                            </div>
                                            <div class="p-2.5 rounded-xl bg-slate-800/60 border border-white/5">
                                                <span class="text-[10px] text-slate-400 block">Desa & Kecamatan:</span>
                                                <span class="font-bold text-slate-200 mt-0.5 block">Desa {{ $detectedVillageName }}, Kec. {{ $detectedDistrictName }}</span>
                                            </div>
                                        </div>

                                        <div class="p-2.5 rounded-xl bg-teal-500/10 border border-teal-500/30">
                                            <span class="text-[10px] font-bold text-teal-400 uppercase tracking-wider block">Rekomendasi Pemanfaatan AI:</span>
                                            <p class="text-xs text-teal-100 font-semibold mt-1">
                                                {{ $pendingReport['suggested_use'] ?? 'Optimalisasi Sentra UMKM Kuliner & Pujasera BUMDes' }}
                                            </p>
                                        </div>

                                        <div class="text-[11px] text-slate-400 flex items-center gap-1.5">
                                            <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                            <span>Notifikasi otomatis akan dikirim ke email aparatur desa & Bappeda Kabupaten Gresik.</span>
                                        </div>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="flex items-center justify-between gap-3 pt-2">
                                        <button 
                                            type="button" 
                                            wire:click="goToStep(2)" 
                                            class="px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold transition-colors cursor-pointer">
                                            Kembali
                                        </button>
                                        <button 
                                            type="button" 
                                            wire:click="submitReportAndRedirectToMap" 
                                            wire:loading.attr="disabled"
                                            class="px-6 py-3 rounded-xl bg-gradient-to-r from-rose-600 to-rose-700 hover:from-rose-500 hover:to-rose-600 text-white text-xs font-extrabold shadow-lg shadow-rose-600/40 flex items-center gap-2 transition-all cursor-pointer">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                            <span wire:loading.remove wire:target="submitReportAndRedirectToMap">Konfirmasi & Terbitkan ke Peta Spasial</span>
                                            <span wire:loading wire:target="submitReportAndRedirectToMap">Memproses Laporan...</span>
                                        </button>
                                    </div>

                                </div>
                            @endif


                            {{-- ========================================== --}}
                            {{-- STEP 4: ANIMASI SUKSES & FLY TO MAP        --}}
                            {{-- ========================================== --}}
                            @if($reportStep === 4)
                                <div class="space-y-5 animate-fade-in text-center py-4">
                                    
                                    <!-- Animated Success Icon -->
                                    <div class="relative w-20 h-20 mx-auto">
                                        <div class="absolute inset-0 rounded-full bg-emerald-500/30 animate-ping [animation-duration:2s]"></div>
                                        <div class="w-20 h-20 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-600 text-white flex items-center justify-center shadow-xl shadow-emerald-500/40 relative z-10">
                                            <svg class="w-10 h-10 stroke-[3]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                        </div>
                                    </div>

                                    <div>
                                        <h3 class="font-heading font-black text-xl text-white">Laporan Berhasil Diterbitkan!</h3>
                                        <p class="text-xs text-slate-300 mt-1 max-w-md mx-auto">
                                            Aset telah tercatat di basis data Kabupaten Gresik dan otomatis diteruskan ke aparatur pemerintah desa untuk verifikasi.
                                        </p>
                                    </div>

                                    <!-- Mini Spatial Radar Preview -->
                                    <div class="p-3.5 rounded-2xl bg-white/5 border border-teal-500/40 max-w-md mx-auto text-left space-y-2">
                                        <div class="flex items-center justify-between text-xs">
                                            <span class="text-slate-400">Lokasi Dilaporkan:</span>
                                            <span class="font-bold text-teal-300">Desa {{ $detectedVillageName ?? 'Manyar' }}</span>
                                        </div>
                                        <div class="flex items-center justify-between text-xs font-mono">
                                            <span class="text-slate-400">Koordinat:</span>
                                            <span class="text-teal-400">{{ $userLat }}, {{ $userLng }}</span>
                                        </div>
                                        <div class="w-full bg-slate-800 h-2 rounded-full overflow-hidden mt-2">
                                            <div class="bg-teal-400 h-full transition-all duration-1000" :style="'width: ' + ((3 - countdown) / 3 * 100) + '%'"></div>
                                        </div>
                                        <p class="text-[11px] text-center text-teal-300 font-semibold animate-pulse mt-1">
                                            Mengalihkan ke Peta Spasial GIS dalam <span x-text="countdown">3</span> detik...
                                        </p>
                                    </div>

                                    <!-- Instant Redirect Button -->
                                    <div>
                                        <a href="{{ $mapRedirectUrl ?? route('map') }}" 
                                           class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl bg-gradient-to-r from-teal-500 to-[#008080] hover:from-teal-400 hover:to-teal-600 text-white font-extrabold text-xs shadow-xl shadow-teal-500/30 transition-all hover:scale-105 active:scale-95">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"/><line x1="8" y1="2" x2="8" y2="18"/><line x1="16" y1="6" x2="16" y2="22"/></svg>
                                            <span>Langsung Buka di Peta GIS Sekarang</span>
                                        </a>
                                    </div>

                                </div>
                            @endif

                        </div>

                    </div>


                {{-- ========================================================================= --}}
                {{-- MODE 2: CHATGPT NORMAL CHAT FEED                                         --}}
                {{-- ========================================================================= --}}
                @else

                    <!-- Asset Focus Header Selector -->
                    <div class="bg-slate-50 border-b border-slate-200 px-4 py-2 flex items-center justify-between gap-3 text-xs shrink-0">
                        <div class="flex items-center gap-2 text-slate-700 font-medium shrink-0">
                            <span class="w-2 h-2 rounded-full bg-teal-500"></span>
                            <span class="text-slate-500">Aset Sasaran:</span>
                        </div>
                        <select wire:model.live="selectedAssetId" class="flex-1 max-w-md px-3 py-1.5 rounded-xl border border-slate-200 bg-white text-slate-800 text-xs font-semibold focus:ring-2 focus:ring-teal-500">
                            @foreach($assets as $a)
                                <option value="{{ $a->id }}">{{ $a->name }} (Desa {{ $a->village->name ?? '-' }})</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Chat Feed -->
                    <div class="flex-1 overflow-y-auto p-4 sm:p-6 space-y-6 bg-slate-50/50" id="chatFeedContainer" x-data x-init="$el.scrollTop = $el.scrollHeight" x-effect="$el.scrollTop = $el.scrollHeight">
                        
                        <!-- Lapor Cerdas Prominent Banner Inside Chat -->
                        <div class="p-3.5 rounded-2xl bg-gradient-to-r from-slate-900 to-slate-800 text-white flex items-center justify-between gap-3 shadow-md border border-rose-500/30">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-xl bg-rose-500/20 text-rose-400 flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-white">Punya Info Aset Tidur / Terbengkalai?</p>
                                    <p class="text-[10px] text-slate-300">Gunakan fitur Lapor Cerdas dengan radar GPS otomatis & langsung tampil di peta.</p>
                                </div>
                            </div>
                            <button 
                                type="button" 
                                wire:click="startReportMode" 
                                class="px-3 py-2 rounded-xl bg-rose-600 hover:bg-rose-500 text-white font-bold text-xs shrink-0 transition-transform active:scale-95 shadow-sm cursor-pointer flex items-center gap-1.5">
                                <span>Mulai Lapor</span>
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </button>
                        </div>

                        <!-- Message History -->
                        @foreach($messages as $msg)
                            @if($msg['role'] === 'assistant')
                                <div class="flex items-start gap-3.5 max-w-[92%] sm:max-w-[88%]" x-data="{ copied: false }">
                                    <div class="w-8 h-8 rounded-xl bg-slate-900 text-teal-400 flex items-center justify-center shrink-0 shadow-xs mt-1 border border-teal-500/30">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                        </svg>
                                    </div>
                                    <div class="space-y-3 flex-1 min-w-0">
                                        <div class="bg-white rounded-3xl rounded-tl-none p-4 sm:p-5 border border-slate-200 shadow-xs text-xs sm:text-[13px] text-slate-800 leading-relaxed">
                                            <div class="prose prose-sm prose-slate max-w-none text-slate-800 leading-relaxed font-sans prose-headings:font-bold prose-headings:text-slate-900 prose-headings:text-sm prose-headings:mt-3 prose-headings:mb-1.5 prose-p:my-1.5 prose-ul:my-1.5 prose-ul:pl-4 prose-ol:my-1.5 prose-ol:pl-4 prose-li:my-0.5 prose-hr:my-3 prose-hr:border-slate-200 prose-strong:text-slate-900 prose-strong:font-bold">
                                                {!! \Illuminate\Support\Str::markdown($msg['text']) !!}
                                            </div>
                                        </div>

                                        <!-- Proposal Card -->
                                        @if(!empty($msg['proposal']))
                                            <div class="bg-teal-50/90 rounded-2xl p-4 border border-teal-200 shadow-xs space-y-2.5">
                                                <div class="flex items-center justify-between gap-2">
                                                    <span class="text-[10px] font-extrabold px-2.5 py-1 rounded-md bg-teal-700 text-white uppercase tracking-wider">
                                                        Rekomendasi {{ $msg['proposal']['category'] }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <h4 class="font-heading font-bold text-xs sm:text-sm text-slate-900">{{ $msg['proposal']['title'] }}</h4>
                                                    <p class="text-xs text-slate-600 mt-1 leading-normal">{{ $msg['proposal']['description'] }}</p>
                                                </div>
                                                <button 
                                                    type="button" 
                                                    wire:click="submitProposal('{{ addslashes($msg['proposal']['title']) }}', '{{ addslashes($msg['proposal']['category']) }}', '{{ addslashes($msg['proposal']['description']) }}', {{ $msg['proposal']['asset_id'] ?? 'null' }})"
                                                    class="w-full py-2.5 px-4 rounded-xl bg-teal-700 hover:bg-teal-800 text-white font-bold text-xs flex items-center justify-center gap-2 shadow-xs transition-all active:scale-95 cursor-pointer"
                                                >
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                    <span>Jadikan Usulan Resmi Warga</span>
                                                </button>
                                            </div>
                                        @endif

                                        <div class="flex items-center gap-3 text-[11px] text-slate-400 pl-1">
                                            <span>{{ $msg['time'] ?? '' }}</span>
                                            <button 
                                                type="button" 
                                                @click="navigator.clipboard.writeText('{{ addslashes($msg['text']) }}'); copied = true; setTimeout(() => copied = false, 2000)"
                                                class="hover:text-slate-600 flex items-center gap-1 transition-colors cursor-pointer"
                                            >
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                                <span x-text="copied ? 'Tersalin!' : 'Salin'">Salin</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <!-- User Message -->
                                <div class="flex items-start justify-end gap-2.5 ml-auto max-w-[85%]">
                                    <div class="space-y-1 text-right">
                                        <div class="bg-slate-900 text-white rounded-3xl rounded-tr-none px-4 py-3 shadow-xs text-xs sm:text-[13px] leading-relaxed text-left">
                                            {{ $msg['text'] }}
                                        </div>
                                        <span class="text-[10px] text-slate-400 block mr-1">{{ $msg['time'] ?? '' }}</span>
                                    </div>
                                </div>
                            @endif
                        @endforeach

                        <!-- Thinking Indicator -->
                        <div wire:loading wire:target="sendMessage,sendQuickPrompt" class="flex items-center gap-3 max-w-[80%] animate-fade-in">
                            <div class="w-8 h-8 rounded-xl bg-slate-900 text-teal-400 flex items-center justify-center shrink-0 border border-teal-500/30">
                                <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                            </div>
                            <div class="bg-white rounded-2xl rounded-tl-none px-4 py-3 border border-slate-200 shadow-xs flex items-center gap-2">
                                <span class="text-xs text-slate-500 font-medium">Asisten AI sedang merumuskan analisis...</span>
                                <span class="flex gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-teal-500 animate-bounce"></span>
                                    <span class="w-1.5 h-1.5 rounded-full bg-teal-500 animate-bounce [animation-delay:0.2s]"></span>
                                    <span class="w-1.5 h-1.5 rounded-full bg-teal-500 animate-bounce [animation-delay:0.4s]"></span>
                                </span>
                            </div>
                        </div>

                    </div>

                    <!-- Bottom Input Bar -->
                    <div class="bg-white border-t border-slate-200 p-3 sm:p-4 shrink-0 space-y-2">
                        <form wire:submit="sendMessage" class="relative flex items-center">
                            <input 
                                type="text" 
                                wire:model="userInput" 
                                placeholder="Ketik keluhan atau ceritakan aset terbengkalai..." 
                                class="w-full pl-4 pr-12 py-3 rounded-2xl border border-slate-300 bg-slate-50 text-slate-900 text-xs sm:text-sm focus:ring-2 focus:ring-slate-900 focus:bg-white focus:outline-none transition-all placeholder:text-slate-400"
                            />
                            <button 
                                type="submit" 
                                class="absolute right-2 p-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold transition-all shadow-xs cursor-pointer active:scale-95 disabled:opacity-50"
                                title="Kirim"
                            >
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                                </svg>
                            </button>
                        </form>
                        <p class="text-[10px] text-center text-slate-400">CROWDASET AI • Ketik <strong>"Lapor"</strong> untuk memulai pelaporan aset tidur dengan panduan animasi &amp; GPS.</p>
                    </div>

                @endif

            </div>
        </div>
    @endif
</div>

