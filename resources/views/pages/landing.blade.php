@extends('layouts.admin')

@section('content')
<div class="bg-[#f8fafc] min-h-screen py-6 sm:py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 sm:space-y-8">

        <!-- ========================================================================= -->
        <!-- 1. HERO SECTION: 2-COLUMN GRID (BANNER + SATELLITE GIS MAP CARD)          -->
        <!-- ========================================================================= -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-stretch">
            
            <!-- Left Hero Banner Card (6 cols) -->
            <div class="lg:col-span-6 bg-gradient-to-br from-sky-50 via-teal-50/30 to-white rounded-2xl border border-sky-100 p-6 sm:p-8 flex flex-col justify-between shadow-xs relative overflow-hidden">
                
                <!-- Subtle Decorative Background Shape -->
                <div class="absolute right-0 bottom-0 w-48 sm:w-56 h-48 sm:h-56 opacity-80 pointer-events-none transform translate-x-4 translate-y-4">
                    <svg viewBox="0 0 200 200" class="w-full h-full" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="120" cy="120" r="70" fill="#0284c7" fill-opacity="0.06"/>
                        <path d="M120 40 L130 90 L155 120 L135 150 L115 150 L95 120 L110 90 Z" fill="#008080" fill-opacity="0.12"/>
                    </svg>
                </div>

                <div class="space-y-4 relative z-10">
                    <!-- Location Badge -->
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-teal-100/70 text-teal-800 text-xs font-bold border border-teal-200/60">
                        <i data-lucide="map-pin" class="w-3.5 h-3.5 text-teal-700"></i>
                        <span>Pemerintah Kabupaten Gresik</span>
                    </div>

                    <!-- Main Headline -->
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 tracking-tight leading-tight font-heading">
                        Optimalisasi & Aktivasi Aset Desa Menjadi Penggerak Ekonomi.
                    </h1>

                    <!-- Subtitle Paragraph -->
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed max-w-lg">
                        Platform kolaboratif Pemerintah Kabupaten Gresik untuk menginventarisasi aset desa yang belum termanfaatkan, menganalisis kelayakan fungsi ekonomi baru, dan mendukung kemandirian BUMDes secara terukur dan transparan.
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-wrap items-center gap-3 pt-6 relative z-10">
                    <a href="{{ route('reports.create') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-[#008080] hover:bg-[#006666] text-white font-bold text-xs shadow-sm transition-all">
                        <i data-lucide="plus-circle" class="w-4 h-4"></i>
                        <span>Lapor Aset Desa &rarr;</span>
                    </a>

                    <a href="{{ route('map') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-white hover:bg-slate-50 text-[#008080] font-bold text-xs border border-[#008080] shadow-xs transition-colors">
                        <i data-lucide="map" class="w-4 h-4"></i>
                        <span>Lihat Peta Spasial GIS &rarr;</span>
                    </a>
                </div>

            </div>

            <!-- Right Hero Card: Satellite GIS Map Card (6 cols) -->
            <div class="lg:col-span-6 bg-slate-900 rounded-2xl border border-slate-700/80 overflow-hidden shadow-md relative min-h-[380px] flex flex-col justify-between">
                
                <!-- Satellite Map Canvas -->
                <div 
                    id="satelliteHeroMap" 
                    class="absolute inset-0 z-0 bg-slate-900"
                    x-data="{
                        init() {
                            const map = L.map('satelliteHeroMap', { 
                                zoomControl: false, 
                                attributionControl: false 
                            }).setView([-7.1350, 112.5950], 12);

                            // Google Earth Satellite Hybrid Tiles
                            L.tileLayer('https://mt1.google.com/vt/lyrs=y&x={x}&y={y}&z={z}', { 
                                maxZoom: 20,
                                subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
                            }).addTo(map);

                            // Gresik District Boundary Polygons
                            const manyarPoly = [[-7.1000, 112.5600], [-7.1100, 112.6300], [-7.1600, 112.6200], [-7.1500, 112.5500]];
                            L.polygon(manyarPoly, { color: '#00c9a7', weight: 2, fillOpacity: 0.15 }).addTo(map);

                            // Authentic GIS Location Markers
                            const markers = [
                                { lat: -7.1350, lng: 112.6020, name: 'Kec. Manyar (Gedung Pertemuan Sukomulyo)', cat: 'Bangunan', color: '#0284c7' },
                                { lat: -7.1580, lng: 112.6500, name: 'Kec. Gresik (Sentra Kuliner Bandar)', cat: 'Ekonomi', color: '#f59e0b' },
                                { lat: -7.1700, lng: 112.6100, name: 'Kec. Kebomas (Lahan Terbuka Giri)', cat: 'Tanah', color: '#10b981' },
                                { lat: -7.2600, lng: 112.5800, name: 'Kec. Driyorejo (Pasar Desa Kreatif)', cat: 'Sosial', color: '#8b5cf6' },
                                { lat: -7.1100, lng: 112.5700, name: 'Kec. Manyar (Hutan Bambu)', cat: 'Lingkungan', color: '#00c9a7' },
                                { lat: -7.1450, lng: 112.6350, name: 'Pelabuhan Gresik (Infrastruktur)', cat: 'Infrastruktur', color: '#38bdf8' }
                            ];

                            markers.forEach(m => {
                                const customIcon = L.divIcon({
                                    className: 'map-badge',
                                    html: `<div style='background-color: ${m.color}; width: 22px; height: 22px; border-radius: 50%; border: 2px solid white; display: flex; align-items: center; justify-content: center; color: white; font-size: 10px; font-weight: 800; box-shadow: 0 2px 6px rgba(0,0,0,0.5);'>•</div>`,
                                    iconSize: [22, 22],
                                    iconAnchor: [11, 11]
                                });
                                L.marker([m.lat, m.lng], { icon: customIcon }).bindPopup(`<b>${m.name}</b><br><span>Kategori: ${m.cat}</span>`).addTo(map);
                            });
                        }
                    }"
                ></div>

                <!-- Top Map Overlay Controls -->
                <div class="relative z-10 p-3 flex flex-wrap items-center justify-between gap-2">
                    
                    <!-- Dropdown Header -->
                    <div class="bg-slate-900/90 backdrop-blur-md text-white px-3 py-1.5 rounded-lg border border-slate-700/80 text-xs font-bold flex items-center gap-1.5 shadow-sm">
                        <i data-lucide="map" class="w-3.5 h-3.5 text-teal-400"></i>
                        <span>Peta Sebaran Aset Desa - Kabupaten Gresik</span>
                    </div>

                    <!-- Legend Box on Top Right -->
                    <div class="bg-slate-900/90 backdrop-blur-md text-white p-2.5 rounded-xl border border-slate-700/80 text-[10px] space-y-1 shadow-lg hidden sm:block">
                        <span class="font-bold text-slate-300 block mb-1 text-[9px] uppercase tracking-wider">Kategori Aset</span>
                        <div class="grid grid-cols-2 gap-x-3 gap-y-1">
                            <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-emerald-500"></span> Tanah</span>
                            <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-sky-500"></span> Bangunan</span>
                            <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-cyan-400"></span> Infrastruktur</span>
                            <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-amber-500"></span> Ekonomi</span>
                            <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-purple-500"></span> Sosial</span>
                            <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-teal-400"></span> Lingkungan</span>
                        </div>
                    </div>

                </div>

                <!-- Category Pills Filter Strip (Clean, No Scrollbar) -->
                <div class="relative z-10 px-3 flex items-center gap-1.5 text-[10.5px] overflow-x-auto [scrollbar-width:none] [-ms-overflow-style:none] [&::-webkit-scrollbar]:hidden">
                    <a href="{{ route('map') }}" class="px-2.5 py-1 rounded-md bg-teal-600 text-white font-bold shrink-0 shadow-xs">Semua Aset</a>
                    <a href="{{ route('map') }}" class="px-2.5 py-1 rounded-md bg-slate-900/80 backdrop-blur-sm text-slate-200 border border-slate-700 font-medium hover:bg-slate-800 shrink-0">Infrastruktur</a>
                    <a href="{{ route('map') }}" class="px-2.5 py-1 rounded-md bg-slate-900/80 backdrop-blur-sm text-slate-200 border border-slate-700 font-medium hover:bg-slate-800 shrink-0">Ekonomi</a>
                    <a href="{{ route('map') }}" class="px-2.5 py-1 rounded-md bg-slate-900/80 backdrop-blur-sm text-slate-200 border border-slate-700 font-medium hover:bg-slate-800 shrink-0">Sosial</a>
                    <a href="{{ route('map') }}" class="px-2.5 py-1 rounded-md bg-slate-900/80 backdrop-blur-sm text-slate-200 border border-slate-700 font-medium hover:bg-slate-800 shrink-0">Lingkungan</a>
                </div>

                <!-- Bottom Map Overlays: District Labels & Mini Inset Map -->
                <div class="relative z-10 p-3 flex items-end justify-between gap-2">
                    <div class="bg-slate-900/90 backdrop-blur-md text-white px-3 py-1 rounded-md border border-slate-700 text-[11px] font-semibold flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-teal-400"></span>
                        <span>Kabupaten Gresik</span>
                    </div>

                    <a href="{{ route('map') }}" class="px-3 py-1 rounded-md bg-white/90 hover:bg-white text-slate-900 font-bold text-[11px] flex items-center gap-1 transition-colors">
                        <i data-lucide="maximize-2" class="w-3.5 h-3.5"></i>
                        <span>Buka Peta Penuh</span>
                    </a>
                </div>

            </div>

        </div>

        <!-- ========================================================================= -->
        <!-- 2. 4 MACRO METRIC CARDS ROW                                               -->
        <!-- ========================================================================= -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            
            <!-- Card 1: Total Aset -->
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs flex items-center gap-4 hover:shadow-md transition-shadow">
                <div class="w-12 h-12 rounded-2xl bg-sky-100 text-sky-700 flex items-center justify-center shrink-0">
                    <i data-lucide="building-2" class="w-6 h-6"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">TOTAL ASET TERIDENTIFIKASI</span>
                        <span class="text-[10px] font-bold text-emerald-700 bg-emerald-100 px-1.5 py-0.2 rounded">+12%</span>
                    </div>
                    <p class="text-xl sm:text-2xl font-extrabold text-slate-900 mt-0.5 font-heading">{{ number_format($totalAssets ?: 412) }} Aset</p>
                    <p class="text-[11px] text-slate-500">Bangunan, lahan, dan aset lainnya</p>
                </div>
            </div>

            <!-- Card 2: Desa Terlibat -->
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs flex items-center gap-4 hover:shadow-md transition-shadow">
                <div class="w-12 h-12 rounded-2xl bg-teal-100 text-teal-700 flex items-center justify-center shrink-0">
                    <i data-lucide="compass" class="w-6 h-6"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">WILAYAH TERINTEGRASI</span>
                        <span class="text-[10px] font-bold text-emerald-700 bg-emerald-100 px-1.5 py-0.2 rounded">+8%</span>
                    </div>
                    <p class="text-xl sm:text-2xl font-extrabold text-slate-900 mt-0.5 font-heading">16 Kecamatan</p>
                    <p class="text-[11px] text-slate-500">356 desa/kelurahan binaan</p>
                </div>
            </div>

            <!-- Card 3: Nilai Ekonomi Potensial -->
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs flex items-center gap-4 hover:shadow-md transition-shadow">
                <div class="w-12 h-12 rounded-2xl bg-amber-100 text-amber-700 flex items-center justify-center shrink-0">
                    <i data-lucide="coins" class="w-6 h-6"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">NILAI EKONOMI POTENSIAL</span>
                        <span class="text-[10px] font-bold text-emerald-700 bg-emerald-100 px-1.5 py-0.2 rounded">+15%</span>
                    </div>
                    <p class="text-xl sm:text-2xl font-extrabold text-slate-900 mt-0.5 font-heading">Rp 1,42 T</p>
                    <p class="text-[11px] text-slate-500">Potensi nilai ekonomi aset daerah</p>
                </div>
            </div>

            <!-- Card 4: Aset Tervalidasi -->
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs flex items-center gap-4 hover:shadow-md transition-shadow">
                <div class="w-12 h-12 rounded-2xl bg-purple-100 text-purple-700 flex items-center justify-center shrink-0">
                    <i data-lucide="clipboard-check" class="w-6 h-6"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">ASPIRASI & USULAN</span>
                        <span class="text-[10px] font-bold text-emerald-700 bg-emerald-100 px-1.5 py-0.2 rounded">+20%</span>
                    </div>
                    <p class="text-xl sm:text-2xl font-extrabold text-slate-900 mt-0.5 font-heading">{{ number_format($totalIdeas ?: 1840) }} Usulan</p>
                    <p class="text-[11px] text-slate-500">Divalidasi tim Pemkab Gresik</p>
                </div>
            </div>

        </div>

        <!-- ========================================================================= -->
        <!-- 3. SPOTLIGHT SECTION: FOKUS PENGEMBANGAN                                   -->
        <!-- ========================================================================= -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-xs space-y-6">
            
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100">
                <div>
                    <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md bg-teal-100 text-teal-800 text-[11px] font-bold mb-1.5">
                        <i data-lucide="tag" class="w-3 h-3 text-teal-700"></i>
                        <span>FOKUS PENGEMBANGAN DAERAH</span>
                    </div>
                    <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 font-heading">
                        Revitalisasi Gedung Pertemuan Desa Sukomulyo (500 m²)
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5 flex items-center gap-1.5">
                        <i data-lucide="map-pin" class="w-3.5 h-3.5 text-slate-400"></i>
                        <span>Kecamatan Manyar, Kabupaten Gresik &bull; Tanah & Bangunan &bull; Potensi: Sentra UMKM & Balai Kreatif</span>
                    </p>
                </div>

                <a href="{{ route('assets.show', 'gedung-serbaguna-desa-sukomulyo') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#008080] hover:bg-[#006666] text-white text-xs font-bold shadow-xs transition-colors shrink-0">
                    <span>Lihat Semua Usulan & Progress</span>
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>

            <!-- 3 Sub-columns Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-center">
                
                <!-- Sub-col 1: Photo & Description (4 cols) -->
                <div class="lg:col-span-4 space-y-3">
                    <div class="relative rounded-xl overflow-hidden border border-slate-200">
                        <img 
                            src="https://images.unsplash.com/photo-1513694203232-719a280e022f?auto=format&fit=crop&w=600&q=80" 
                            alt="Gedung Pertemuan Desa Sukomulyo" 
                            class="w-full h-44 object-cover"
                        />
                        <div class="absolute bottom-2 left-2 bg-slate-900/80 backdrop-blur-xs text-white text-[10px] font-bold px-2 py-0.5 rounded">
                            Gedung Pertemuan Desa
                        </div>
                    </div>

                    <div>
                        <h4 class="text-xs font-bold text-slate-900 mb-1">Deskripsi Aset</h4>
                        <p class="text-[11.5px] text-slate-500 leading-relaxed">
                            Gedung pertemuan milik desa yang sebelumnya kurang produktif. Berpotensi dikembangkan sebagai pusat UMKM, pelatihan kerja, dan kegiatan ekonomi masyarakat.
                        </p>
                    </div>

                    <a href="{{ route('assets.show', 'gedung-serbaguna-desa-sukomulyo') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-bold transition-colors">
                        <span>Lihat Detail Lengkap</span>
                        <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                    </a>
                </div>

                <!-- Sub-col 2: Feasibility Score Donut & Breakdown (4 cols) -->
                <div class="lg:col-span-4 bg-slate-50/70 p-5 rounded-xl border border-slate-200/80 space-y-4">
                    <span class="text-[10.5px] font-bold text-slate-500 uppercase tracking-wider block">SKOR KELAYAKAN AKTIVASI</span>
                    
                    <!-- Donut Ring + Verdict -->
                    <div class="flex items-center gap-4">
                        <div class="relative w-20 h-20 flex items-center justify-center shrink-0">
                            <svg viewBox="0 0 36 36" class="w-20 h-20 transform -rotate-90">
                                <path class="text-slate-200" stroke-width="3.5" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                                <path class="text-teal-600" stroke-dasharray="89, 100" stroke-width="3.5" stroke-linecap="round" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                            </svg>
                            <div class="absolute text-center">
                                <span class="text-xl font-extrabold text-slate-900 block leading-none">89</span>
                                <span class="text-[9px] text-slate-400 font-bold">/100</span>
                            </div>
                        </div>

                        <div>
                            <div class="flex items-center gap-1.5 text-emerald-700 font-bold text-sm">
                                <i data-lucide="check-circle" class="w-4 h-4"></i>
                                <span>Sangat Layak</span>
                            </div>
                            <p class="text-[11px] text-slate-500 mt-0.5">Potensi nilai ekonomi tinggi</p>
                        </div>
                    </div>

                    <!-- Progress Bar Rows -->
                    <div class="space-y-2.5 pt-2 border-t border-slate-200/80 text-xs">
                        <div>
                            <div class="flex justify-between items-center text-[11px] mb-1">
                                <span class="flex items-center gap-1.5 text-slate-600 font-medium">
                                    <i data-lucide="map-pin" class="w-3.5 h-3.5 text-sky-600"></i>
                                    Aksesibilitas & Lokasi
                                </span>
                                <span class="font-bold text-slate-800">92<span class="text-slate-400 text-[9px]">/100</span></span>
                            </div>
                            <div class="w-full bg-slate-200 h-1.5 rounded-full overflow-hidden">
                                <div class="bg-sky-600 h-full rounded-full" style="width: 92%"></div>
                            </div>
                        </div>

                        <div>
                            <div class="flex justify-between items-center text-[11px] mb-1">
                                <span class="flex items-center gap-1.5 text-slate-600 font-medium">
                                    <i data-lucide="users" class="w-3.5 h-3.5 text-teal-600"></i>
                                    Kebutuhan Wilayah Lokal
                                </span>
                                <span class="font-bold text-slate-800">88<span class="text-slate-400 text-[9px]">/100</span></span>
                            </div>
                            <div class="w-full bg-slate-200 h-1.5 rounded-full overflow-hidden">
                                <div class="bg-teal-600 h-full rounded-full" style="width: 88%"></div>
                            </div>
                        </div>

                        <div>
                            <div class="flex justify-between items-center text-[11px] mb-1">
                                <span class="flex items-center gap-1.5 text-slate-600 font-medium">
                                    <i data-lucide="coins" class="w-3.5 h-3.5 text-amber-500"></i>
                                    Potensi Pendapatan BUMDes
                                </span>
                                <span class="font-bold text-slate-800">87<span class="text-slate-400 text-[9px]">/100</span></span>
                            </div>
                            <div class="w-full bg-slate-200 h-1.5 rounded-full overflow-hidden">
                                <div class="bg-amber-500 h-full rounded-full" style="width: 87%"></div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Sub-col 3: AI Recommendations & Action (4 cols) -->
                <div class="lg:col-span-4 bg-sky-50/40 p-5 rounded-xl border border-sky-100 space-y-4">
                    
                    <!-- AI Process Status -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-lg bg-teal-700 text-white flex items-center justify-center font-bold">
                                <i data-lucide="cpu" class="w-4 h-4"></i>
                            </div>
                            <span class="font-bold text-slate-900 text-xs">Proses Analisis AI</span>
                        </div>
                        <span class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 font-bold text-[10px]">
                            Terverifikasi
                        </span>
                    </div>

                    <!-- Recommendations List -->
                    <div class="space-y-2">
                        <h4 class="text-xs font-bold text-slate-900">Rekomendasi Aktivasi Aset</h4>
                        
                        <ul class="space-y-2 text-[11.5px] text-slate-600">
                            <li class="flex items-start gap-2">
                                <i data-lucide="check-circle-2" class="w-4 h-4 text-teal-600 shrink-0 mt-0.5"></i>
                                <span>Cocok untuk pengembangan sentra UMKM desa</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <i data-lucide="check-circle-2" class="w-4 h-4 text-teal-600 shrink-0 mt-0.5"></i>
                                <span>Potensi pendapatan tahunan ± Rp 320 juta</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <i data-lucide="check-circle-2" class="w-4 h-4 text-teal-600 shrink-0 mt-0.5"></i>
                                <span>Disarankan kolaborasi kemitraan BUMDes & swasta</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Realtime AI Consultation CTA Button -->
                    <button 
                        type="button" 
                        x-data 
                        @click="$dispatch('open-ai-chatbot')" 
                        class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-[#008080] hover:bg-[#006666] text-white font-bold text-xs shadow-xs transition-colors cursor-pointer"
                    >
                        <i data-lucide="sparkles" class="w-4 h-4 text-teal-200"></i>
                        <span>Konsultasi AI Realtime & Rekomendasi</span>
                    </button>

                </div>

            </div>

        </div>

        <!-- ========================================================================= -->
        <!-- 4. 5-STEP WORKFLOW: ALUR TATA KELOLA & AKTIVASI                           -->
        <!-- ========================================================================= -->
        <div class="space-y-4">
            
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-teal-800 text-white flex items-center justify-center font-bold shadow-xs shrink-0">
                    <i data-lucide="layers" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="font-heading font-extrabold text-base text-slate-900">
                        Alur Tata Kelola & Aktivasi Aset Desa
                    </h3>
                    <p class="text-xs text-slate-500">
                        Memudahkan koordinasi dari pendataan hingga realisasi pengembangan aset desa.
                    </p>
                </div>
            </div>

            <!-- 5 Step Cards Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3.5">
                
                <!-- Step 1: Pendataan -->
                <div class="bg-white rounded-xl border border-slate-200/80 p-4 shadow-xs flex flex-col justify-between space-y-3 hover:shadow-md transition-shadow">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <span class="w-6 h-6 rounded-full bg-sky-600 text-white font-bold text-xs flex items-center justify-center">1</span>
                            <i data-lucide="users" class="w-5 h-5 text-sky-600"></i>
                        </div>
                        <h4 class="font-bold text-slate-900 text-xs">Pendataan & Pelaporan Spasial</h4>
                        <p class="text-[11px] text-slate-500 mt-1 leading-relaxed">
                            Input data aset desa secara terstandar dan terintegrasi.
                        </p>
                    </div>
                    <a href="{{ route('reports.create') }}" class="text-sky-600 hover:text-sky-800 font-bold text-[11px] inline-flex items-center gap-1 pt-2 border-t border-slate-100">
                        <span>Mulai Pendataan</span>
                        <i data-lucide="arrow-right" class="w-3 h-3"></i>
                    </a>
                </div>

                <!-- Step 2: Verifikasi AI -->
                <div class="bg-white rounded-xl border border-slate-200/80 p-4 shadow-xs flex flex-col justify-between space-y-3 hover:shadow-md transition-shadow">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <span class="w-6 h-6 rounded-full bg-emerald-600 text-white font-bold text-xs flex items-center justify-center">2</span>
                            <i data-lucide="cpu" class="w-5 h-5 text-emerald-600"></i>
                        </div>
                        <h4 class="font-bold text-slate-900 text-xs">Verifikasi Lapangan & Analisis AI</h4>
                        <p class="text-[11px] text-slate-500 mt-1 leading-relaxed">
                            Validasi data dan analisis kelayakan aset secara realtime.
                        </p>
                    </div>
                    <a href="{{ route('dashboard.verification') }}" class="text-emerald-700 hover:text-emerald-900 font-bold text-[11px] inline-flex items-center gap-1 pt-2 border-t border-slate-100">
                        <span>Lihat Proses</span>
                        <i data-lucide="arrow-right" class="w-3 h-3"></i>
                    </a>
                </div>

                <!-- Step 3: Kajian Kelayakan -->
                <div class="bg-white rounded-xl border border-slate-200/80 p-4 shadow-xs flex flex-col justify-between space-y-3 hover:shadow-md transition-shadow">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <span class="w-6 h-6 rounded-full bg-amber-500 text-white font-bold text-xs flex items-center justify-center">3</span>
                            <i data-lucide="trending-up" class="w-5 h-5 text-amber-500"></i>
                        </div>
                        <h4 class="font-bold text-slate-900 text-xs">Kajian Kelayakan & Monitoring</h4>
                        <p class="text-[11px] text-slate-500 mt-1 leading-relaxed">
                            Penilaian potensi ekonomi, sosial, dan lingkungan.
                        </p>
                    </div>
                    <a href="{{ route('map') }}" class="text-amber-600 hover:text-amber-800 font-bold text-[11px] inline-flex items-center gap-1 pt-2 border-t border-slate-100">
                        <span>Lihat Analitik</span>
                        <i data-lucide="arrow-right" class="w-3 h-3"></i>
                    </a>
                </div>

                <!-- Step 4: BUMDes & Kemitraan -->
                <div class="bg-white rounded-xl border border-slate-200/80 p-4 shadow-xs flex flex-col justify-between space-y-3 hover:shadow-md transition-shadow">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <span class="w-6 h-6 rounded-full bg-purple-600 text-white font-bold text-xs flex items-center justify-center">4</span>
                            <i data-lucide="handshake" class="w-5 h-5 text-purple-600"></i>
                        </div>
                        <h4 class="font-bold text-slate-900 text-xs">Pemanfaatan BUMDes & Kemitraan</h4>
                        <p class="text-[11px] text-slate-500 mt-1 leading-relaxed">
                            Kolaborasi untuk realisasi pengembangan aset.
                        </p>
                    </div>
                    <a href="{{ route('explore') }}" class="text-purple-600 hover:text-purple-800 font-bold text-[11px] inline-flex items-center gap-1 pt-2 border-t border-slate-100">
                        <span>Lihat Peluang</span>
                        <i data-lucide="arrow-right" class="w-3 h-3"></i>
                    </a>
                </div>

                <!-- Step 5: Pelaporan & Evaluasi -->
                <div class="bg-white rounded-xl border border-slate-200/80 p-4 shadow-xs flex flex-col justify-between space-y-3 hover:shadow-md transition-shadow">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <span class="w-6 h-6 rounded-full bg-cyan-600 text-white font-bold text-xs flex items-center justify-center">5</span>
                            <i data-lucide="file-check" class="w-5 h-5 text-cyan-600"></i>
                        </div>
                        <h4 class="font-bold text-slate-900 text-xs">Pelaporan & Evaluasi</h4>
                        <p class="text-[11px] text-slate-500 mt-1 leading-relaxed">
                            Pantau progres dan rekapitulasi desa secara transparan.
                        </p>
                    </div>
                    <a href="{{ route('reports.villages') }}" class="text-cyan-700 hover:text-cyan-900 font-bold text-[11px] inline-flex items-center gap-1 pt-2 border-t border-slate-100">
                        <span>Lihat Laporan</span>
                        <i data-lucide="arrow-right" class="w-3 h-3"></i>
                    </a>
                </div>

            </div>

        </div>

    </div>
</div>
@endsection
