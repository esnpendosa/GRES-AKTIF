<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8" x-data="{ activeImage: '{{ $asset->primary_image_url }}', openSimModal: false }">

    <!-- Top Breadcrumb & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-2 text-xs text-slate-500">
            <a href="{{ route('explore') }}" class="hover:text-teal-600">Jelajahi Aset</a>
            <span>/</span>
            <span>Kecamatan {{ $asset->village && $asset->village->district ? $asset->village->district->name : 'Manyar' }}</span>
            <span>/</span>
            <span class="font-bold text-slate-800">Desa {{ $asset->village ? $asset->village->name : 'Sukomulyo' }}</span>
        </div>

        <div class="flex items-center gap-2.5">
            <!-- Support CTA -->
            <button 
                wire:click="toggleSupport" 
                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-teal-200 bg-teal-50 hover:bg-teal-100 text-teal-800 text-xs font-bold transition-all active:scale-95 shadow-xs"
            >
                <i data-lucide="heart" class="w-4 h-4 text-teal-600 fill-teal-600"></i>
                <span>{{ $asset->supporters_count ?: 213 }} Warga Mendukung</span>
            </button>

            <!-- Government AI Re-analyze trigger (if logged in as admin) -->
            @if(auth()->check() && auth()->user()->isGovernment())
                <button 
                    wire:click="triggerAiAnalysis" 
                    wire:loading.attr="disabled"
                    class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold shadow-xs"
                >
                    <i data-lucide="sparkles" class="w-3.5 h-3.5 text-cyan-400"></i>
                    <span wire:loading.remove>Jalankan AI Analisis</span>
                    <span wire:loading>Menganalisis...</span>
                </button>
            @endif
        </div>
    </div>

    <!-- Main Grid: Media & Overview -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Left Column: Gallery & Core Details (7 cols) -->
        <div class="lg:col-span-7 space-y-6">
            
            <!-- Main Photo Gallery -->
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs space-y-3 p-3">
                <div class="relative h-72 sm:h-96 rounded-xl overflow-hidden bg-slate-100">
                    <img :src="activeImage" alt="{{ $asset->name }}" class="w-full h-full object-cover transition-all duration-300" />
                    
                    <div class="absolute top-4 left-4 flex flex-wrap gap-2">
                        <span class="px-2.5 py-1 rounded-md text-xs font-semibold bg-slate-900/85 text-white backdrop-blur border border-white/10">
                            {{ $asset->category ? $asset->category->name : 'Bangunan' }}
                        </span>
                    </div>

                    <div class="absolute top-4 right-4">
                        <span class="px-2.5 py-1 rounded-md text-xs font-semibold {{ $asset->condition === 'terbengkalai' || $asset->condition === 'rusak' ? 'bg-rose-600 text-white' : 'bg-amber-600 text-white' }} shadow-xs">
                            Kondisi: {{ $asset->condition_label }}
                        </span>
                    </div>

                    <div class="absolute bottom-4 left-4 right-4 text-white bg-gradient-to-t from-black/80 via-black/40 to-transparent p-4 rounded-lg">
                        <span class="inline-block text-[10px] font-bold px-2 py-0.5 rounded bg-teal-700 text-white uppercase tracking-wider mb-1">
                            Status: {{ $asset->status_label }}
                        </span>
                        <h1 class="font-heading font-bold text-xl sm:text-2xl text-white">
                            {{ $asset->name }}
                        </h1>
                    </div>
                </div>

                <!-- Thumbnail strip if multiple images -->
                @if($asset->images->count() > 1)
                    <div class="flex items-center gap-2 overflow-x-auto pb-1">
                        @foreach($asset->images as $img)
                            <button 
                                type="button" 
                                @click="activeImage = '{{ $img->url }}'"
                                class="w-20 h-14 rounded-lg overflow-hidden border-2 flex-shrink-0 transition-all hover:opacity-100"
                                :class="activeImage === '{{ $img->url }}' ? 'border-teal-700 opacity-100 scale-95' : 'border-transparent opacity-60'"
                            >
                                <img src="{{ $img->url }}" class="w-full h-full object-cover" />
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Asset Description & Specifications -->
            <div class="bg-white rounded-2xl p-6 sm:p-7 border border-slate-200 shadow-xs space-y-6">
                <div>
                    <h2 class="font-heading font-bold text-base sm:text-lg text-slate-900 mb-2">Deskripsi & Riwayat Aset</h2>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        {{ $asset->description }}
                    </p>
                </div>

                <!-- Specs Grid -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-4 border-t border-slate-100">
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/80">
                        <span class="text-[11px] text-slate-500 font-medium block">Luas Area</span>
                        <span class="font-bold text-sm text-slate-900">{{ number_format($asset->area, 0, ',', '.') }} m²</span>
                    </div>
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/80">
                        <span class="text-[11px] text-slate-500 font-medium block">Kepemilikan</span>
                        <span class="font-bold text-sm text-slate-900 truncate block">{{ $asset->ownership_type ?? 'Pemerintah Desa' }}</span>
                    </div>
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/80">
                        <span class="text-[11px] text-slate-500 font-medium block">Estimasi Valuasi</span>
                        <span class="font-bold text-sm text-teal-700">Rp {{ number_format($asset->estimated_economic_value / 1000000, 0) }} Juta</span>
                    </div>
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/80">
                        <span class="text-[11px] text-slate-500 font-medium block">Wilayah</span>
                        <span class="font-bold text-sm text-slate-900 truncate block">{{ $asset->village ? $asset->village->name : 'Gresik' }}</span>
                    </div>
                </div>

                <!-- Location Map Snippet -->
                <div class="space-y-2 pt-2 border-t border-slate-100">
                    <div class="flex items-center justify-between text-xs">
                        <span class="font-semibold text-slate-700 flex items-center gap-1.5">
                            <i data-lucide="map-pin" class="w-4 h-4 text-teal-700"></i>
                            <span>{{ $asset->address ?? 'Gresik, Jawa Timur' }}</span>
                        </span>
                        <span class="text-slate-400 font-mono text-[11px]">{{ $asset->latitude }}, {{ $asset->longitude }}</span>
                    </div>

                    <div 
                        id="assetDetailMap" 
                        wire:ignore 
                        class="h-44 w-full rounded-xl border border-slate-200 z-0"
                        x-data="{
                            init() {
                                const map = L.map('assetDetailMap', { zoomControl: false, attributionControl: false }).setView([{{ $asset->latitude }}, {{ $asset->longitude }}], 16);
                                L.tileLayer('https://mt1.google.com/vt/lyrs=y&x={x}&y={y}&z={z}', { maxZoom: 20, subdomains: ['mt0', 'mt1', 'mt2', 'mt3'] }).addTo(map);
                                L.marker([{{ $asset->latitude }}, {{ $asset->longitude }}]).addTo(map);
                            }
                        }"
                    ></div>
                </div>
            </div>

            <!-- PROJECT LIFECYCLE TIMELINE TRACKER -->
            <div class="bg-white rounded-2xl p-6 sm:p-7 border border-slate-200 shadow-xs space-y-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="font-heading font-bold text-lg text-slate-900">Tahapan Aktivasi Aset</h2>
                        <p class="text-xs text-slate-500">Transparansi progres dari laporan warga hingga aktivasi ekonomi nyata.</p>
                    </div>
                    <span class="text-xs font-bold px-3 py-1 rounded-full bg-teal-50 text-teal-700 border border-teal-200">
                        {{ $asset->status_label }}
                    </span>
                </div>

                @php
                    $steps = [
                        ['key' => 'reported', 'label' => 'Dilaporkan', 'desc' => 'Diusulkan masyarakat via crowdsourcing.'],
                        ['key' => 'verified', 'label' => 'Terverifikasi', 'desc' => 'Divalidasi keabsahan oleh Pemdes.'],
                        ['key' => 'ai_analyzed', 'label' => 'Analisis AI', 'desc' => 'Skor kelayakan & rekomendasi fungsi dihitung.'],
                        ['key' => 'community_discussion', 'label' => 'Konsensus Publik', 'desc' => 'Warga mengajukan ide & voting kebutuhan.'],
                        ['key' => 'prioritized', 'label' => 'Diprioritaskan', 'desc' => 'Masuk agenda prioritas kecamatan/kabupaten.'],
                        ['key' => 'planning', 'label' => 'Perencanaan Proyek', 'desc' => 'Penyusunan anggaran APBDes/CSR & skema BUMDes.'],
                        ['key' => 'implementation', 'label' => 'Realisasi Fisik', 'desc' => 'Renovasi fisik dan penataan kios/fasilitas.'],
                        ['key' => 'productive', 'label' => 'Aktif & Produktif', 'desc' => 'Beroperasi menghasilkan perputaran ekonomi.'],
                    ];

                    $statusOrder = ['reported', 'verified', 'ai_analyzed', 'community_discussion', 'prioritized', 'planning', 'implementation', 'productive'];
                    $currentIndex = array_search($asset->status, $statusOrder);
                    if ($currentIndex === false) $currentIndex = 2;
                @endphp

                <div class="relative pl-6 sm:pl-8 space-y-6 before:absolute before:left-2.5 sm:before:left-3.5 before:top-2 before:bottom-2 before:w-0.5 before:bg-slate-200">
                    @foreach($steps as $idx => $step)
                        @php
                            $isPassed = $idx <= $currentIndex;
                            $isCurrent = $idx === $currentIndex;
                        @endphp
                        <div class="relative flex items-start gap-4">
                            <!-- Dot Icon -->
                            <div class="absolute -left-6 sm:-left-8 top-0.5 w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold {{ $isPassed ? 'bg-teal-600 text-white ring-4 ring-teal-100' : 'bg-slate-200 text-slate-500' }}">
                                @if($isPassed && !$isCurrent)
                                    <i data-lucide="check" class="w-3.5 h-3.5"></i>
                                @else
                                    {{ $idx + 1 }}
                                @endif
                            </div>

                            <div class="space-y-0.5">
                                <div class="flex items-center gap-2">
                                    <h4 class="font-bold text-sm {{ $isCurrent ? 'text-teal-700 font-heading' : ($isPassed ? 'text-slate-800' : 'text-slate-400') }}">
                                        {{ $step['label'] }}
                                    </h4>
                                    @if($isCurrent)
                                        <span class="text-[10px] font-bold px-2 py-0.2 rounded-full bg-teal-100 text-teal-800">Tahap Berjalan</span>
                                    @endif
                                </div>
                                <p class="text-xs {{ $isPassed ? 'text-slate-600' : 'text-slate-400' }}">
                                    {{ $step['desc'] }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>

        <!-- Right Column: AI Intelligence & Community Crowd Consensus (5 cols) -->
        <div class="lg:col-span-5 space-y-6">
            
            <!-- KAJIAN KELAYAKAN & POTENSI PEMANFAATAN CARD -->
            <div class="bg-white rounded-2xl p-6 sm:p-7 border border-slate-200 shadow-xs space-y-5">
                
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-xl bg-teal-50 text-teal-700 flex items-center justify-center border border-teal-100">
                            <i data-lucide="bar-chart-3" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <h3 class="font-heading font-bold text-sm sm:text-base text-slate-900">Kajian Kelayakan & Potensi Aset</h3>
                            <p class="text-[11px] text-slate-500">Indeks Komprehensif Tata Ruang & BUMDes</p>
                        </div>
                    </div>

                    <span class="text-xs font-semibold px-2.5 py-1 rounded-md {{ $asset->potential_score >= 71 ? 'bg-teal-50 text-teal-800 border border-teal-200' : 'bg-slate-100 text-slate-700 border border-slate-200' }}">
                        {{ $asset->potential_level }} ({{ $asset->potential_score }}/100)
                    </span>
                </div>

                <!-- Score Highlight Box -->
                <div class="flex items-center justify-between p-4 rounded-xl bg-slate-50 border border-slate-200/80">
                    <div>
                        <div class="flex items-baseline gap-1">
                            <span class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight font-heading">{{ $asset->potential_score }}</span>
                            <span class="text-slate-400 font-bold text-sm">/ 100</span>
                        </div>
                        <p class="text-xs text-slate-600 font-medium mt-0.5">Indeks Kelayakan Re-Aktivasi</p>
                    </div>

                    <div class="text-right">
                        <span class="inline-flex items-center gap-1 text-xs font-bold text-teal-700 bg-teal-100/70 px-2.5 py-1 rounded-md">
                            <i data-lucide="check-circle" class="w-3.5 h-3.5"></i>
                            Sangat Prospektif
                        </span>
                        <p class="text-[10px] text-slate-400 mt-1">Status: Siap Diusulkan ke APBDes</p>
                    </div>
                </div>

                <!-- Parameter Breakdown -->
                <div class="space-y-3 pt-1 text-xs">
                    <div>
                        <div class="flex items-center justify-between text-slate-700 mb-1">
                            <span class="font-medium">Kesesuaian Tata Ruang & Lokasi (20%)</span>
                            <span class="font-bold text-slate-900">{{ $asset->location_score }}/100</span>
                        </div>
                        <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                            <div class="bg-teal-600 h-full rounded-full" style="width: {{ $asset->location_score }}%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between text-slate-700 mb-1">
                            <span class="font-medium">Aksesibilitas & Infrastruktur Jalan (15%)</span>
                            <span class="font-bold text-slate-900">{{ $asset->accessibility_score }}/100</span>
                        </div>
                        <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                            <div class="bg-teal-600 h-full rounded-full" style="width: {{ $asset->accessibility_score }}%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between text-slate-700 mb-1">
                            <span class="font-medium">Dukungan & Kebutuhan Komunitas (15%)</span>
                            <span class="font-bold text-slate-900">{{ $asset->community_demand_score }}/100</span>
                        </div>
                        <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                            <div class="bg-teal-600 h-full rounded-full" style="width: {{ $asset->community_demand_score }}%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between text-slate-700 mb-1">
                            <span class="font-medium">Estimasi Perputaran Ekonomi BUMDes (15%)</span>
                            <span class="font-bold text-slate-900">{{ $asset->economic_score }}/100</span>
                        </div>
                        <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                            <div class="bg-teal-600 h-full rounded-full" style="width: {{ $asset->economic_score }}%"></div>
                        </div>
                    </div>
                </div>

                <!-- Rekomendasi Fungsi -->
                <div class="pt-4 border-t border-slate-100 space-y-2.5">
                    <div class="flex items-center justify-between">
                        <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Rekomendasi Fungsi Optimal</h4>
                        <span class="text-[11px] text-teal-700 font-semibold">Tingkat Kesesuaian 94%</span>
                    </div>

                    @if($asset->latestAiAnalysis && !empty($asset->latestAiAnalysis->recommendations))
                        <div class="space-y-2">
                            @foreach($asset->latestAiAnalysis->recommendations as $rec)
                                <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/80 flex items-start justify-between gap-3">
                                    <div class="space-y-0.5">
                                        <span class="text-xs font-bold text-slate-900 block">{{ $rec['title'] }}</span>
                                        <p class="text-[11px] text-slate-600 leading-snug">{{ $rec['description'] ?? '' }}</p>
                                    </div>
                                    <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-teal-50 text-teal-800 border border-teal-200 shrink-0">
                                        {{ $rec['match_percentage'] ?? 90 }}%
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/80 flex items-start justify-between gap-3">
                            <div class="space-y-0.5">
                                <span class="text-xs font-bold text-slate-900 block">Sentra UMKM Desa & Pusat Kuliner Khas Gresik</span>
                                <p class="text-[11px] text-slate-600 leading-snug">Konversi lahan menjadi kios modular BUMDes untuk pemasaran produk unggulan dan olahan lokal.</p>
                            </div>
                            <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-teal-50 text-teal-800 border border-teal-200 shrink-0">
                                94%
                            </span>
                        </div>
                    @endif
                </div>

                <!-- Feasibility Simulator CTA -->
                <button 
                    type="button" 
                    @click="openSimModal = true"
                    class="w-full py-2.5 px-4 rounded-xl bg-teal-700 hover:bg-teal-800 text-white font-bold text-xs flex items-center justify-center gap-2 shadow-xs transition-colors"
                >
                    <i data-lucide="calculator" class="w-4 h-4 text-white"></i>
                    <span>Simulasikan Model Bisnis & Kelayakan</span>
                </button>

                <!-- Technical Note -->
                <p class="text-[10px] text-slate-400 text-center leading-relaxed">
                    * Analisis kelayakan merupakan instrumen pendukung keputusan (decision support system) berbasis data spasial dan masukan warga.
                </p>
            </div>

            <!-- COMMUNITY CONSENSUS CLUSTER CARD -->
            <div class="bg-white rounded-2xl p-6 sm:p-7 border border-slate-200 shadow-xs space-y-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-xl bg-teal-50 text-teal-700 flex items-center justify-center border border-teal-100">
                            <i data-lucide="users" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <h3 class="font-heading font-bold text-sm sm:text-base text-slate-900">Konsensus Aspirasi Komunitas</h3>
                            <p class="text-[11px] text-slate-500">Agregasi Kebutuhan Warga & Pelaku Usaha</p>
                        </div>
                    </div>
                </div>

                <div class="p-3.5 rounded-xl bg-teal-50/70 border border-teal-200 text-xs text-teal-950 leading-relaxed font-medium">
                    "{{ $asset->consensus ? $asset->consensus->consensus_summary : 'Mayoritas masyarakat (75%) mengusulkan pemanfaatan aset ini sebagai Sentra UMKM dan Pusat Kuliner Warga.' }}"
                </div>

                <!-- Cluster Breakdown Bars -->
                @php
                    $clusters = $asset->consensus && $asset->consensus->clusters ? $asset->consensus->clusters : [
                        ['category' => 'Sentra UMKM & Display Produk', 'percentage' => 44, 'votes' => 42],
                        ['category' => 'Pusat Kuliner / Food Court', 'percentage' => 31, 'votes' => 31],
                        ['category' => 'Balai Pelatihan & Kursus', 'percentage' => 17, 'votes' => 18],
                        ['category' => 'Fasilitas Publik Lainnya', 'percentage' => 8, 'votes' => 7],
                    ];
                @endphp

                <div class="space-y-3 pt-2">
                    @foreach($clusters as $cls)
                        <div class="space-y-1">
                            <div class="flex items-center justify-between text-xs">
                                <span class="font-semibold text-slate-700">{{ $cls['category'] }}</span>
                                <span class="font-bold text-slate-900">{{ $cls['percentage'] }}% ({{ $cls['votes'] ?? 0 }} suara)</span>
                            </div>
                            <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                                <div class="bg-teal-600 h-full rounded-full" style="width: {{ $cls['percentage'] }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- COMMUNITY IDEAS & VOTING FEED -->
            <div id="ide-warga" class="bg-white rounded-2xl p-6 sm:p-7 border border-slate-200 shadow-xs space-y-5 scroll-mt-20">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-heading font-bold text-sm sm:text-base text-slate-900">Ide Pemanfaatan Warga</h3>
                        <p class="text-[11px] text-slate-500">{{ $ideas->count() }} usulan gagasan terdaftar</p>
                    </div>

                    <button 
                        type="button" 
                        wire:click="$set('showIdeaModal', true)"
                        class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-teal-700 hover:bg-teal-800 text-white text-xs font-bold shadow-xs transition-colors"
                    >
                        <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                        <span>+ Usulkan Ide</span>
                    </button>
                </div>

                <!-- Sorting Pills -->
                <div class="flex items-center gap-1.5 text-xs border-b border-slate-100 pb-2.5">
                    <span class="text-[11px] text-slate-400">Urutkan:</span>
                    <button wire:click="$set('ideasSort', 'votes')" class="px-2.5 py-1 rounded-lg {{ $ideasSort === 'votes' ? 'bg-slate-900 text-white font-bold' : 'text-slate-600 hover:bg-slate-100' }}">Terpopuler</button>
                    <button wire:click="$set('ideasSort', 'latest')" class="px-2.5 py-1 rounded-lg {{ $ideasSort === 'latest' ? 'bg-slate-900 text-white font-bold' : 'text-slate-600 hover:bg-slate-100' }}">Terbaru</button>
                    <button wire:click="$set('ideasSort', 'ai')" class="px-2.5 py-1 rounded-lg {{ $ideasSort === 'ai' ? 'bg-slate-900 text-white font-bold' : 'text-slate-600 hover:bg-slate-100' }}">Rekomendasi</button>
                </div>

                <!-- Ideas List -->
                <div class="space-y-3">
                    @forelse($ideas as $idea)
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 space-y-2.5">
                            <div class="flex items-start justify-between gap-3">
                                <div class="space-y-1">
                                    <div class="flex items-center gap-2">
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-slate-200 text-slate-700">{{ $idea->category }}</span>
                                        @if($idea->is_ai_recommended)
                                            <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-teal-50 text-teal-800 border border-teal-200 flex items-center gap-1">
                                                <i data-lucide="check" class="w-3 h-3 text-teal-600"></i> Terpilih
                                            </span>
                                        @endif
                                    </div>
                                    <h4 class="font-bold text-xs text-slate-900 leading-snug">{{ $idea->title }}</h4>
                                </div>

                                <!-- Upvote Button -->
                                <button 
                                    wire:click="voteIdea({{ $idea->id }})" 
                                    class="flex flex-col items-center px-2.5 py-1.5 rounded-xl border {{ $idea->isVotedBy(auth()->user()) ? 'bg-teal-700 border-teal-700 text-white' : 'bg-white border-slate-200 text-slate-700 hover:border-teal-500' }} transition-all shrink-0"
                                >
                                    <i data-lucide="thumbs-up" class="w-3.5 h-3.5"></i>
                                    <span class="text-xs font-bold">{{ $idea->votes_count }}</span>
                                </button>
                            </div>

                            @if($idea->description)
                                <p class="text-xs text-slate-600 leading-relaxed">{{ $idea->description }}</p>
                            @endif

                            <div class="text-[10px] text-slate-400 flex items-center justify-between pt-1">
                                <span>Oleh: {{ $idea->user ? $idea->user->name : 'Warga Sukomulyo' }}</span>
                                <span>{{ $idea->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center text-xs text-slate-500 bg-slate-50 rounded-xl">
                            Belum ada ide yang diajukan. Jadilah yang pertama memberikan gagasan!
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- COMMENTS STREAM -->
            <div class="bg-white rounded-2xl p-6 sm:p-7 border border-slate-200 shadow-xs space-y-4">
                <h3 class="font-heading font-bold text-sm sm:text-base text-slate-900">Diskusi Komunitas ({{ $comments->count() }})</h3>

                <!-- Comment Input -->
                <form wire:submit.prevent="submitComment" class="space-y-2.5">
                    <textarea 
                        wire:model="newComment" 
                        rows="2" 
                        placeholder="Tulis tanggapan atau usulan Anda terkait aset ini..." 
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-teal-600 focus:border-teal-600"
                    ></textarea>
                    <div class="flex justify-end">
                        <button type="submit" class="px-4 py-2 rounded-xl bg-teal-700 hover:bg-teal-800 text-white text-xs font-bold shadow-xs transition-colors">
                            Kirim Komentar (+5 pts)
                        </button>
                    </div>
                </form>

                <!-- Comments List -->
                <div class="space-y-2.5 pt-2">
                    @foreach($comments as $cm)
                        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/80 text-xs space-y-1">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-slate-800">{{ $cm->user ? $cm->user->name : 'Warga Sukomulyo' }}</span>
                                <span class="text-[10px] text-slate-400">{{ $cm->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-slate-600 leading-relaxed">{{ $cm->comment }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>

    </div>

    <!-- NEW IDEA MODAL -->
    @if($showIdeaModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-xs">
            <div class="bg-white rounded-2xl p-6 sm:p-8 max-w-lg w-full border border-slate-200 shadow-2xl space-y-5 animate-scale-in">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-[11px] font-bold text-teal-700 uppercase tracking-wider">Partisipasi Warga</span>
                        <h3 class="font-heading font-bold text-lg text-slate-900">Ajukan Gagasan Ide Baru</h3>
                    </div>
                    <button wire:click="$set('showIdeaModal', false)" class="text-slate-400 hover:text-slate-600">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <div class="space-y-3.5 text-xs">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Kategori Pemanfaatan</label>
                        <select wire:model="ideaCategory" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white text-slate-800">
                            <option value="UMKM">Sentra UMKM / Display Produk Lokal</option>
                            <option value="Kuliner">Pusat Kuliner / Pujasera Warga</option>
                            <option value="Pertanian">Pertanian / Urban Farming / Budidaya</option>
                            <option value="Pariwisata">Pariwisata / Ekowisata & Rekreasi</option>
                            <option value="Pendidikan">Balai Pelatihan Kerja & Vokasi</option>
                            <option value="Olahraga">Fasilitas Olahraga / Sport Center</option>
                            <option value="Coworking">Coworking Space & Digital Hub</option>
                            <option value="Lainnya">Fasilitas Publik Lainnya</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Judul Ide Gagasan <span class="text-rose-500">*</span></label>
                        <input type="text" wire:model="ideaTitle" placeholder="Contoh: Sentra Olahan Bandeng & Pudak Khas Gresik" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs" />
                        @error('ideaTitle') <span class="text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Penjelasan Singkat Rencana Pemanfaatan</label>
                        <textarea wire:model="ideaDescription" rows="3" placeholder="Jelaskan bagaimana ide ini bisa memberdayakan warga sekitar atau BUMDes..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs"></textarea>
                    </div>

                    <div class="p-3 rounded-xl bg-teal-50 border border-teal-200 text-teal-900 flex items-center justify-between">
                        <span class="font-medium">Poin Reward Kontribusi Komunitas:</span>
                        <span class="font-bold">+10 Poin</span>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="button" wire:click="$set('showIdeaModal', false)" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100">
                        Batal
                    </button>
                    <button type="button" wire:click="submitIdea" class="px-5 py-2.5 rounded-xl text-xs font-bold bg-teal-700 hover:bg-teal-800 text-white shadow-xs">
                        Kirim Gagasan
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- FEASIBILITY & BUSINESS MODEL SIMULATION MODAL -->
    <div x-show="openSimModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-xs">
        <div class="bg-white rounded-2xl p-6 sm:p-8 max-w-4xl w-full border border-slate-200 shadow-2xl space-y-6 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-700 flex items-center justify-center border border-teal-100">
                        <i data-lucide="calculator" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <span class="text-[11px] font-bold text-teal-700 uppercase tracking-wider">Simulasi Model Ekonomi BUMDes</span>
                        <h3 class="font-heading font-bold text-lg sm:text-xl text-slate-900">Komparasi Skenario Pemanfaatan Aset</h3>
                    </div>
                </div>
                <button @click="openSimModal = false" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <!-- Scenarios Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                
                <!-- SCENARIO A: SENTRA UMKM -->
                <div class="p-5 rounded-xl border-2 border-teal-600 bg-teal-50/40 flex flex-col justify-between space-y-4 relative">
                    <span class="absolute top-3 right-3 text-[10px] font-bold px-2 py-0.5 rounded-md bg-teal-700 text-white">SKENARIO A</span>
                    <div class="space-y-2">
                        <span class="text-xs font-bold text-teal-800 uppercase">Sentra UMKM Desa</span>
                        <h4 class="font-heading font-bold text-base text-slate-900 leading-snug">Sentra Display & Ritel BUMDes</h4>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            Konversi ruang menjadi 15 kios modular untuk produk olahan bandeng, pudak, dan kerajinan.
                        </p>
                    </div>

                    <div class="space-y-2 pt-3 border-t border-teal-200 text-xs">
                        <div class="flex justify-between text-slate-600">
                            <span>Estimasi Tenant:</span>
                            <span class="font-bold text-slate-900">15 Kios</span>
                        </div>
                        <div class="flex justify-between text-slate-600">
                            <span>Penyerapan Kerja:</span>
                            <span class="font-bold text-slate-900">30 - 40 Orang</span>
                        </div>
                        <div class="flex justify-between text-slate-600">
                            <span>Kebutuhan Renovasi:</span>
                            <span class="font-bold text-slate-900">Sedang (Rp 85 Juta)</span>
                        </div>
                        <div class="flex justify-between text-teal-800 font-semibold">
                            <span>Dampak Ekonomi:</span>
                            <span class="font-bold">Tinggi</span>
                        </div>
                    </div>

                    <div class="pt-2">
                        <div class="flex items-center justify-between text-xs font-bold">
                            <span>Skor Kelayakan:</span>
                            <span class="text-teal-700 text-sm">94 / 100</span>
                        </div>
                    </div>
                </div>

                <!-- SCENARIO B: CULINARY CENTER -->
                <div class="p-5 rounded-xl border border-slate-200 bg-slate-50 flex flex-col justify-between space-y-4 relative">
                    <span class="absolute top-3 right-3 text-[10px] font-bold px-2 py-0.5 rounded-md bg-slate-700 text-white">SKENARIO B</span>
                    <div class="space-y-2">
                        <span class="text-xs font-bold text-amber-800 uppercase">Pusat Kuliner</span>
                        <h4 class="font-heading font-bold text-base text-slate-900 leading-snug">Pujasera Malam & Kafe Warga</h4>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            Pemanfaatan pelataran untuk 12 lapak kuliner malam dengan penataan pencahayaan terpadu.
                        </p>
                    </div>

                    <div class="space-y-2 pt-3 border-t border-slate-200 text-xs">
                        <div class="flex justify-between text-slate-600">
                            <span>Estimasi Tenant:</span>
                            <span class="font-bold text-slate-900">12 Stan</span>
                        </div>
                        <div class="flex justify-between text-slate-600">
                            <span>Penyerapan Kerja:</span>
                            <span class="font-bold text-slate-900">20 - 25 Orang</span>
                        </div>
                        <div class="flex justify-between text-slate-600">
                            <span>Kebutuhan Renovasi:</span>
                            <span class="font-bold text-slate-900">Ringan (Rp 45 Juta)</span>
                        </div>
                        <div class="flex justify-between text-slate-800 font-semibold">
                            <span>Dampak Ekonomi:</span>
                            <span class="font-bold">Menengah-Tinggi</span>
                        </div>
                    </div>

                    <div class="pt-2">
                        <div class="flex items-center justify-between text-xs font-bold">
                            <span>Skor Kelayakan:</span>
                            <span class="text-slate-800 text-sm">87 / 100</span>
                        </div>
                    </div>
                </div>

                <!-- SCENARIO C: TRAINING CENTER -->
                <div class="p-5 rounded-xl border border-slate-200 bg-slate-50 flex flex-col justify-between space-y-4 relative">
                    <span class="absolute top-3 right-3 text-[10px] font-bold px-2 py-0.5 rounded-md bg-slate-700 text-white">SKENARIO C</span>
                    <div class="space-y-2">
                        <span class="text-xs font-bold text-slate-700 uppercase">Pelatihan Vokasi</span>
                        <h4 class="font-heading font-bold text-base text-slate-900 leading-snug">Balai Pelatihan & Digital Hub</h4>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            Penyediaan ruang kursus vokasi, digital marketing, dan sertifikasi keahlian industri pemuda desa.
                        </p>
                    </div>

                    <div class="space-y-2 pt-3 border-t border-slate-200 text-xs">
                        <div class="flex justify-between text-slate-600">
                            <span>Estimasi Tenant:</span>
                            <span class="font-bold text-slate-900">4 Kelas</span>
                        </div>
                        <div class="flex justify-between text-slate-600">
                            <span>Penyerapan Kerja:</span>
                            <span class="font-bold text-slate-900">10 Orang</span>
                        </div>
                        <div class="flex justify-between text-slate-600">
                            <span>Kebutuhan Renovasi:</span>
                            <span class="font-bold text-slate-900">Ringan (Rp 35 Juta)</span>
                        </div>
                        <div class="flex justify-between text-slate-800 font-semibold">
                            <span>Dampak Ekonomi:</span>
                            <span class="font-bold">Jangka Panjang</span>
                        </div>
                    </div>

                    <div class="pt-2">
                        <div class="flex items-center justify-between text-xs font-bold">
                            <span>Skor Kelayakan:</span>
                            <span class="text-slate-800 text-sm">81 / 100</span>
                        </div>
                    </div>
                </div>

            </div>

            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 text-slate-700 text-xs space-y-1">
                <p class="font-bold flex items-center gap-1.5 text-slate-900">
                    <i data-lucide="info" class="w-4 h-4 text-teal-700"></i>
                    <span>Catatan Transparansi Kajian Teknis</span>
                </p>
                <p class="text-[11px] leading-relaxed text-slate-600">
                    Angka proyeksi di atas merupakan simulasi indikatif berbasis pemodelan data spasial dan kebutuhan riil warga. Pemerintah desa dan pengelola BUMDes disarankan untuk memverifikasi rencana teknis sebelum penetapan alokasi APBDes.
                </p>
            </div>

            <div class="flex justify-end">
                <button @click="openSimModal = false" class="px-5 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition-colors">
                    Tutup Simulasi
                </button>
            </div>
        </div>
    </div>

</div>
