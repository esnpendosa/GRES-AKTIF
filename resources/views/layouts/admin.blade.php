<!DOCTYPE html>
<html lang="id" class="h-full bg-[#f8fafc]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'KENTONGAN AI' }} | Asset Intelligence Platform</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <!-- Leaflet & ApexCharts -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css" />
    <script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        theme: {
                            teal: '#008080',
                            tealLight: '#00c9a7',
                            tealDark: '#006666',
                            cyan: '#0284c7',
                            coral: '#ff5722',
                            bg: '#f8fafc',
                            card: '#ffffff',
                            sidebarUser: '#b2dfdb',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                        heading: ['Plus Jakarta Sans', 'Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Inter', sans-serif; }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        
        /* Leaflet tweaks */
        .leaflet-container { font-family: 'Inter', sans-serif; }
    </style>
    @livewireStyles
</head>
<body class="h-full flex overflow-hidden bg-[#f8fafc] text-slate-800 antialiased" x-data="{ sidebarCollapsed: false, mobileSidebarOpen: false }">

    <!-- LEFT SIDEBAR - CROWDASET AI STYLE (Narrow Dark Navy) -->
    <aside class="w-[72px] flex flex-col flex-shrink-0 bg-[#0d1b2a] z-30 select-none print:hidden">

        @php
            $homeRoute = route('home');
            if(auth()->check()) {
                if(auth()->user()->isVillageAdmin()) $homeRoute = route('dashboard.village');
                elseif(auth()->user()->isDistrictAdmin()) $homeRoute = route('dashboard.district');
                elseif(auth()->user()->isRegencyAdmin() || auth()->user()->isSuperAdmin()) $homeRoute = route('dashboard.regency');
            }
        @endphp

        <!-- Logo Mark (Cloud Icon ala CROWDASET AI) -->
        <div class="h-16 flex items-center justify-center border-b border-white/10">
            <a href="{{ $homeRoute }}" title="CROWDASET AI" class="flex flex-col items-center gap-0.5 group">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-teal-400 to-[#008080] shadow flex items-center justify-center group-hover:scale-105 transition-transform text-white">
                    <svg viewBox="0 0 24 24" class="w-5 h-5 fill-none stroke-current" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17.5 19H9a7 7 0 1 1 6.71-9h1.79a4.5 4.5 0 1 1 0 9Z"/>
                    </svg>
                </div>
            </a>
        </div>

        <!-- Nav Menu: DINAMIS SESUAI ROLE PENGGUNA -->
        <nav class="flex-1 flex flex-col items-center gap-1 py-4 px-2 overflow-y-auto overflow-x-hidden no-scrollbar">

            @if(auth()->user()?->isVillageAdmin())
                {{-- ============================================================ --}}
                {{-- PERAN: PEMERINTAH DESA (Operasional & Tata Kelola Aset Desa) --}}
                {{-- ============================================================ --}}

                <!-- 1. Dashboard Desa -->
                @php $active = request()->routeIs('dashboard.village') || (request()->routeIs('dashboard.index') && auth()->user()?->isVillageAdmin()); @endphp
                <a href="{{ route('dashboard.village') }}" title="Dashboard Desa"
                   class="w-full flex flex-col items-center gap-1 px-1 py-2.5 rounded-xl text-center transition-all {{ $active ? 'bg-[#008080] text-white shadow-xs' : 'text-slate-400 hover:bg-white/10 hover:text-white' }}">
                    <i data-lucide="layout-dashboard" class="w-5 h-5 {{ $active ? 'text-white' : '' }}"></i>
                    <span class="text-[9.5px] font-semibold leading-none">Dashboard</span>
                </a>

                <!-- 2. Inventaris Aset Desa -->
                @php $active = request()->routeIs('explore') || request()->routeIs('assets.*'); @endphp
                <a href="{{ route('explore') }}" title="Inventaris Aset Desa"
                   class="w-full flex flex-col items-center gap-1 px-1 py-2.5 rounded-xl text-center transition-all {{ $active ? 'bg-[#008080] text-white shadow-xs' : 'text-slate-400 hover:bg-white/10 hover:text-white' }}">
                    <i data-lucide="box" class="w-5 h-5 {{ $active ? 'text-white' : '' }}"></i>
                    <span class="text-[9.5px] font-semibold leading-none">Aset Desa</span>
                </a>

                <!-- 3. Verifikasi Laporan Warga -->
                @php $active = request()->routeIs('dashboard.verification'); @endphp
                <a href="{{ route('dashboard.verification') }}" title="Verifikasi Laporan Warga"
                   class="w-full flex flex-col items-center gap-1 px-1 py-2.5 rounded-xl text-center transition-all {{ $active ? 'bg-[#008080] text-white shadow-xs' : 'text-slate-400 hover:bg-white/10 hover:text-white' }}">
                    <i data-lucide="check-square" class="w-5 h-5 {{ $active ? 'text-white' : '' }}"></i>
                    <span class="text-[9.5px] font-semibold leading-none">Verifikasi</span>
                </a>

                <!-- 4. Laporan SIPADES R3 (Permendagri 1/2016) -->
                @php $active = request()->routeIs('reports.sipades*'); @endphp
                <a href="{{ route('reports.sipades') }}" title="Laporan SIPADES R3"
                   class="w-full flex flex-col items-center gap-1 px-1 py-2.5 rounded-xl text-center transition-all {{ $active ? 'bg-[#008080] text-white shadow-xs' : 'text-slate-400 hover:bg-white/10 hover:text-white' }}">
                    <i data-lucide="file-bar-chart-2" class="w-5 h-5 {{ $active ? 'text-white' : '' }}"></i>
                    <span class="text-[9.5px] font-semibold leading-none">SIPADES</span>
                </a>

                <!-- 5. Batas Wilayah Desa -->
                @php $active = request()->routeIs('dashboard.boundary'); @endphp
                <a href="{{ route('dashboard.boundary') }}" title="Peta Batas Desa"
                   class="w-full flex flex-col items-center gap-1 px-1 py-2.5 rounded-xl text-center transition-all {{ $active ? 'bg-[#008080] text-white shadow-xs' : 'text-slate-400 hover:bg-white/10 hover:text-white' }}">
                    <i data-lucide="scan-line" class="w-5 h-5 {{ $active ? 'text-white' : '' }}"></i>
                    <span class="text-[9.5px] font-semibold leading-none">Batas Desa</span>
                </a>

                <!-- 6. Pengaturan Profil -->
                @php $active = request()->routeIs('profile'); @endphp
                <a href="{{ route('profile') }}" title="Pengaturan Akun Desa"
                   class="w-full flex flex-col items-center gap-1 px-1 py-2.5 rounded-xl text-center transition-all {{ $active ? 'bg-[#008080] text-white shadow-xs' : 'text-slate-400 hover:bg-white/10 hover:text-white' }}">
                    <i data-lucide="settings" class="w-5 h-5 {{ $active ? 'text-white' : '' }}"></i>
                    <span class="text-[9.5px] font-semibold leading-none">Pengaturan</span>
                </a>

            @elseif(auth()->user()?->isDistrictAdmin())
                {{-- ============================================================ --}}
                {{-- PERAN: PEMERINTAH KECAMATAN (Mode Pengawas / Read-Only)     --}}
                {{-- ============================================================ --}}

                <!-- 1. Monitoring Kecamatan -->
                @php $active = request()->routeIs('dashboard.district') || (request()->routeIs('dashboard.index') && auth()->user()?->isDistrictAdmin()); @endphp
                <a href="{{ route('dashboard.district') }}" title="Dashboard Camat"
                   class="w-full flex flex-col items-center gap-1 px-1 py-2.5 rounded-xl text-center transition-all {{ $active ? 'bg-[#008080] text-white shadow-xs' : 'text-slate-400 hover:bg-white/10 hover:text-white' }}">
                    <i data-lucide="building-2" class="w-5 h-5 {{ $active ? 'text-white' : '' }}"></i>
                    <span class="text-[9.5px] font-semibold leading-none">Dashboard</span>
                </a>

                <!-- 2. Peta GIS Wilayah Kecamatan -->
                @php $active = request()->routeIs('map'); @endphp
                <a href="{{ route('map') }}" title="Peta GIS Kecamatan"
                   class="w-full flex flex-col items-center gap-1 px-1 py-2.5 rounded-xl text-center transition-all {{ $active ? 'bg-[#008080] text-white shadow-xs' : 'text-slate-400 hover:bg-white/10 hover:text-white' }}">
                    <i data-lucide="map" class="w-5 h-5 {{ $active ? 'text-white' : '' }}"></i>
                    <span class="text-[9.5px] font-semibold leading-none">Peta GIS</span>
                </a>

                <!-- 3. Rekapitulasi Desa -->
                @php $active = request()->routeIs('reports.villages') || request()->routeIs('reports.kib_a'); @endphp
                <a href="{{ route('reports.villages') }}" title="Rekapitulasi Desa"
                   class="w-full flex flex-col items-center gap-1 px-1 py-2.5 rounded-xl text-center transition-all {{ $active ? 'bg-[#008080] text-white shadow-xs' : 'text-slate-400 hover:bg-white/10 hover:text-white' }}">
                    <i data-lucide="layers" class="w-5 h-5 {{ $active ? 'text-white' : '' }}"></i>
                    <span class="text-[9.5px] font-semibold leading-none">Rekap Desa</span>
                </a>

                <!-- 4. Audit SIPADES Desa (Read-Only) -->
                @php $active = request()->routeIs('reports.sipades*'); @endphp
                <a href="{{ route('reports.sipades') }}" title="Monitoring SIPADES Desa"
                   class="w-full flex flex-col items-center gap-1 px-1 py-2.5 rounded-xl text-center transition-all {{ $active ? 'bg-[#008080] text-white shadow-xs' : 'text-slate-400 hover:bg-white/10 hover:text-white' }}">
                    <i data-lucide="file-text" class="w-5 h-5 {{ $active ? 'text-white' : '' }}"></i>
                    <span class="text-[9.5px] font-semibold leading-none">SIPADES</span>
                </a>

                <!-- 5. Pengaturan Profil -->
                @php $active = request()->routeIs('profile'); @endphp
                <a href="{{ route('profile') }}" title="Pengaturan Akun Kecamatan"
                   class="w-full flex flex-col items-center gap-1 px-1 py-2.5 rounded-xl text-center transition-all {{ $active ? 'bg-[#008080] text-white shadow-xs' : 'text-slate-400 hover:bg-white/10 hover:text-white' }}">
                    <i data-lucide="settings" class="w-5 h-5 {{ $active ? 'text-white' : '' }}"></i>
                    <span class="text-[9.5px] font-semibold leading-none">Pengaturan</span>
                </a>

            @elseif(auth()->user()?->isRegencyAdmin() || auth()->user()?->isSuperAdmin())
                {{-- ============================================================ --}}
                {{-- PERAN: PEMERINTAH KABUPATEN / BAPPEDA (Makro & Analitik AI) --}}
                {{-- ============================================================ --}}

                <!-- 1. Executive Command Center -->
                @php $active = request()->routeIs('dashboard.regency') || (request()->routeIs('dashboard.index') && (auth()->user()?->isRegencyAdmin() || auth()->user()?->isSuperAdmin())); @endphp
                <a href="{{ route('dashboard.regency') }}" title="Command Center Kabupaten"
                   class="w-full flex flex-col items-center gap-1 px-1 py-2.5 rounded-xl text-center transition-all {{ $active ? 'bg-[#008080] text-white shadow-xs' : 'text-slate-400 hover:bg-white/10 hover:text-white' }}">
                    <i data-lucide="bar-chart-2" class="w-5 h-5 {{ $active ? 'text-white' : '' }}"></i>
                    <span class="text-[9.5px] font-semibold leading-none">Command</span>
                </a>

                <!-- 2. Verifikasi Laporan Aset Warga (Otoritas Bappeda) -->
                @php 
                    $active = request()->routeIs('dashboard.verification'); 
                    $pendingReportsCount = \App\Models\AssetReport::where('status', 'pending')->count();
                @endphp
                <a href="{{ route('dashboard.verification') }}" title="Verifikasi & Validasi Laporan Aset Daerah"
                   class="w-full flex flex-col items-center gap-1 px-1 py-2.5 rounded-xl text-center transition-all relative {{ $active ? 'bg-[#008080] text-white shadow-xs' : 'text-slate-400 hover:bg-white/10 hover:text-white' }}">
                    <i data-lucide="check-square" class="w-5 h-5 {{ $active ? 'text-white' : '' }}"></i>
                    <span class="text-[9.5px] font-semibold leading-none">Verifikasi</span>
                    @if($pendingReportsCount > 0)
                        <span class="absolute top-1.5 right-2 w-4 h-4 rounded-full bg-amber-500 text-white font-bold text-[9px] flex items-center justify-center animate-pulse">
                            {{ $pendingReportsCount }}
                        </span>
                    @endif
                </a>

                <!-- 3. Analitik & Simulasi AI -->
                @php $active = request()->routeIs('dashboard.simulation') || request()->routeIs('dashboard.consensus') || request()->routeIs('dashboard.opportunities'); @endphp
                <a href="{{ route('dashboard.simulation') }}" title="Analitik AI & Simulasi Potensi"
                   class="w-full flex flex-col items-center gap-1 px-1 py-2.5 rounded-xl text-center transition-all {{ $active ? 'bg-[#008080] text-white shadow-xs' : 'text-slate-400 hover:bg-white/10 hover:text-white' }}">
                    <i data-lucide="brain-circuit" class="w-5 h-5 {{ $active ? 'text-white' : '' }}"></i>
                    <span class="text-[9.5px] font-semibold leading-none">Simulasi AI</span>
                </a>

                <!-- 3. Peta GIS Makro Kabupaten -->
                @php $active = request()->routeIs('map'); @endphp
                <a href="{{ route('map') }}" title="Peta GIS Makro Kabupaten"
                   class="w-full flex flex-col items-center gap-1 px-1 py-2.5 rounded-xl text-center transition-all {{ $active ? 'bg-[#008080] text-white shadow-xs' : 'text-slate-400 hover:bg-white/10 hover:text-white' }}">
                    <i data-lucide="map" class="w-5 h-5 {{ $active ? 'text-white' : '' }}"></i>
                    <span class="text-[9.5px] font-semibold leading-none">Peta Makro</span>
                </a>

                <!-- 4. Agenda Revitalisasi Aset -->
                @php $active = request()->routeIs('dashboard.projects'); @endphp
                <a href="{{ route('dashboard.projects') }}" title="Proyek Revitalisasi Aset"
                   class="w-full flex flex-col items-center gap-1 px-1 py-2.5 rounded-xl text-center transition-all {{ $active ? 'bg-[#008080] text-white shadow-xs' : 'text-slate-400 hover:bg-white/10 hover:text-white' }}">
                    <i data-lucide="hammer" class="w-5 h-5 {{ $active ? 'text-white' : '' }}"></i>
                    <span class="text-[9.5px] font-semibold leading-none">Proyek</span>
                </a>

                <!-- 5. Konsolidasi SIPADES Kabupaten -->
                @php $active = request()->routeIs('reports.sipades*'); @endphp
                <a href="{{ route('reports.sipades') }}" title="Konsolidasi SIPADES 18 Kecamatan"
                   class="w-full flex flex-col items-center gap-1 px-1 py-2.5 rounded-xl text-center transition-all {{ $active ? 'bg-[#008080] text-white shadow-xs' : 'text-slate-400 hover:bg-white/10 hover:text-white' }}">
                    <i data-lucide="file-bar-chart-2" class="w-5 h-5 {{ $active ? 'text-white' : '' }}"></i>
                    <span class="text-[9.5px] font-semibold leading-none">SIPADES</span>
                </a>

                <!-- 6. Pengaturan Profil -->
                @php $active = request()->routeIs('profile'); @endphp
                <a href="{{ route('profile') }}" title="Pengaturan Akun Kabupaten"
                   class="w-full flex flex-col items-center gap-1 px-1 py-2.5 rounded-xl text-center transition-all {{ $active ? 'bg-[#008080] text-white shadow-xs' : 'text-slate-400 hover:bg-white/10 hover:text-white' }}">
                    <i data-lucide="settings" class="w-5 h-5 {{ $active ? 'text-white' : '' }}"></i>
                    <span class="text-[9.5px] font-semibold leading-none">Pengaturan</span>
                </a>

            @else
                {{-- ============================================================ --}}
                {{-- PERAN: MASYARAKAT / WARGA / PUBLIK (Eksplorasi & Pelaporan) --}}
                {{-- ============================================================ --}}

                <!-- 1. Beranda Publik -->
                @php $active = request()->routeIs('home'); @endphp
                <a href="{{ route('home') }}" title="Beranda"
                   class="w-full flex flex-col items-center gap-1 px-1 py-2.5 rounded-xl text-center transition-all {{ $active ? 'bg-[#008080] text-white shadow-xs' : 'text-slate-400 hover:bg-white/10 hover:text-white' }}">
                    <i data-lucide="home" class="w-5 h-5 {{ $active ? 'text-white' : '' }}"></i>
                    <span class="text-[9.5px] font-semibold leading-none">Beranda</span>
                </a>

                <!-- 2. Peta GIS Interaktif -->
                @php $active = request()->routeIs('map'); @endphp
                <a href="{{ route('map') }}" title="Peta GIS Interaktif"
                   class="w-full flex flex-col items-center gap-1 px-1 py-2.5 rounded-xl text-center transition-all {{ $active ? 'bg-[#008080] text-white shadow-xs' : 'text-slate-400 hover:bg-white/10 hover:text-white' }}">
                    <i data-lucide="map" class="w-5 h-5 {{ $active ? 'text-white' : '' }}"></i>
                    <span class="text-[9.5px] font-semibold leading-none">Peta GIS</span>
                </a>

                <!-- 3. Eksplorasi Aset -->
                @php $active = request()->routeIs('explore') || request()->routeIs('assets.*'); @endphp
                <a href="{{ route('explore') }}" title="Katalog Data Aset"
                   class="w-full flex flex-col items-center gap-1 px-1 py-2.5 rounded-xl text-center transition-all {{ $active ? 'bg-[#008080] text-white shadow-xs' : 'text-slate-400 hover:bg-white/10 hover:text-white' }}">
                    <i data-lucide="database" class="w-5 h-5 {{ $active ? 'text-white' : '' }}"></i>
                    <span class="text-[9.5px] font-semibold leading-none">Data Aset</span>
                </a>

                <!-- 4. Buat Laporan / Usulan Aset -->
                @php $active = request()->routeIs('reports.create'); @endphp
                <a href="{{ route('reports.create') }}" title="Laporkan Aset Tidur"
                   class="w-full flex flex-col items-center gap-1 px-1 py-2.5 rounded-xl text-center transition-all {{ $active ? 'bg-[#008080] text-white shadow-xs' : 'text-slate-400 hover:bg-white/10 hover:text-white' }}">
                    <i data-lucide="plus-circle" class="w-5 h-5 {{ $active ? 'text-white' : '' }}"></i>
                    <span class="text-[9.5px] font-semibold leading-none">Lapor</span>
                </a>

                <!-- 5. Profil Saya -->
                @php $active = request()->routeIs('profile'); @endphp
                <a href="{{ route('profile') }}" title="Profil Saya"
                   class="w-full flex flex-col items-center gap-1 px-1 py-2.5 rounded-xl text-center transition-all {{ $active ? 'bg-[#008080] text-white shadow-xs' : 'text-slate-400 hover:bg-white/10 hover:text-white' }}">
                    <i data-lucide="user" class="w-5 h-5 {{ $active ? 'text-white' : '' }}"></i>
                    <span class="text-[9.5px] font-semibold leading-none">Profil</span>
                </a>

            @endif

        </nav>

        <!-- Bottom Role Badge -->
        <div class="px-2 pb-2">
            @if(auth()->user()?->isDistrictAdmin())
                <div class="flex flex-col items-center gap-0.5 py-1.5 px-1 rounded-xl bg-sky-900/60 border border-sky-700/40 text-center" title="Mode Pengawas: Hanya Lihat">
                    <i data-lucide="eye" class="w-3.5 h-3.5 text-sky-400"></i>
                    <span class="text-[8px] text-sky-300 font-bold leading-tight">Pengawas</span>
                </div>
            @elseif(auth()->user()?->isVillageAdmin())
                <div class="flex flex-col items-center gap-0.5 py-1.5 px-1 rounded-xl bg-emerald-900/60 border border-emerald-700/40 text-center" title="Pemerintah Desa">
                    <i data-lucide="home" class="w-3.5 h-3.5 text-emerald-400"></i>
                    <span class="text-[8px] text-emerald-300 font-bold leading-tight">Desa</span>
                </div>
            @elseif(auth()->user()?->isRegencyAdmin() || auth()->user()?->isSuperAdmin())
                <div class="flex flex-col items-center gap-0.5 py-1.5 px-1 rounded-xl bg-purple-900/60 border border-purple-700/40 text-center" title="Bappeda Kabupaten">
                    <i data-lucide="shield" class="w-3.5 h-3.5 text-purple-400"></i>
                    <span class="text-[8px] text-purple-300 font-bold leading-tight">Bappeda</span>
                </div>
            @else
                <div class="flex flex-col items-center gap-0.5 py-1.5 px-1 rounded-xl bg-teal-900/60 border border-teal-700/40 text-center" title="Warga Komunitas">
                    <i data-lucide="users" class="w-3.5 h-3.5 text-teal-400"></i>
                    <span class="text-[8px] text-teal-300 font-bold leading-tight">Warga</span>
                </div>
            @endif
        </div>

        <!-- User Avatar + Switcher / Logout -->
        <div class="border-t border-white/10 p-2 flex flex-col items-center gap-2">
            <!-- Demo role switcher -->
            <div class="relative w-full" x-data="{ open: false }">
                <button @click="open = !open"
                        title="Ganti Peran"
                        class="w-full flex flex-col items-center gap-1 py-2 rounded-xl text-slate-400 hover:bg-white/10 hover:text-white transition-all">
                    <div class="w-8 h-8 rounded-full bg-[#008080] text-white flex items-center justify-center font-bold text-xs">
                        <i data-lucide="user" class="w-3.5 h-3.5"></i>
                    </div>
                    <span class="text-[8.5px] font-semibold leading-none text-center max-w-full truncate px-0.5">
                        {{ Str::limit(auth()->user()?->name ?? 'User', 6) }}
                    </span>
                </button>
                <!-- Dropdown -->
                <div x-show="open" @click.outside="open = false" x-cloak
                     class="absolute bottom-full left-full ml-2 mb-1 w-52 bg-[#0d1b2a] border border-white/10 rounded-xl shadow-2xl py-2 z-50 text-xs">
                    <p class="px-3 pb-1 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Ganti Peran Akses</p>
                    <a href="{{ route('auth.demo', 'desa') }}" class="flex items-center gap-2 px-3 py-2 hover:bg-white/10 text-slate-300 {{ auth()->user()?->isVillageAdmin() ? 'text-teal-400 font-bold' : '' }}">
                        <i data-lucide="home" class="w-3.5 h-3.5"></i> Pemerintah Desa
                    </a>
                    <a href="{{ route('auth.demo', 'kecamatan') }}" class="flex items-center gap-2 px-3 py-2 hover:bg-white/10 text-slate-300 {{ auth()->user()?->isDistrictAdmin() ? 'text-teal-400 font-bold' : '' }}">
                        <i data-lucide="building-2" class="w-3.5 h-3.5"></i> Kecamatan
                    </a>
                    <a href="{{ route('auth.demo', 'kabupaten') }}" class="flex items-center gap-2 px-3 py-2 hover:bg-white/10 text-slate-300 {{ (auth()->user()?->isRegencyAdmin() || auth()->user()?->isSuperAdmin()) ? 'text-teal-400 font-bold' : '' }}">
                        <i data-lucide="landmark" class="w-3.5 h-3.5"></i> Bappeda Kabupaten
                    </a>
                    <a href="{{ route('auth.demo', 'masyarakat') }}" class="flex items-center gap-2 px-3 py-2 hover:bg-white/10 text-slate-300 {{ (auth()->user()?->isCommunity() || !auth()->check()) ? 'text-teal-400 font-bold' : '' }}">
                        <i data-lucide="users" class="w-3.5 h-3.5"></i> Warga / Publik
                    </a>
                    <div class="border-t border-white/10 mt-1 pt-1">
                        @if(auth()->check())
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 hover:bg-red-900/40 text-red-400 font-medium text-left">
                                <i data-lucide="log-out" class="w-3.5 h-3.5"></i> Keluar
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </aside>



    <!-- MAIN DASHBOARD CONTENT AREA -->
    <div class="flex-1 flex flex-col min-w-0 overflow-y-auto bg-[#f8fafc]">

        <!-- TOP HEADER BAR (CROWDASET AI EXACT MATCH) -->
        <header class="h-16 bg-white border-b border-slate-200/90 px-6 flex items-center justify-between gap-4 sticky top-0 z-20 shadow-xs select-none">
            
            <!-- Left: Logo & Slogan -->
            <div class="flex items-center gap-3">
                <a href="{{ $homeRoute }}" class="w-9 h-9 rounded-xl bg-gradient-to-br from-teal-500 to-[#008080] flex items-center justify-center text-white shadow-xs hover:scale-105 transition-transform">
                    <svg viewBox="0 0 24 24" class="w-5 h-5 fill-none stroke-current" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                    </svg>
                </a>
                <div>
                    <div class="flex items-center gap-1.5 leading-none">
                        <span class="font-heading font-black text-slate-900 text-base tracking-tight">CROWDASET</span>
                        <span class="font-heading font-extrabold text-[#008080] text-base">AI</span>
                    </div>
                    <span class="text-[10px] text-slate-400 font-medium tracking-tight block mt-0.5">Kolaborasi Data Aset untuk Kabupaten yang Lebih Baik</span>
                </div>
            </div>

            <!-- Middle: Search Bar -->
            <div class="hidden md:flex flex-1 max-w-md mx-6">
                <div class="relative w-full">
                    <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2"></i>
                    <input 
                        type="text" 
                        placeholder="Cari aset, desa, kecamatan..." 
                        class="w-full pl-9 pr-4 py-2 text-xs bg-slate-50 hover:bg-slate-100/80 focus:bg-white border border-slate-200/90 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-600 focus:border-transparent transition-all placeholder:text-slate-400"
                    />
                </div>
            </div>

            <!-- Right: Notifications & User Info -->
            <div class="flex items-center gap-3 sm:gap-4">
                
                <!-- Notification Bell with Interactive Dropdown -->
                <div class="relative" x-data="{ 
                    notifOpen: false, 
                    unreadCount: 3, 
                    readList: [],
                    markAsRead(id) {
                        if (!this.readList.includes(id)) {
                            this.readList.push(id);
                            if (this.unreadCount > 0) this.unreadCount--;
                        }
                    },
                    markAllRead() {
                        this.readList = [1, 2, 3];
                        this.unreadCount = 0;
                    }
                }">
                    <!-- Bell Button -->
                    <button 
                        type="button"
                        @click="notifOpen = !notifOpen"
                        :class="notifOpen ? 'bg-slate-100 text-teal-700 ring-2 ring-teal-500/20' : 'hover:bg-slate-100 text-slate-600'"
                        class="relative w-9 h-9 rounded-xl flex items-center justify-center transition-all cursor-pointer"
                        title="Pemberitahuan & Aktivitas"
                    >
                        <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        
                        <!-- Unread Red Badge -->
                        <span 
                            x-show="unreadCount > 0" 
                            x-cloak
                            x-text="unreadCount"
                            class="absolute -top-0.5 -right-0.5 min-w-[18px] h-[18px] px-1 rounded-full bg-rose-600 text-white font-black text-[10px] flex items-center justify-center shadow-md ring-2 ring-white animate-pulse"
                        >3</span>
                    </button>

                    <!-- Notifications Dropdown Popover -->
                    <div 
                        x-show="notifOpen" 
                        x-transition:enter="transition ease-out duration-200" 
                        x-transition:enter-start="opacity-0 scale-95 -translate-y-2" 
                        x-transition:enter-end="opacity-100 scale-100 translate-y-0" 
                        x-transition:leave="transition ease-in duration-150" 
                        x-transition:leave-start="opacity-100 scale-100 translate-y-0" 
                        x-transition:leave-end="opacity-0 scale-95 -translate-y-2" 
                        @click.outside="notifOpen = false" 
                        x-cloak 
                        class="absolute right-0 top-full mt-2.5 w-80 sm:w-96 bg-white rounded-2xl shadow-2xl border border-slate-200 z-50 overflow-hidden text-slate-800 animate-in"
                    >
                        <!-- Popover Header -->
                        <div class="px-4 py-3 bg-slate-900 text-white flex items-center justify-between gap-2 border-b border-slate-800">
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full bg-teal-400 animate-ping"></div>
                                <h4 class="font-heading font-extrabold text-xs tracking-wide">Pemberitahuan</h4>
                                <span 
                                    x-show="unreadCount > 0" 
                                    x-cloak
                                    class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-500/20 text-rose-300 border border-rose-500/30"
                                >
                                    <span x-text="unreadCount"></span> Baru
                                </span>
                            </div>
                            <button 
                                type="button" 
                                @click="markAllRead()" 
                                x-show="unreadCount > 0"
                                class="text-[10px] text-teal-300 hover:text-white font-semibold underline underline-offset-2 transition-colors cursor-pointer"
                            >
                                Tandai semua dibaca
                            </button>
                        </div>

                        <!-- Notification List (Role-Tailored) -->
                        <div class="max-h-80 overflow-y-auto divide-y divide-slate-100">
                            
                            @if(auth()->user()?->isDistrictAdmin())
                                {{-- NOTIF KECAMATAN --}}
                                <!-- Item 1 -->
                                <a href="{{ route('reports.villages') }}" @click="markAsRead(1); notifOpen = false" class="p-3.5 flex items-start gap-3 hover:bg-slate-50 transition-colors block text-left group">
                                    <div class="w-8 h-8 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center shrink-0 mt-0.5 group-hover:scale-110 transition-transform">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between gap-1">
                                            <p class="text-xs font-bold text-slate-900 group-hover:text-teal-700">Rekap Desa Banyuwangi</p>
                                            <span class="w-2 h-2 rounded-full bg-rose-500 shrink-0" x-show="!readList.includes(1)"></span>
                                        </div>
                                        <p class="text-[11px] text-slate-500 mt-0.5 leading-snug">Laporan inventaris SIPADES Semester I telah diunggah dan siap diverifikasi Camat.</p>
                                        <span class="text-[9.5px] text-slate-400 mt-1 block font-medium">10 menit yang lalu</span>
                                    </div>
                                </a>

                                <!-- Item 2 -->
                                <a href="{{ route('map') }}" @click="markAsRead(2); notifOpen = false" class="p-3.5 flex items-start gap-3 hover:bg-slate-50 transition-colors block text-left group">
                                    <div class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center shrink-0 mt-0.5 group-hover:scale-110 transition-transform">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between gap-1">
                                            <p class="text-xs font-bold text-slate-900 group-hover:text-teal-700">Laporan Aset Masuk</p>
                                            <span class="w-2 h-2 rounded-full bg-rose-500 shrink-0" x-show="!readList.includes(2)"></span>
                                        </div>
                                        <p class="text-[11px] text-slate-500 mt-0.5 leading-snug">Warga melaporkan 1 aset terbengkalai di wilayah Manyar Sidomukti.</p>
                                        <span class="text-[9.5px] text-slate-400 mt-1 block font-medium">1 jam yang lalu</span>
                                    </div>
                                </a>

                                <!-- Item 3 -->
                                <a href="{{ route('reports.sipades') }}" @click="markAsRead(3); notifOpen = false" class="p-3.5 flex items-start gap-3 hover:bg-slate-50 transition-colors block text-left group">
                                    <div class="w-8 h-8 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center shrink-0 mt-0.5 group-hover:scale-110 transition-transform">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between gap-1">
                                            <p class="text-xs font-bold text-slate-900 group-hover:text-teal-700">Sinkronisasi Makro</p>
                                            <span class="w-2 h-2 rounded-full bg-rose-500 shrink-0" x-show="!readList.includes(3)"></span>
                                        </div>
                                        <p class="text-[11px] text-slate-500 mt-0.5 leading-snug">Sistem berhasil memadukan 16 desa di Manyar ke dalam database kabupaten.</p>
                                        <span class="text-[9.5px] text-slate-400 mt-1 block font-medium">3 jam yang lalu</span>
                                    </div>
                                </a>

                            @elseif(auth()->user()?->isVillageAdmin())
                                {{-- NOTIF DESA --}}
                                <!-- Item 1 -->
                                <a href="{{ route('dashboard.verification') }}" @click="markAsRead(1); notifOpen = false" class="p-3.5 flex items-start gap-3 hover:bg-slate-50 transition-colors block text-left group">
                                    <div class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center shrink-0 mt-0.5 group-hover:scale-110 transition-transform">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between gap-1">
                                            <p class="text-xs font-bold text-slate-900 group-hover:text-teal-700">Laporan Warga Perlu Verifikasi</p>
                                            <span class="w-2 h-2 rounded-full bg-rose-500 shrink-0" x-show="!readList.includes(1)"></span>
                                        </div>
                                        <p class="text-[11px] text-slate-500 mt-0.5 leading-snug">Ada laporan aset terbengkalai di dusun timur yang memerlukan pengecekan lapangan.</p>
                                        <span class="text-[9.5px] text-slate-400 mt-1 block font-medium">15 menit yang lalu</span>
                                    </div>
                                </a>

                                <!-- Item 2 -->
                                <a href="{{ route('reports.sipades') }}" @click="markAsRead(2); notifOpen = false" class="p-3.5 flex items-start gap-3 hover:bg-slate-50 transition-colors block text-left group">
                                    <div class="w-8 h-8 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center shrink-0 mt-0.5 group-hover:scale-110 transition-transform">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between gap-1">
                                            <p class="text-xs font-bold text-slate-900 group-hover:text-teal-700">SIPADES: KIB A Siap Ekspor</p>
                                            <span class="w-2 h-2 rounded-full bg-rose-500 shrink-0" x-show="!readList.includes(2)"></span>
                                        </div>
                                        <p class="text-[11px] text-slate-500 mt-0.5 leading-snug">Format inventaris tanah desa telah diperbarui sesuai standar Permendagri.</p>
                                        <span class="text-[9.5px] text-slate-400 mt-1 block font-medium">2 jam yang lalu</span>
                                    </div>
                                </a>

                                <!-- Item 3 -->
                                <a href="{{ route('dashboard.boundary') }}" @click="markAsRead(3); notifOpen = false" class="p-3.5 flex items-start gap-3 hover:bg-slate-50 transition-colors block text-left group">
                                    <div class="w-8 h-8 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center shrink-0 mt-0.5 group-hover:scale-110 transition-transform">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"/><line x1="8" y1="2" x2="8" y2="18"/><line x1="16" y1="6" x2="16" y2="22"/></svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between gap-1">
                                            <p class="text-xs font-bold text-slate-900 group-hover:text-teal-700">Batas Geospasial Desa</p>
                                            <span class="w-2 h-2 rounded-full bg-rose-500 shrink-0" x-show="!readList.includes(3)"></span>
                                        </div>
                                        <p class="text-[11px] text-slate-500 mt-0.5 leading-snug">Peta poligon batas administrasi desa telah disinkronkan dengan Bappeda.</p>
                                        <span class="text-[9.5px] text-slate-400 mt-1 block font-medium">1 hari yang lalu</span>
                                    </div>
                                </a>

                            @elseif(auth()->user()?->isRegencyAdmin() || auth()->user()?->isSuperAdmin())
                                {{-- NOTIF KABUPATEN / BAPPEDA --}}
                                <!-- Item 1 -->
                                <a href="{{ route('dashboard.projects') }}" @click="markAsRead(1); notifOpen = false" class="p-3.5 flex items-start gap-3 hover:bg-slate-50 transition-colors block text-left group">
                                    <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 mt-0.5 group-hover:scale-110 transition-transform">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between gap-1">
                                            <p class="text-xs font-bold text-slate-900 group-hover:text-teal-700">Usulan Proyek BUMDes Masuk</p>
                                            <span class="w-2 h-2 rounded-full bg-rose-500 shrink-0" x-show="!readList.includes(1)"></span>
                                        </div>
                                        <p class="text-[11px] text-slate-500 mt-0.5 leading-snug">Usulan Sentra UMKM Kuliner Manyar menunggu persetujuan tim Bappeda.</p>
                                        <span class="text-[9.5px] text-slate-400 mt-1 block font-medium">20 menit yang lalu</span>
                                    </div>
                                </a>

                                <!-- Item 2 -->
                                <a href="{{ route('dashboard.simulation') }}" @click="markAsRead(2); notifOpen = false" class="p-3.5 flex items-start gap-3 hover:bg-slate-50 transition-colors block text-left group">
                                    <div class="w-8 h-8 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center shrink-0 mt-0.5 group-hover:scale-110 transition-transform">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between gap-1">
                                            <p class="text-xs font-bold text-slate-900 group-hover:text-teal-700">Simulasi AI Selesai Diproses</p>
                                            <span class="w-2 h-2 rounded-full bg-rose-500 shrink-0" x-show="!readList.includes(2)"></span>
                                        </div>
                                        <p class="text-[11px] text-slate-500 mt-0.5 leading-snug">Estimasi optimalisasi aset tidur menghasilkan potensi PAD Rp 1,42 Triliun.</p>
                                        <span class="text-[9.5px] text-slate-400 mt-1 block font-medium">1 jam yang lalu</span>
                                    </div>
                                </a>

                                <!-- Item 3 -->
                                <a href="{{ route('dashboard.regency') }}" @click="markAsRead(3); notifOpen = false" class="p-3.5 flex items-start gap-3 hover:bg-slate-50 transition-colors block text-left group">
                                    <div class="w-8 h-8 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center shrink-0 mt-0.5 group-hover:scale-110 transition-transform">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between gap-1">
                                            <p class="text-xs font-bold text-slate-900 group-hover:text-teal-700">Command Center Terintegrasi</p>
                                            <span class="w-2 h-2 rounded-full bg-rose-500 shrink-0" x-show="!readList.includes(3)"></span>
                                        </div>
                                        <p class="text-[11px] text-slate-500 mt-0.5 leading-snug">16 Kecamatan & 356 desa telah terhubung dalam dashboard makro spasial.</p>
                                        <span class="text-[9.5px] text-slate-400 mt-1 block font-medium">4 jam yang lalu</span>
                                    </div>
                                </a>

                            @else
                                {{-- NOTIF WARGA / PUBLIK --}}
                                <!-- Item 1 -->
                                <a href="{{ route('profile') }}" @click="markAsRead(1); notifOpen = false" class="p-3.5 flex items-start gap-3 hover:bg-slate-50 transition-colors block text-left group">
                                    <div class="w-8 h-8 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center shrink-0 mt-0.5 group-hover:scale-110 transition-transform">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between gap-1">
                                            <p class="text-xs font-bold text-slate-900 group-hover:text-teal-700">Laporan Aset Diterima</p>
                                            <span class="w-2 h-2 rounded-full bg-rose-500 shrink-0" x-show="!readList.includes(1)"></span>
                                        </div>
                                        <p class="text-[11px] text-slate-500 mt-0.5 leading-snug">Laporan aset Anda telah masuk ke sistem dan diteruskan ke aparatur desa untuk verifikasi.</p>
                                        <span class="text-[9.5px] text-slate-400 mt-1 block font-medium">5 menit yang lalu</span>
                                    </div>
                                </a>

                                <!-- Item 2 -->
                                <a href="{{ route('map') }}" @click="markAsRead(2); notifOpen = false" class="p-3.5 flex items-start gap-3 hover:bg-slate-50 transition-colors block text-left group">
                                    <div class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center shrink-0 mt-0.5 group-hover:scale-110 transition-transform">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between gap-1">
                                            <p class="text-xs font-bold text-slate-900 group-hover:text-teal-700">Aset Dipetakan di Peta GIS</p>
                                            <span class="w-2 h-2 rounded-full bg-rose-500 shrink-0" x-show="!readList.includes(2)"></span>
                                        </div>
                                        <p class="text-[11px] text-slate-500 mt-0.5 leading-snug">Laporan aset Anda telah diverifikasi dan kini tampil di peta spasial Kabupaten Gresik.</p>
                                        <span class="text-[9.5px] text-slate-400 mt-1 block font-medium">45 menit yang lalu</span>
                                    </div>
                                </a>

                                <!-- Item 3 -->
                                <a href="{{ route('explore') }}" @click="markAsRead(3); notifOpen = false" class="p-3.5 flex items-start gap-3 hover:bg-slate-50 transition-colors block text-left group">
                                    <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 mt-0.5 group-hover:scale-110 transition-transform">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between gap-1">
                                            <p class="text-xs font-bold text-slate-900 group-hover:text-teal-700">Usulan UMKM Ditanggapi</p>
                                            <span class="w-2 h-2 rounded-full bg-rose-500 shrink-0" x-show="!readList.includes(3)"></span>
                                        </div>
                                        <p class="text-[11px] text-slate-500 mt-0.5 leading-snug">Aparatur desa mengapresiasi ide sentra kuliner BUMDes di lahan kosong perempatan.</p>
                                        <span class="text-[9.5px] text-slate-400 mt-1 block font-medium">2 jam yang lalu</span>
                                    </div>
                                </a>

                            @endif

                        </div>

                        <!-- Popover Footer -->
                        <div class="px-4 py-2.5 bg-slate-50 border-t border-slate-100 flex items-center justify-between text-xs">
                            <span class="text-[10px] text-slate-400 font-medium">CROWDASET Notifikasi Real-Time</span>
                            <a 
                                href="{{ auth()->user()?->isVillageAdmin() ? route('dashboard.verification') : (auth()->user()?->isDistrictAdmin() ? route('reports.villages') : (auth()->user()?->isRegencyAdmin() || auth()->user()?->isSuperAdmin() ? route('dashboard.projects') : route('profile'))) }}" 
                                class="text-[11px] font-bold text-teal-700 hover:text-teal-900 flex items-center gap-1"
                            >
                                <span>Lihat Semua</span>
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- User Profile Pill (Clickable to Profile Page) -->
                <a href="{{ route('profile') }}" class="flex items-center gap-2.5 pl-2 border-l border-slate-200 hover:opacity-85 transition-all group" title="Buka Profil Saya">
                    <div class="w-8 h-8 rounded-full bg-sky-600 text-white flex items-center justify-center font-bold text-xs shrink-0 shadow-xs group-hover:scale-105 transition-transform">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <div class="hidden sm:block text-left leading-tight">
                        <p class="text-xs font-bold text-slate-900 truncate group-hover:text-teal-700 transition-colors">
                            @if(auth()->user()?->isDistrictAdmin())
                                Admin Kecamatan ({{ auth()->user()->district?->name ?? 'Manyar' }})
                            @elseif(auth()->user()?->isVillageAdmin())
                                Admin Desa ({{ auth()->user()->village?->name ?? 'Manyar Sidomukti' }})
                            @elseif(auth()->user()?->isRegencyAdmin() || auth()->user()?->isSuperAdmin())
                                Bappeda Kab. Gresik
                            @else
                                {{ auth()->user()?->name ?? 'Masyarakat Gresik' }}
                            @endif
                        </p>
                        <p class="text-[10px] text-slate-400 font-medium">
                            @if(auth()->user()?->isDistrictAdmin())
                                Mode Pengawas • Kab. Gresik
                            @elseif(auth()->user()?->isVillageAdmin())
                                Kec. {{ auth()->user()->village?->district?->name ?? 'Manyar' }} • Kab. Gresik
                            @elseif(auth()->user()?->isRegencyAdmin() || auth()->user()?->isSuperAdmin())
                                Pusat Komando Makro
                            @else
                                Partisipasi Warga Aktif
                            @endif
                        </p>
                    </div>
                </a>

            </div>

        </header>

        <main class="p-5 space-y-5">
            {{ $slot ?? '' }}
            @yield('content')
        </main>
    </div>

    @livewireScripts
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
            if (typeof initVillageDashboardMap === 'function') {
                initVillageDashboardMap();
            }
            if (typeof initBoundaryEditor === 'function') {
                initBoundaryEditor();
            }
        });
        document.addEventListener('livewire:navigated', () => {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
            if (typeof initVillageDashboardMap === 'function') {
                initVillageDashboardMap();
            }
            if (typeof initBoundaryEditor === 'function') {
                initBoundaryEditor();
            }
        });


        // State map global agar bisa di-refresh tanpa re-init
        let _villageDashboardMap = null;
        let _villageMarkerLayer = null;

        function refreshVillageMapMarkers() {
            if (!_villageDashboardMap || !_villageMarkerLayer) return;

            _villageMarkerLayer.clearLayers();

            const dataEl = document.getElementById('village-asset-data');
            const dbAssets = dataEl ? JSON.parse(dataEl.dataset.assets || '[]') : [];

            const demoMarkers = [
                { lat: -7.1330, lng: 112.6000, color: '#00c9a7', name: 'Tanah Kas Desa Blok A', category: 'Demo', condition: 'produktif' },
                { lat: -7.1310, lng: 112.6030, color: '#00c9a7', name: 'Gedung Pertemuan Sukomulyo', category: 'Demo', condition: 'produktif' },
                { lat: -7.1325, lng: 112.6050, color: '#00c9a7', name: 'Pujasera BUMDes', category: 'Demo', condition: 'produktif' },
                { lat: -7.1370, lng: 112.6010, color: '#ff5722', name: 'Gudang KUD Terbengkalai', category: 'Demo', condition: 'terbengkalai' },
            ];

            const markers = dbAssets.length > 0 ? dbAssets.map(function(a) {
                const needsAttention = ['terbengkalai', 'rusak', 'tidak_digunakan', 'kurang_produktif'].includes(a.condition);
                return Object.assign({}, a, { color: needsAttention ? '#ff5722' : '#00c9a7' });
            }) : demoMarkers;

            markers.forEach(function(m) {
                const dotIcon = L.divIcon({
                    className: 'custom-dot',
                    html: '<div style="background-color: ' + m.color + '; width: 12px; height: 12px; border-radius: 50%; border: 2px solid white; box-shadow: 0 1px 4px rgba(0,0,0,0.4); cursor: pointer;"></div>',
                    iconSize: [12, 12],
                    iconAnchor: [6, 6]
                });
                const kondisiLabel = {
                    'produktif': 'Produktif',
                    'kurang_produktif': 'Kurang Produktif',
                    'jarang_digunakan': 'Jarang Digunakan',
                    'tidak_digunakan': 'Tidak Digunakan',
                    'terbengkalai': 'Terbengkalai',
                    'rusak': 'Rusak'
                }[m.condition] || m.condition || 'Aktif / Terdata';
                L.marker([m.lat, m.lng], { icon: dotIcon })
                    .bindPopup('<div style="font-family:sans-serif;font-size:12px;min-width:160px;"><strong>' + m.name + '</strong><br><span style="color:#64748b;font-size:11px;">Kondisi: ' + kondisiLabel + '</span><br><span style="color:#94a3b8;font-size:10px;">' + (m.category || '') + '</span></div>')
                    .addTo(_villageMarkerLayer);
            });
        }

        function initVillageDashboardMap() {
            const container = document.getElementById('referenceVillageMap');
            if (!container || typeof L === 'undefined') return;

            // Jika map sudah ada, cukup refresh marker-nya saja
            if (container._leaflet_id) {
                refreshVillageMapMarkers();
                return;
            }

            _villageDashboardMap = L.map('referenceVillageMap', {
                zoomControl: true,
                attributionControl: false
            }).setView([-7.1350, 112.6020], 15);

            const googleEarth = L.tileLayer('https://mt1.google.com/vt/lyrs=y&x={x}&y={y}&z={z}', {
                maxZoom: 20,
                subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
            }).addTo(_villageDashboardMap);

            const streetMap = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19
            });

            L.control.layers({
                'Citra Satelit': googleEarth,
                'Peta Standar': streetMap
            }, null, { position: 'topright' }).addTo(_villageDashboardMap);

            // Batas wilayah desa — diambil dinamis dari DB
            const dataEl2 = document.getElementById('village-asset-data');
            const boundaryCoords = dataEl2 ? JSON.parse(dataEl2.dataset.boundary || '[]') : [];
            const villageName    = dataEl2 ? (dataEl2.dataset.villageName || 'Desa') : 'Desa';

            if (boundaryCoords.length >= 3) {
                L.polygon(boundaryCoords, {
                    color: '#0284c7',
                    weight: 2,
                    fillColor: '#0284c7',
                    fillOpacity: 0.08,
                    dashArray: '4, 4'
                }).bindPopup("<div style='font-family:sans-serif;font-size:12px;'><strong>Batas Delimitasi Aset " + villageName + "</strong><p style='margin-top:2px;color:#64748b;font-size:11px;'>Zona garis biru adalah batas area hamparan tanah kas desa &amp; aset potensial.</p><div style='margin-top:6px;'><a href='/dashboard/boundary' style='display:inline-block;padding:3px 8px;background:#0284c7;color:white;border-radius:6px;font-size:11px;font-weight:bold;text-decoration:none;'>Perluas / Ubah Batas &rarr;</a></div></div>")
                  .bindTooltip("Batas Lahan Aset " + villageName, { sticky: true })
                  .addTo(_villageDashboardMap);
            }

            // Klik peta untuk laporkan atau tambah aset
            _villageDashboardMap.on('click', function(e) {
                const lat = parseFloat(e.latlng.lat.toFixed(6));
                const lng = parseFloat(e.latlng.lng.toFixed(6));
                L.popup()
                    .setLatLng(e.latlng)
                    .setContent('<div style="font-family:sans-serif;font-size:12px;line-height:1.4;"><strong style="color:#0f172a;display:block;margin-bottom:2px;">Titik Koordinat Terpilih</strong><span style="color:#64748b;font-size:11px;">Lat: ' + lat + ', Lng: ' + lng + '</span><div style="margin-top:6px;display:flex;gap:6px;flex-wrap:wrap;"><button type="button" onclick="window.__openCreateAssetAtPoint(' + lat + ', ' + lng + ')" style="display:inline-block;padding:4px 8px;background-color:#008080;color:white;border-radius:6px;font-size:11px;font-weight:bold;cursor:pointer;border:none;">+ Tambah Aset di Sini</button><a href="/report?lat=' + lat + '&lng=' + lng + '" style="display:inline-block;padding:4px 8px;background-color:#0284c7;color:white;border-radius:6px;font-size:11px;font-weight:bold;text-decoration:none;">Laporan Warga</a></div></div>')
                    .openOn(_villageDashboardMap);
            });

            // Layer grup untuk marker (agar bisa di-clear & refresh)
            _villageMarkerLayer = L.layerGroup().addTo(_villageDashboardMap);
            refreshVillageMapMarkers();
        }

        // Bridge helper global untuk membuka modal tambah aset dengan koordinat titik terpilih
        window.__openCreateAssetAtPoint = function(lat, lng) {
            const mapEl = document.getElementById('referenceVillageMap');
            if (!mapEl) return;
            const wireRoot = mapEl.closest('[wire\\:id]');
            if (wireRoot && typeof Livewire !== 'undefined') {
                const comp = Livewire.find(wireRoot.getAttribute('wire:id'));
                if (comp) {
                    comp.openCreateAssetModalWithCoords(lat, lng);
                }
            }
        };

        // Dengarkan event dari Livewire saat aset baru disimpan
        document.addEventListener('livewire:initialized', function() {
            Livewire.on('asset-saved', function() {
                setTimeout(refreshVillageMapMarkers, 300);
            });
            Livewire.on('boundary-cleared', function() {
                if (typeof _existingBoundaryPolygon !== 'undefined' && _existingBoundaryPolygon && _boundaryMap) {
                    _boundaryMap.removeLayer(_existingBoundaryPolygon);
                    _existingBoundaryPolygon = null;
                }
            });
        });

        // Global boundary editor map state
        let _boundaryMap = null;
        let _drawnPoints = [];
        let _drawnMarkers = [];
        let _drawnPolyline = null;
        let _existingBoundaryPolygon = null;
        let _isDrawingBoundary = false;

        function initBoundaryEditor() {
            const container = document.getElementById('boundaryEditorMap');
            if (!container || typeof L === 'undefined') return;

            if (container._leaflet_id) {
                return;
            }

            const lat = parseFloat(container.dataset.lat || '-7.1350');
            const lng = parseFloat(container.dataset.lng || '112.6020');

            _boundaryMap = L.map('boundaryEditorMap', {
                zoomControl: true,
                attributionControl: false
            }).setView([lat, lng], 15);

            const satellite = L.tileLayer('https://mt1.google.com/vt/lyrs=y&x={x}&y={y}&z={z}', {
                maxZoom: 20,
                subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
            }).addTo(_boundaryMap);

            const streetMap = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19
            });

            L.control.layers({
                'Citra Satelit': satellite,
                'Peta Standar': streetMap
            }, null, { position: 'topright' }).addTo(_boundaryMap);

            // Load existing boundary
            let existingCoords = [];
            try {
                existingCoords = JSON.parse(container.dataset.boundary || '[]');
            } catch(e) {
                existingCoords = [];
            }

            function updateDisplay(coords) {
                const countEl = document.getElementById('pointCount');
                const displayEl = document.getElementById('coordsDisplay');
                const inputEl = document.getElementById('boundaryInput');

                if (countEl) countEl.textContent = coords.length;
                if (displayEl) displayEl.textContent = JSON.stringify(coords, null, 2);
                if (inputEl) {
                    inputEl.value = JSON.stringify(coords);
                    inputEl.dispatchEvent(new Event('input'));
                }
            }

            if (existingCoords.length >= 3) {
                _existingBoundaryPolygon = L.polygon(existingCoords, {
                    color: '#0284c7',
                    weight: 2,
                    fillColor: '#0284c7',
                    fillOpacity: 0.12,
                    dashArray: '4, 4'
                }).addTo(_boundaryMap);

                _boundaryMap.fitBounds(_existingBoundaryPolygon.getBounds(), { padding: [30, 30] });
                updateDisplay(existingCoords);
            }

            // Click event for drawing
            _boundaryMap.on('click', function(e) {
                if (!_isDrawingBoundary) return;

                const lat = parseFloat(e.latlng.lat.toFixed(6));
                const lng = parseFloat(e.latlng.lng.toFixed(6));

                // Jika sudah ada minimal 3 titik dan klik dekat titik pertama -> tutup polygon
                if (_drawnPoints.length >= 3) {
                    const first = _drawnPoints[0];
                    const dist = Math.hypot(lat - first[0], lng - first[1]);
                    if (dist < 0.0006) {
                        finishBoundaryDrawing();
                        return;
                    }
                }

                _drawnPoints.push([lat, lng]);

                const isFirst = _drawnPoints.length === 1;
                const dotIcon = L.divIcon({
                    className: 'boundary-dot',
                    html: '<div style="background-color: ' + (isFirst ? '#0284c7' : '#0f172a') + '; width: 12px; height: 12px; border-radius: 50%; border: 2px solid white; box-shadow: 0 1px 4px rgba(0,0,0,0.5); cursor: pointer;"></div>',
                    iconSize: [12, 12],
                    iconAnchor: [6, 6]
                });

                const marker = L.marker([lat, lng], { icon: dotIcon }).addTo(_boundaryMap);
                if (isFirst) {
                    marker.bindTooltip('Titik Awal (Klik untuk menutup polygon)', { permanent: true, direction: 'top' });
                    marker.on('click', function() {
                        if (_drawnPoints.length >= 3) {
                            finishBoundaryDrawing();
                        }
                    });
                }
                _drawnMarkers.push(marker);

                if (_drawnPolyline) _boundaryMap.removeLayer(_drawnPolyline);
                _drawnPolyline = L.polyline(_drawnPoints, {
                    color: '#0284c7',
                    weight: 2,
                    dashArray: '4, 4'
                }).addTo(_boundaryMap);

                updateDisplay(_drawnPoints);
            });

            function finishBoundaryDrawing() {
                _isDrawingBoundary = false;
                const hint = document.getElementById('drawHint');
                if (hint) hint.classList.add('hidden');
                _boundaryMap.getContainer().style.cursor = '';

                _drawnMarkers.forEach(m => _boundaryMap.removeLayer(m));
                _drawnMarkers = [];
                if (_drawnPolyline) {
                    _boundaryMap.removeLayer(_drawnPolyline);
                    _drawnPolyline = null;
                }

                if (_existingBoundaryPolygon) _boundaryMap.removeLayer(_existingBoundaryPolygon);
                _existingBoundaryPolygon = L.polygon(_drawnPoints, {
                    color: '#0284c7',
                    weight: 2,
                    fillColor: '#0284c7',
                    fillOpacity: 0.12,
                    dashArray: '4, 4'
                }).addTo(_boundaryMap);

                updateDisplay(_drawnPoints);

                L.popup()
                    .setLatLng(_drawnPoints[0])
                    .setContent('<div style="font-family:sans-serif;font-size:12px;"><strong>Batas Polygon Terbentuk!</strong><p style="margin-top:2px;color:#64748b;font-size:11px;">' + _drawnPoints.length + ' titik sudut tercatat. Klik tombol <strong>Simpan Batas ke Database</strong> untuk menerapkan.</p></div>')
                    .openOn(_boundaryMap);
            }

            const btnDraw = document.getElementById('btnDraw');
            if (btnDraw) {
                btnDraw.onclick = function() {
                    _isDrawingBoundary = true;
                    _drawnPoints = [];
                    _drawnMarkers.forEach(m => _boundaryMap.removeLayer(m));
                    _drawnMarkers = [];
                    if (_drawnPolyline) { _boundaryMap.removeLayer(_drawnPolyline); _drawnPolyline = null; }
                    if (_existingBoundaryPolygon) { _boundaryMap.removeLayer(_existingBoundaryPolygon); _existingBoundaryPolygon = null; }

                    updateDisplay([]);
                    const hint = document.getElementById('drawHint');
                    if (hint) hint.classList.remove('hidden');
                    _boundaryMap.getContainer().style.cursor = 'crosshair';
                };
            }

            const btnSave = document.getElementById('btnSave');
            if (btnSave) {
                btnSave.onclick = function() {
                    // Jika masih dalam proses gambar tapi sudah klik minimal 3 titik, otomatis tutup polygon
                    if (_isDrawingBoundary && _drawnPoints.length >= 3) {
                        finishBoundaryDrawing();
                    }

                    // Ambil koordinat yang ada
                    let coordsToSave = [];
                    if (_drawnPoints && _drawnPoints.length >= 3) {
                        coordsToSave = _drawnPoints;
                    } else if (_existingBoundaryPolygon) {
                        const raw = _existingBoundaryPolygon.getLatLngs();
                        const latlngs = Array.isArray(raw[0]) ? raw[0] : raw;
                        coordsToSave = latlngs.map(ll => [
                            parseFloat(ll.lat.toFixed(6)),
                            parseFloat(ll.lng.toFixed(6))
                        ]);
                    }

                    if (!coordsToSave || coordsToSave.length < 3) {
                        alert('Silakan klik minimal 3 titik pada peta untuk membuat batas wilayah desa sebelum menyimpan.');
                        return;
                    }

                    const btnSaveText = document.getElementById('btnSaveText');
                    if (btnSaveText) btnSaveText.textContent = 'Menyimpan...';

                    // Kirim langsung ke Livewire component
                    Livewire.dispatch('save-boundary-coords', { coordsJson: JSON.stringify(coordsToSave) });
                };
            }
        }

        // Livewire event listeners for boundary editor
        document.addEventListener('livewire:initialized', function() {
            Livewire.on('boundary-saved', function() {
                const btnSaveText = document.getElementById('btnSaveText');
                if (btnSaveText) btnSaveText.textContent = 'Simpan Batas ke Database';
            });

            Livewire.on('village-switched', function(event) {
                const data = Array.isArray(event) ? event[0] : event;
                if (!_boundaryMap || !data) return;

                // Reset drawing state
                _isDrawingBoundary = false;
                _drawnPoints = [];
                _drawnMarkers.forEach(m => _boundaryMap.removeLayer(m));
                _drawnMarkers = [];
                if (_drawnPolyline) { _boundaryMap.removeLayer(_drawnPolyline); _drawnPolyline = null; }
                if (_existingBoundaryPolygon) { _boundaryMap.removeLayer(_existingBoundaryPolygon); _existingBoundaryPolygon = null; }

                const hint = document.getElementById('drawHint');
                if (hint) hint.classList.add('hidden');
                _boundaryMap.getContainer().style.cursor = '';

                // Render boundary desa yang dipilih jika ada
                if (data.boundary && data.boundary.length >= 3) {
                    _existingBoundaryPolygon = L.polygon(data.boundary, {
                        color: '#0284c7',
                        weight: 2,
                        fillColor: '#0284c7',
                        fillOpacity: 0.12,
                        dashArray: '4, 4'
                    }).addTo(_boundaryMap);

                    _boundaryMap.fitBounds(_existingBoundaryPolygon.getBounds(), { padding: [30, 30] });
                } else {
                    _boundaryMap.setView([data.lat, data.lng], 15);
                }

                // Update info text
                const countEl = document.getElementById('pointCount');
                const displayEl = document.getElementById('coordsDisplay');
                if (countEl) countEl.textContent = (data.boundary ? data.boundary.length : 0);
                if (displayEl) displayEl.textContent = JSON.stringify(data.boundary || [], null, 2);
            });
        });

        // Alpine component untuk interaktif picker lokasi aset di dalam modal
        function registerModalAssetPicker() {
            if (typeof Alpine === 'undefined') return;
            Alpine.data('modalAssetPicker', function(config) {
                return {
                    pickerMap: null,
                    pickerMarker: null,
                    lat: config.lat,
                    lng: config.lng,
                    init: function() {
                        const self = this;
                        this.$nextTick(function() {
                            setTimeout(function() {
                                const el = self.$refs.modalMapContainer;
                                if (!el || typeof L === 'undefined') return;
                                if (self.pickerMap) {
                                    self.pickerMap.invalidateSize();
                                    return;
                                }

                                const initLat = parseFloat(self.lat) || -7.1350;
                                const initLng = parseFloat(self.lng) || 112.6020;

                                self.pickerMap = L.map(el, {
                                    zoomControl: true,
                                    attributionControl: false
                                }).setView([initLat, initLng], 16);

                                L.tileLayer('https://mt1.google.com/vt/lyrs=y&x={x}&y={y}&z={z}', {
                                    maxZoom: 20,
                                    subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
                                }).addTo(self.pickerMap);

                                const streetMap = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 });
                                L.control.layers({ 'Satelit': L.tileLayer('https://mt1.google.com/vt/lyrs=y&x={x}&y={y}&z={z}', { maxZoom: 20 }), 'Peta Jalan': streetMap }, null, { position: 'topright' }).addTo(self.pickerMap);

                                const pinIcon = L.divIcon({
                                    className: 'custom-pin-marker',
                                    html: '<div style="width:26px;height:26px;border-radius:50%;background:#008080;border:3px solid white;box-shadow:0 2px 8px rgba(0,0,0,0.5);display:flex;align-items:center;justify-content:center;font-size:14px;cursor:grab;">📍</div>',
                                    iconSize: [26, 26],
                                    iconAnchor: [13, 26]
                                });

                                self.pickerMarker = L.marker([initLat, initLng], {
                                    draggable: true,
                                    icon: pinIcon
                                }).addTo(self.pickerMap);

                                function updatePos(newLat, newLng) {
                                    self.lat = parseFloat(newLat.toFixed(6));
                                    self.lng = parseFloat(newLng.toFixed(6));
                                    if (self.$wire) {
                                        self.$wire.setLocation(self.lat, self.lng);
                                    }
                                }

                                self.pickerMarker.on('dragend', function(e) {
                                    const pos = self.pickerMarker.getLatLng();
                                    updatePos(pos.lat, pos.lng);
                                });

                                self.pickerMap.on('click', function(e) {
                                    self.pickerMarker.setLatLng(e.latlng);
                                    updatePos(e.latlng.lat, e.latlng.lng);
                                });
                            }, 250);
                        });
                    },
                    useGPS: function() {
                        const self = this;
                        if (navigator.geolocation) {
                            navigator.geolocation.getCurrentPosition(function(pos) {
                                const newLat = pos.coords.latitude;
                                const newLng = pos.coords.longitude;
                                if (self.pickerMap && self.pickerMarker) {
                                    self.pickerMap.setView([newLat, newLng], 17);
                                    self.pickerMarker.setLatLng([newLat, newLng]);
                                }
                                self.lat = parseFloat(newLat.toFixed(6));
                                self.lng = parseFloat(newLng.toFixed(6));
                                if (self.$wire) {
                                    self.$wire.setLocation(self.lat, self.lng);
                                }
                            });
                        } else {
                            alert('GPS tidak didukung oleh perangkat Anda.');
                        }
                    }
                };
            });
        }

        // Re-initialize Lucide icons after every Livewire DOM update (Livewire 3)
        document.addEventListener('livewire:navigated', () => {
            if (typeof lucide !== 'undefined') lucide.createIcons();
        });
        document.addEventListener('livewire:update', () => {
            if (typeof lucide !== 'undefined') lucide.createIcons();
        });
        document.addEventListener('livewire:updated', () => {
            if (typeof lucide !== 'undefined') lucide.createIcons();
        });
    </script>

    <!-- Global Floating AI Chatbot with GPS & Animated Reporting Flow -->
    <livewire:ai-suggestion-chatbot />
</body>
</html>

