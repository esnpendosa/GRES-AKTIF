@extends('layouts.app')

@section('content')
<div class="space-y-16 sm:space-y-20">

    <!-- HERO SECTION (CLEAN INSTITUTIONAL LIGHT THEME) -->
    <section class="bg-white border-b border-slate-200 py-12 sm:py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                
                <!-- Left: Headline & Information (7 cols) -->
                <div class="lg:col-span-7 space-y-5">
                    
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-md bg-slate-100 border border-slate-200 text-slate-700 text-xs font-semibold">
                        <span class="w-2 h-2 rounded-full bg-teal-600"></span>
                        <span>Sistem Manajemen Aset Daerah Terpadu &bull; Kabupaten Gresik</span>
                    </div>

                    <div class="space-y-3">
                        <h1 class="font-heading font-extrabold text-3xl sm:text-4xl lg:text-5xl text-slate-900 tracking-tight leading-tight">
                            Optimalisasi & Aktivasi Aset Desa Menjadi Penggerak Ekonomi.
                        </h1>
                        <p class="text-sm sm:text-base text-slate-600 leading-relaxed max-w-2xl">
                            Platform kolaboratif Pemerintah Kabupaten Gresik untuk menginventarisasi aset desa yang belum termanfaatkan, menganalisis kelayakan fungsi ekonomi baru, dan mendukung kemandirian BUMDes secara terukur dan transparan.
                        </p>
                    </div>

                    <!-- Core Principle Note -->
                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-700 font-medium leading-relaxed max-w-xl">
                        <span class="font-bold text-teal-800">Prinsip Kerja Sama:</span> "AI menemukan potensi, masyarakat menentukan kebutuhan, pemerintah mengaktifkan aset."
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row items-center gap-3 pt-2">
                        <a href="{{ route('reports.create') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 rounded-lg bg-[#008080] hover:bg-[#006666] text-white font-semibold text-xs shadow-xs transition-colors">
                            <i data-lucide="plus-circle" class="w-4 h-4"></i>
                            <span>Laporkan Aset Desa</span>
                        </a>

                        <a href="{{ route('map') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 rounded-lg bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 font-semibold text-xs transition-colors">
                            <i data-lucide="map" class="w-4 h-4 text-teal-600"></i>
                            <span>Buka Peta Spasial GIS</span>
                        </a>
                    </div>

                </div>

                <!-- Right: Clean GIS Map & Asset Card (5 cols) -->
                <div class="lg:col-span-5">
                    <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm space-y-3">
                        <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-teal-600"></span>
                                <span class="font-bold text-xs text-slate-900">Radar Spasial Aset Gresik</span>
                            </div>
                            <span class="text-[11px] text-slate-400 font-medium">16 Kecamatan</span>
                        </div>

                        <!-- Mini Map Preview -->
                        <div 
                            id="landingMap" 
                            class="h-60 w-full rounded-xl overflow-hidden border border-slate-200 z-0 bg-slate-100"
                            x-data="{
                                init() {
                                    const map = L.map('landingMap', { zoomControl: false, attributionControl: false }).setView([-7.1380, 112.6000], 14);
                                    L.tileLayer('https://mt1.google.com/vt/lyrs=y&x={x}&y={y}&z={z}', { 
                                        maxZoom: 20,
                                        subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
                                    }).addTo(map);

                                    const pins = [
                                        { lat: -7.1350, lng: 112.6020, name: 'Gedung Sukomulyo', score: 89, color: '#008080' },
                                        { lat: -7.1280, lng: 112.5850, name: 'Lahan Peganden', score: 92, color: '#0284c7' },
                                        { lat: -7.1430, lng: 112.5950, name: 'Kios Suci', score: 85, color: '#00c9a7' },
                                        { lat: -7.1500, lng: 112.6200, name: 'Lahan Manyar', score: 88, color: '#ff5722' }
                                    ];

                                    pins.forEach(p => {
                                        const dot = L.divIcon({
                                            className: 'landing-dot',
                                            html: `<div style='background-color: ${p.color}; width: 22px; height: 22px; border-radius: 50%; border: 2px solid white; display: flex; align-items: center; justify-content: center; color: white; font-size: 9px; font-weight: 800; box-shadow: 0 1px 4px rgba(0,0,0,0.3);'>${p.score}</div>`,
                                            iconSize: [22, 22],
                                            iconAnchor: [11, 11]
                                        });
                                        L.marker([p.lat, p.lng], { icon: dot }).addTo(map);
                                    });
                                }
                            }"
                        ></div>

                        <!-- Pilot Card Summary -->
                        <div class="p-3 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-between text-xs">
                            <div class="flex items-center gap-2.5 overflow-hidden">
                                <div class="w-8 h-8 rounded-lg bg-teal-100 text-teal-800 font-extrabold flex items-center justify-center shrink-0">
                                    89
                                </div>
                                <div class="truncate">
                                    <p class="font-bold text-slate-900 truncate">Gedung Serbaguna Desa Sukomulyo</p>
                                    <p class="text-[10px] text-slate-500">Rekomendasi: Sentra UMKM & Kuliner (94%)</p>
                                </div>
                            </div>

                            <a href="{{ route('assets.show', 'gedung-serbaguna-desa-sukomulyo') }}" class="font-bold text-teal-700 hover:text-teal-900 shrink-0 text-xs">
                                Buka &rarr;
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- 4 MACRO METRICS STRIP -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            
            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">ASET TERINVENTARISASI</span>
                <span class="font-heading font-extrabold text-2xl text-slate-900">412 Aset</span>
                <p class="text-[11px] text-slate-500 mt-0.5">Bangunan, lahan, dan pasar desa</p>
            </div>

            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">CAKUPAN WILAYAH</span>
                <span class="font-heading font-extrabold text-2xl text-teal-700">16 Kecamatan</span>
                <p class="text-[11px] text-slate-500 mt-0.5">356 desa dan kelurahan binaan</p>
            </div>

            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">ESTIMASI NILAI EKONOMI</span>
                <span class="font-heading font-extrabold text-2xl text-slate-900">Rp 1,42 T</span>
                <p class="text-[11px] text-slate-500 mt-0.5">Potensi aset produktif daerah</p>
            </div>

            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">PARTISIPASI WARGA</span>
                <span class="font-heading font-extrabold text-2xl text-cyan-700">1.840 Usulan</span>
                <p class="text-[11px] text-slate-500 mt-0.5">Gagasan & validasi crowdsourcing</p>
            </div>

        </div>
    </section>

    <!-- FLAGSHIP PILOT SHOWCASE (GEDUNG SUKOMULYO) -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs">
            
            <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded bg-teal-50 text-teal-800 border border-teal-200">
                        Proyek Percontohan Prioritas
                    </span>
                    <h2 class="font-heading font-bold text-xl text-slate-900 mt-2">Revitalisasi Gedung Pertemuan Desa Sukomulyo (500 m²)</h2>
                    <p class="text-xs text-slate-500">Kecamatan Manyar, Kabupaten Gresik &bull; Transformasi dari aset pasif menjadi Sentra UMKM BUMDes</p>
                </div>

                <a href="{{ route('assets.show', 'gedung-serbaguna-desa-sukomulyo') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-[#008080] hover:bg-[#006666] text-white text-xs font-semibold shadow-xs shrink-0">
                    <span>Lihat Rencana Usaha & Gagasan</span>
                    <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 divide-y lg:divide-y-0 lg:divide-x divide-slate-100 text-xs">
                
                <!-- Col 1: Photo & Condition -->
                <div class="p-6 space-y-3">
                    <img src="https://images.unsplash.com/photo-1513694203232-719a280e022f?auto=format&fit=crop&w=600&q=80" alt="Gedung Sukomulyo" class="w-full h-44 rounded-xl object-cover border border-slate-200"/>
                    <div class="space-y-1">
                        <span class="font-bold text-slate-900 block">Kondisi Eksisting:</span>
                        <p class="text-slate-500 leading-relaxed">Gedung pertemuan milik desa yang sebelumnya hanya digunakan 2-3 kali setahun untuk acara hajatan. Terletak di tepi jalan utama dengan akses logistik memadai.</p>
                    </div>
                </div>

                <!-- Col 2: Feasibility & Analytics -->
                <div class="p-6 space-y-4">
                    <span class="font-bold text-slate-900 uppercase tracking-wider text-[10px] block">SKOR KELAYAKAN AKTIVASI</span>
                    
                    <div class="flex items-center gap-3">
                        <div class="text-3xl font-extrabold text-teal-800 font-heading">89<span class="text-xs text-slate-400">/100</span></div>
                        <div class="space-y-0.5">
                            <span class="font-bold text-slate-900 block">Sangat Layak Direvitalisasi</span>
                            <span class="text-[11px] text-slate-500">Peringkat 1 di Kecamatan Manyar</span>
                        </div>
                    </div>

                    <div class="space-y-2 pt-2 border-t border-slate-100">
                        <div class="flex justify-between text-slate-600">
                            <span>Aksesibilitas & Lokasi:</span>
                            <span class="font-bold text-slate-900">92/100</span>
                        </div>
                        <div class="flex justify-between text-slate-600">
                            <span>Kebutuhan UMKM Lokal:</span>
                            <span class="font-bold text-slate-900">95/100</span>
                        </div>
                        <div class="flex justify-between text-slate-600">
                            <span>Prospek Pendapatan BUMDes:</span>
                            <span class="font-bold text-slate-900">91/100</span>
                        </div>
                    </div>
                </div>

                <!-- Col 3: Status Musrenbang & Progress -->
                <div class="p-6 space-y-4 bg-slate-50/50">
                    <span class="font-bold text-slate-900 uppercase tracking-wider text-[10px] block">PROGRESS EKSEKUSI</span>
                    
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="font-semibold text-slate-700">Realisasi Fisik & Partisi Kios</span>
                            <span class="font-bold text-teal-700">45% Selesai</span>
                        </div>
                        <div class="w-full bg-slate-200 h-2 rounded-full overflow-hidden">
                            <div class="bg-teal-600 h-full rounded-full" style="width: 45%"></div>
                        </div>
                    </div>

                    <div class="space-y-2 pt-2 border-t border-slate-200 text-[11px] text-slate-600">
                        <div class="flex items-center gap-2">
                            <i data-lucide="check" class="w-3.5 h-3.5 text-emerald-600 shrink-0"></i>
                            <span>Musyawarah Desa Sukomulyo (Disetujui)</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i data-lucide="check" class="w-3.5 h-3.5 text-emerald-600 shrink-0"></i>
                            <span>213 Warga Memberikan Dukungan Gagasan</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i data-lucide="clock" class="w-3.5 h-3.5 text-blue-600 shrink-0"></i>
                            <span>Penyelesaian 15 Tenant UMKM (Target Nov 2026)</span>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </section>

    <!-- 4-STEP INSTITUTIONAL WORKFLOW -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-8 space-y-2">
            <h2 class="font-heading font-bold text-2xl text-slate-900">Alur Tata Kelola & Aktivasi Aset Desa</h2>
            <p class="text-xs text-slate-500">Mekanisme terstruktur dari pelaporan warga hingga realisasi program pembangunan desa.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-xs">
            
            <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-xs space-y-2">
                <span class="w-7 h-7 rounded-lg bg-slate-900 text-white flex items-center justify-center font-bold text-xs">1</span>
                <h3 class="font-bold text-slate-900 text-sm">Pendataan & Pelaporan Spasial</h3>
                <p class="text-slate-500 leading-relaxed">Warga dan aparatur desa mendata aset tidur melalui koordinat peta GIS dan foto kondisi lapangan.</p>
            </div>

            <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-xs space-y-2">
                <span class="w-7 h-7 rounded-lg bg-slate-900 text-white flex items-center justify-center font-bold text-xs">2</span>
                <h3 class="font-bold text-slate-900 text-sm">Verifikasi Lapangan Pemdes</h3>
                <p class="text-slate-500 leading-relaxed">Pemerintah Desa melakukan validasi status kepemilikan, batas lahan, dan kesiapan legalitas aset.</p>
            </div>

            <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-xs space-y-2">
                <span class="w-7 h-7 rounded-lg bg-slate-900 text-white flex items-center justify-center font-bold text-xs">3</span>
                <h3 class="font-bold text-slate-900 text-sm">Kajian Kelayakan & Musrenbang</h3>
                <p class="text-slate-500 leading-relaxed">Sistem menganalisis kesesuaian ekonomi mikro dan memfasilitasi konsensus aspirasi masyarakat desa.</p>
            </div>

            <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-xs space-y-2">
                <span class="w-7 h-7 rounded-lg bg-teal-700 text-white flex items-center justify-center font-bold text-xs">4</span>
                <h3 class="font-bold text-slate-900 text-sm">Pengelolaan BUMDes & Pemkab</h3>
                <p class="text-slate-500 leading-relaxed">Aset dialokasikan untuk dikelola BUMDes bersama UMKM lokal dengan dukungan pendanaan APBD/APBDes.</p>
            </div>

        </div>
    </section>

</div>
@endsection
