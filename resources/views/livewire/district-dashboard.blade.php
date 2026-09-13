<div class="space-y-4">

    <!-- TOP KPI METRIC STRIP (EXACT REFERENCE DESIGN) -->
    <div class="bg-white rounded-2xl p-4 border border-slate-200/90 shadow-xs">
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 divide-y md:divide-y-0 md:divide-x divide-slate-100 items-center">
            
            <!-- Group 1: ASSET HEALTH -->
            <div class="pr-0 md:pr-4 space-y-2">
                <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-800">ASSET HEALTH (MANYAR)</span>
                <div class="space-y-1.5 text-xs">
                    <div class="flex items-center justify-between gap-3">
                        <span class="text-slate-500 text-[11px] w-20 shrink-0">Compliance</span>
                        <div class="flex-1 bg-slate-100 h-2.5 rounded-full overflow-hidden">
                            <div class="bg-blue-600 h-full rounded-full" style="width: 72%"></div>
                        </div>
                        <span class="text-blue-600 font-bold text-xs w-8 text-right">72%</span>
                    </div>
                    <div class="flex items-center justify-between gap-3">
                        <span class="text-slate-500 text-[11px] w-20 shrink-0">Operational</span>
                        <div class="flex-1 bg-slate-100 h-2.5 rounded-full overflow-hidden">
                            <div class="bg-[#ff5722] h-full rounded-full" style="width: 28%"></div>
                        </div>
                        <span class="text-[#ff5722] font-bold text-xs w-8 text-right">28%</span>
                    </div>
                </div>
            </div>

            <!-- Group 2: TASKS -->
            <div class="px-0 md:px-4 space-y-2 pt-3 md:pt-0">
                <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-800">TASKS (23 DESA)</span>
                <div class="grid grid-cols-3 gap-2 text-center">
                    <div>
                        <span class="text-[10px] text-slate-500 block mb-1">Inspections</span>
                        <span class="inline-flex items-center justify-center w-full py-1 rounded-lg bg-[#00c9a7] text-white font-bold text-xs">28</span>
                    </div>
                    <div>
                        <span class="text-[10px] text-slate-500 block mb-1">Jobs</span>
                        <span class="inline-flex items-center justify-center w-full py-1 rounded-lg bg-[#0284c7] text-white font-bold text-xs">96</span>
                    </div>
                    <div>
                        <span class="text-[10px] text-slate-500 block mb-1">Defects</span>
                        <span class="inline-flex items-center justify-center w-full py-1 rounded-lg bg-[#ff5722] text-white font-bold text-xs">14</span>
                    </div>
                </div>
            </div>

            <!-- Group 3: ASSET VALUE -->
            <div class="px-0 md:px-4 space-y-2 pt-3 md:pt-0">
                <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-800">ASSET VALUE</span>
                <div class="grid grid-cols-3 gap-2 text-center text-xs">
                    <div>
                        <span class="text-[10px] text-slate-500 block mb-0.5">Purchase</span>
                        <span class="inline-block px-2 py-1 bg-slate-100 rounded text-slate-800 font-bold text-[11px]">Rp 420M</span>
                    </div>
                    <div>
                        <span class="text-[10px] text-slate-500 block mb-0.5">Insurance</span>
                        <span class="inline-block px-2 py-1 bg-slate-100 rounded text-slate-800 font-bold text-[11px]">Rp 680M</span>
                    </div>
                    <div>
                        <span class="text-[10px] text-slate-500 block mb-0.5">Difference</span>
                        <span class="inline-block px-2 py-1 bg-slate-100 rounded text-emerald-700 font-bold text-[11px]">+61.9%</span>
                    </div>
                </div>
            </div>

            <!-- Group 4: TOTAL ASSETS -->
            <div class="pl-0 md:pl-4 space-y-2 pt-3 md:pt-0">
                <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-800">TOTAL ASSETS</span>
                <div class="flex items-center gap-1.5 justify-between">
                    <div class="text-center">
                        <span class="text-[10px] text-slate-500 block mb-0.5">Count</span>
                        <span class="inline-block px-2 py-1 border-2 border-cyan-500 text-cyan-600 font-extrabold text-xs rounded-md">{{ $totalAssets }}</span>
                    </div>
                    <div class="text-center">
                        <span class="text-[10px] text-slate-500 block mb-0.5">Active</span>
                        <span class="inline-block px-2.5 py-1 bg-teal-600 text-white font-extrabold text-xs rounded-md">84</span>
                    </div>
                    <div class="text-center">
                        <span class="text-[10px] text-slate-500 block mb-0.5">Inactive</span>
                        <span class="inline-block px-2.5 py-1 bg-cyan-500 text-white font-extrabold text-xs rounded-md">{{ $unusedAssets }}</span>
                    </div>
                    <div class="text-center">
                        <span class="text-[10px] text-slate-500 block mb-0.5">Disposed</span>
                        <span class="inline-block px-2.5 py-1 bg-slate-400 text-white font-extrabold text-xs rounded-md">32</span>
                    </div>
                    <a href="{{ route('map') }}" class="w-7 h-7 rounded-full border border-slate-300 hover:border-teal-600 hover:text-teal-600 flex items-center justify-center text-slate-400 transition-colors shrink-0 self-end mb-0.5" title="Peta Wilayah">
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
                    id="referenceDistrictMap" 
                    wire:ignore 
                    class="h-[380px] w-full z-0 bg-[#e5e9ec]"
                    x-data="{
                        init() {
                            const map = L.map('referenceDistrictMap', {
                                zoomControl: true,
                                attributionControl: false
                            }).setView([-7.1350, 112.5950], 13);

                            // Google Earth Satellite Hybrid + Standard OSM layer
                            const googleEarth = L.tileLayer('https://mt1.google.com/vt/lyrs=y&x={x}&y={y}&z={z}', {
                                maxZoom: 20,
                                subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
                            }).addTo(map);

                            const streetMap = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                maxZoom: 19
                            });

                            L.control.layers({
                                'Citra Satelit': googleEarth,
                                'Peta Standar': streetMap
                            }, null, { position: 'topright' }).addTo(map);

                            // District Boundary
                            const manyarPoly = [
                                [-7.1150, 112.5600],
                                [-7.1050, 112.6100],
                                [-7.1450, 112.6300],
                                [-7.1650, 112.5800],
                                [-7.1350, 112.5500]
                            ];
                            L.polygon(manyarPoly, {
                                color: '#008080',
                                weight: 2,
                                fillColor: '#008080',
                                fillOpacity: 0.05,
                                dashArray: '4, 4'
                            }).addTo(map);

                            const points = [
                                { lat: -7.1350, lng: 112.6020, color: '#00c9a7' },
                                { lat: -7.1280, lng: 112.5850, color: '#00c9a7' },
                                { lat: -7.1430, lng: 112.5950, color: '#00c9a7' },
                                { lat: -7.1200, lng: 112.6100, color: '#ff5722' },
                                { lat: -7.1500, lng: 112.5700, color: '#00c9a7' },
                                { lat: -7.1380, lng: 112.6200, color: '#00c9a7' },
                            ];

                            points.forEach(p => {
                                const dotIcon = L.divIcon({
                                    className: 'custom-dot',
                                    html: `<div style='background-color: ${p.color}; width: 10px; height: 10px; border-radius: 50%; border: 2px solid white; box-shadow: 0 1px 4px rgba(0,0,0,0.3);'></div>`,
                                    iconSize: [10, 10],
                                    iconAnchor: [5, 5]
                                });
                                L.marker([p.lat, p.lng], { icon: dotIcon }).addTo(map);
                            });
                        }
                    }"
                ></div>

                <!-- FLOATING OVERLAY CARD: TOP OPPORTUNITY -->
                <div class="absolute top-4 right-4 z-10 w-80 bg-white rounded-xl border border-[#00c9a7] shadow-xl p-3 text-xs space-y-2 select-none" x-data="{ open: true }" x-show="open">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center gap-2">
                            <span class="px-1.5 py-0.5 bg-[#00c9a7] text-white font-extrabold text-[10px] rounded uppercase">LIVE</span>
                            <span class="px-1.5 py-0.5 bg-cyan-500 text-white font-bold text-[10px] rounded uppercase">INS</span>
                            <span class="px-1.5 py-0.5 bg-slate-100 text-slate-700 font-bold text-[10px] rounded uppercase">IN PROGRESS</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <div class="w-5 h-5 rounded-full bg-slate-200 overflow-hidden">
                                <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=100&q=80" alt="Avatar" class="w-full h-full object-cover"/>
                            </div>
                            <button @click="open = false" class="text-slate-400 hover:text-slate-600">
                                <i data-lucide="x" class="w-3.5 h-3.5"></i>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <img src="https://images.unsplash.com/photo-1500382017468-9049fed747ef?auto=format&fit=crop&w=300&q=80" alt="Asset" class="w-14 h-12 rounded-lg object-cover shrink-0 border border-slate-100"/>
                        <div class="overflow-hidden">
                            <h4 class="font-bold text-slate-900 truncate">Lahan Kas Desa Peganden</h4>
                            <p class="text-[11px] text-slate-500 truncate">Kecamatan Manyar (Skor 92/100)</p>
                        </div>
                    </div>

                    <div class="flex items-center justify-between text-[10px] text-slate-400 pt-1 border-t border-slate-100">
                        <span>ID: 2104</span>
                        <a href="{{ route('assets.show', 'lahan-kas-desa-peganden-manyar') }}" class="font-bold text-teal-600 hover:underline">Detail Aset &rarr;</a>
                    </div>
                </div>

            </div>

            <!-- BOTTOM TABLE: UPCOMING INSPECTIONS & VERIFIKASI DESA -->
            <div class="bg-white rounded-2xl border border-slate-200/90 overflow-hidden shadow-xs">
                
                <div class="p-4 border-b border-slate-100">
                    <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-800">UPCOMING INSPECTIONS & DESA AUDITS</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-[#80cbc4]/70 text-slate-800 font-extrabold text-[11px] uppercase tracking-wider">
                            <tr>
                                <th class="py-2.5 px-4 font-extrabold">NAME</th>
                                <th class="py-2.5 px-4 font-extrabold">LAST INSPECTION</th>
                                <th class="py-2.5 px-4 font-extrabold">ASSET TYPE</th>
                                <th class="py-2.5 px-4 font-extrabold">NEXT INSPECTION</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-3 px-4 font-semibold text-slate-900">Desa Sukomulyo (Gedung Serbaguna)</td>
                                <td class="py-3 px-4 text-slate-500">-</td>
                                <td class="py-3 px-4 text-slate-600 font-medium">Bangunan & Gedung</td>
                                <td class="py-3 px-4 text-slate-900 font-bold">15 / 03 / 2026</td>
                            </tr>
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-3 px-4 font-semibold text-slate-900">Desa Peganden (Lahan Kas Manyar)</td>
                                <td class="py-3 px-4 text-slate-500">15 / 04 / 2025</td>
                                <td class="py-3 px-4 text-slate-600 font-medium">Tanah Kas Desa</td>
                                <td class="py-3 px-4 text-slate-900 font-bold">15 / 04 / 2026</td>
                            </tr>
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-3 px-4 font-semibold text-slate-900">Desa Suci (Ekowisata Sendang)</td>
                                <td class="py-3 px-4 text-slate-500">01 / 10 / 2025</td>
                                <td class="py-3 px-4 text-slate-600 font-medium">Wisata Desa</td>
                                <td class="py-3 px-4 text-slate-900 font-bold">01 / 10 / 2026</td>
                            </tr>
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-3 px-4 font-semibold text-slate-900">Desa Yosowilangun (Balai RW)</td>
                                <td class="py-3 px-4 text-slate-500">-</td>
                                <td class="py-3 px-4 text-slate-600 font-medium">Ruang Publik</td>
                                <td class="py-3 px-4 text-slate-900 font-bold">23 / 10 / 2026</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>

        </div>

        <!-- RIGHT ANALYTICS SIDEBAR (4 COLS: CATEGORIES + 2 DONUT CHARTS) -->
        <div class="xl:col-span-4 space-y-4">
            
            <!-- 1. ASSET CATEGORIES HORIZONTAL BARS -->
            <div class="bg-white rounded-2xl p-4 border border-slate-200/90 shadow-xs space-y-3">
                <h3 class="text-[11px] font-extrabold uppercase tracking-wider text-slate-800">ASSET CATEGORIES (MANYAR)</h3>
                
                <div class="space-y-2 text-xs">
                    <div>
                        <div class="flex justify-between text-[11px] text-slate-600 mb-0.5">
                            <span class="font-medium">Bangunan & Gedung Pertemuan</span>
                        </div>
                        <div class="w-full bg-slate-100 h-3 rounded">
                            <div class="bg-[#80cbc4] h-full rounded" style="width: 78%"></div>
                        </div>
                        <span class="text-[10px] text-slate-500">42</span>
                    </div>

                    <div>
                        <div class="flex justify-between text-[11px] text-slate-600 mb-0.5">
                            <span class="font-medium">Tanah Kas Desa & Pekarangan</span>
                        </div>
                        <div class="w-full bg-slate-100 h-3 rounded">
                            <div class="bg-[#80cbc4] h-full rounded" style="width: 60%"></div>
                        </div>
                        <span class="text-[10px] text-slate-500">28</span>
                    </div>

                    <div>
                        <div class="flex justify-between text-[11px] text-slate-600 mb-0.5">
                            <span class="font-medium">Pasar Desa & Kios UMKM</span>
                        </div>
                        <div class="w-full bg-slate-100 h-3 rounded">
                            <div class="bg-[#80cbc4] h-full rounded" style="width: 45%"></div>
                        </div>
                        <span class="text-[10px] text-slate-500">19</span>
                    </div>

                    <div>
                        <div class="flex justify-between text-[11px] text-slate-600 mb-0.5">
                            <span class="font-medium">Sarana Olahraga & Pemuda</span>
                        </div>
                        <div class="w-full bg-slate-100 h-3 rounded">
                            <div class="bg-[#80cbc4] h-full rounded" style="width: 35%"></div>
                        </div>
                        <span class="text-[10px] text-slate-500">14</span>
                    </div>

                    <div>
                        <div class="flex justify-between text-[11px] text-slate-600 mb-0.5">
                            <span class="font-medium">Wisata Desa & RTH</span>
                        </div>
                        <div class="w-full bg-slate-100 h-3 rounded">
                            <div class="bg-[#80cbc4] h-full rounded" style="width: 25%"></div>
                        </div>
                        <span class="text-[10px] text-slate-500">11</span>
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
                                series: [58, 42],
                                chart: { type: 'donut', height: 160, sparkline: { enabled: true } },
                                colors: ['#00c9a7', '#008080'],
                                labels: ['LAND & PROPERTY', 'INFRASTRUCTURE & AMENITIES'],
                                plotOptions: { pie: { donut: { size: '68%', labels: { show: false } } } },
                                stroke: { width: 0 }
                            };
                            new ApexCharts(this.$refs.donutDistrict1, options).render();
                        }
                    }"
                >
                    <div x-ref="donutDistrict1" class="w-32 h-32 shrink-0"></div>
                    
                    <div class="space-y-2 text-[10px] font-bold text-slate-700">
                        <div class="flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-[#00c9a7] shrink-0"></span>
                            <span>LAND & PROPERTY</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-[#008080] shrink-0"></span>
                            <span>INFRASTRUCTURE</span>
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
                                series: [52, 18, 15, 15],
                                chart: { type: 'donut', height: 160, sparkline: { enabled: true } },
                                colors: ['#008080', '#ff5722', '#00c9a7', '#60a5fa'],
                                labels: ['GOOD', 'POOR', 'NEW', 'FAIR'],
                                plotOptions: { pie: { donut: { size: '68%', labels: { show: false } } } },
                                stroke: { width: 0 }
                            };
                            new ApexCharts(this.$refs.donutDistrict2, options).render();
                        }
                    }"
                >
                    <div x-ref="donutDistrict2" class="w-32 h-32 shrink-0"></div>
                    
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
