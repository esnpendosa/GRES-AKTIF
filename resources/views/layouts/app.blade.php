<!DOCTYPE html>
<html lang="id" class="h-full bg-[#f8fafc]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'GRES-AKTIF' }} | Sistem Informasi Tata Kelola & Aktivasi Aset Desa Kab. Gresik</title>
    <meta name="description" content="Platform integrasi data spasial dan optimalisasi aset desa Pemerintah Kabupaten Gresik.">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">

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
        h1, h2, h3, h4, h5, h6 { font-family: 'Plus Jakarta Sans', sans-serif; }
        .leaflet-container { font-family: inherit; }
    </style>
    @livewireStyles
</head>
<body class="min-h-screen flex flex-col bg-[#f8fafc] text-slate-800 antialiased selection:bg-teal-600 selection:text-white pb-16 md:pb-0" x-data="{ mobileMenuOpen: false }">

    <!-- Official Government Sub-Header Bar (Clean Light / Subtle Gray) -->
    <div class="bg-slate-100 text-xs text-slate-600 py-1.5 px-4 border-b border-slate-200">
        <div class="max-w-7xl mx-auto flex items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <span class="font-bold text-slate-700">Pemerintah Kabupaten Gresik</span>
                <span class="text-slate-300">|</span>
                <span class="hidden sm:inline text-slate-500">Bappedalitbang & Dinas Pemberdayaan Masyarakat dan Desa</span>
            </div>

            <!-- Quick Role Switcher for Evaluation -->
            <div class="flex items-center gap-1.5 text-[11px]">
                <span class="text-slate-500 hidden md:inline">Mode Akses:</span>
                <a href="{{ route('auth.demo', 'masyarakat') }}" class="px-2 py-0.5 rounded border {{ auth()->check() && auth()->user()->isCommunity() ? 'bg-teal-700 text-white border-teal-700 font-bold' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50' }}">Warga</a>
                <a href="{{ route('auth.demo', 'desa') }}" class="px-2 py-0.5 rounded border {{ auth()->check() && auth()->user()->isVillageAdmin() ? 'bg-teal-700 text-white border-teal-700 font-bold' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50' }}">Pemdes</a>
                <a href="{{ route('auth.demo', 'kecamatan') }}" class="px-2 py-0.5 rounded border {{ auth()->check() && auth()->user()->isDistrictAdmin() ? 'bg-teal-700 text-white border-teal-700 font-bold' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50' }}">Kecamatan</a>
                <a href="{{ route('auth.demo', 'kabupaten') }}" class="px-2 py-0.5 rounded border {{ auth()->check() && (auth()->user()->isRegencyAdmin() || auth()->user()->isSuperAdmin()) ? 'bg-teal-700 text-white border-teal-700 font-bold' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50' }}">Bappeda</a>
            </div>
        </div>
    </div>

    <!-- Main Navigation Bar -->
    <header class="sticky top-0 z-40 bg-white border-b border-slate-200 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 gap-4">
                
                <!-- Brand Logo -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                        <!-- Stylized G+ Logo -->
                        <div class="relative flex items-center justify-center w-8 h-8">
                            <svg viewBox="0 0 40 40" class="w-8 h-8" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M26 12C23.5 9.5 19.5 9 16 11C11.5 13.5 9.5 18.5 11 23.5C12.5 28.5 17.5 31.5 22.5 30.5C25.5 30 28 28 29.5 25.5" stroke="#0f172a" stroke-width="3.5" stroke-linecap="round"/>
                                <path d="M29 11L35 11M32 8L32 14" stroke="#0284c7" stroke-width="3" stroke-linecap="round"/>
                            </svg>
                        </div>
                        <div>
                            <div class="flex items-center gap-1.5">
                                <span class="font-heading font-extrabold text-lg text-slate-900 tracking-tight">GRES-AKTIF</span>
                            </div>
                            <p class="text-[10px] text-slate-500 font-medium -mt-1 leading-none">Gresik Asset Activation Platform</p>
                        </div>
                    </a>
                </div>

                <!-- Desktop Navigation Menu -->
                <nav class="hidden md:flex items-center gap-1 text-xs font-semibold text-slate-600">
                    <a href="{{ route('home') }}" class="px-3.5 py-2 rounded-lg transition-colors {{ request()->routeIs('home') ? 'bg-slate-100 text-slate-900 font-bold' : 'hover:bg-slate-50 hover:text-slate-900' }}">
                        Beranda
                    </a>
                    <a href="{{ route('explore') }}" class="px-3.5 py-2 rounded-lg transition-colors {{ request()->routeIs('explore') ? 'bg-slate-100 text-slate-900 font-bold' : 'hover:bg-slate-50 hover:text-slate-900' }}">
                        Jelajahi Aset
                    </a>
                    <a href="{{ route('map') }}" class="px-3.5 py-2 rounded-lg transition-colors {{ request()->routeIs('map') ? 'bg-slate-100 text-slate-900 font-bold' : 'hover:bg-slate-50 hover:text-slate-900' }}">
                        Peta Spasial GIS
                    </a>
                    @if(auth()->check() && !auth()->user()->isCommunity())
                        <a href="{{ auth()->user()->isVillageAdmin() ? route('dashboard.village') : (auth()->user()->isDistrictAdmin() ? route('dashboard.district') : route('dashboard.regency')) }}" class="px-3.5 py-2 rounded-lg text-teal-700 bg-teal-50 hover:bg-teal-100 font-bold">
                            Command Center &rarr;
                        </a>
                    @endif
                </nav>

                <!-- Action Buttons -->
                <div class="flex items-center gap-2">
                    <a href="{{ route('map') }}?openIdea=1" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg bg-teal-50 hover:bg-teal-100 text-teal-800 text-xs font-bold border border-teal-200 transition-colors">
                        <i data-lucide="lightbulb" class="w-3.5 h-3.5 text-teal-700"></i>
                        <span>Usulkan Ide</span>
                    </a>

                    <a href="{{ route('reports.create') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg bg-[#008080] hover:bg-[#006666] text-white text-xs font-semibold shadow-xs transition-all">
                        <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                        <span>Laporkan Aset</span>
                    </a>

                    @if(auth()->check())
                        <div class="flex items-center gap-2">
                            <a href="{{ route('profile') }}" class="flex items-center gap-2 px-2.5 py-1.5 rounded-lg border border-slate-200 hover:bg-slate-50 text-xs font-medium text-slate-700">
                                <div class="w-6 h-6 rounded-full bg-slate-200 text-slate-700 flex items-center justify-center font-bold text-[10px]">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                                <span class="hidden sm:inline max-w-[100px] truncate">{{ auth()->user()->name }}</span>
                            </a>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-semibold transition-colors">
                            <i data-lucide="log-in" class="w-3.5 h-3.5"></i>
                            <span>Masuk</span>
                        </a>
                    @endif

                    <!-- Mobile Menu Button -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 text-slate-600 hover:bg-slate-100 rounded-lg">
                        <i data-lucide="menu" class="w-5 h-5"></i>
                    </button>
                </div>

            </div>
        </div>

        <!-- Mobile Drawer -->
        <div x-show="mobileMenuOpen" x-cloak class="md:hidden border-t border-slate-200 bg-white px-4 py-3 space-y-2 text-sm font-medium">
            <a href="{{ route('home') }}" class="block px-3 py-2 rounded-lg hover:bg-slate-50 text-slate-800">Beranda</a>
            <a href="{{ route('explore') }}" class="block px-3 py-2 rounded-lg hover:bg-slate-50 text-slate-800">Jelajahi Aset</a>
            <a href="{{ route('map') }}" class="block px-3 py-2 rounded-lg hover:bg-slate-50 text-slate-800">Peta Spasial GIS</a>
            <a href="{{ route('map') }}?openIdea=1" class="block px-3 py-2 rounded-lg bg-teal-50 text-teal-800 font-bold">💡 Usulkan Ide Warga</a>
            <a href="{{ route('reports.create') }}" class="block px-3 py-2 rounded-lg bg-slate-100 text-slate-800 font-bold">+ Laporkan Aset Baru</a>
        </div>
    </header>

    <!-- Main Page Content -->
    <main class="flex-1">
        @if(session('success'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center gap-3 text-xs font-medium">
                    <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600 shrink-0"></i>
                    <p>{{ session('success') }}</p>
                </div>
            </div>
        @endif

        {{ $slot ?? '' }}
        @yield('content')
    </main>

    <!-- Institutional Official Footer -->
    <footer class="bg-white border-t border-slate-200 text-slate-600 text-xs mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                
                <!-- Col 1: Institutional info -->
                <div class="md:col-span-2 space-y-3">
                    <div class="flex items-center gap-2">
                        <span class="font-heading font-bold text-base text-slate-900">GRES-AKTIF</span>
                        <span class="text-[10px] px-2 py-0.5 rounded bg-slate-100 text-slate-600 font-bold">Portal Resmi</span>
                    </div>
                    <p class="text-slate-500 leading-relaxed max-w-md">
                        Sistem Informasi Manajemen & Pemanfaatan Aset Desa Terpadu Pemerintah Kabupaten Gresik. Menghubungkan aspirasi masyarakat, transparansi desa, dan strategi pembangunan daerah.
                    </p>
                    <p class="text-[11px] text-slate-400">
                        Sekretariat Bappedalitbang &bull; Jl. Dr. Wahidin Sudirohusodo No. 245, Gresik
                    </p>
                </div>

                <!-- Col 2: Quick Links -->
                <div class="space-y-2">
                    <p class="font-bold text-slate-900 uppercase tracking-wider text-[10px]">Navigasi Utama</p>
                    <ul class="space-y-1.5 text-slate-500">
                        <li><a href="{{ route('home') }}" class="hover:text-teal-700">Beranda</a></li>
                        <li><a href="{{ route('explore') }}" class="hover:text-teal-700">Inventaris Aset Terbuka</a></li>
                        <li><a href="{{ route('map') }}" class="hover:text-teal-700">Peta Sebaran GIS</a></li>
                        <li><a href="{{ route('reports.create') }}" class="hover:text-teal-700">Formulir Laporan Warga</a></li>
                    </ul>
                </div>

                <!-- Col 3: Portal Pemerintahan -->
                <div class="space-y-2">
                    <p class="font-bold text-slate-900 uppercase tracking-wider text-[10px]">Akses Instansi</p>
                    <ul class="space-y-1.5 text-slate-500">
                        <li><a href="{{ route('auth.demo', 'desa') }}" class="hover:text-teal-700">Portal Pemerintah Desa</a></li>
                        <li><a href="{{ route('auth.demo', 'kecamatan') }}" class="hover:text-teal-700">Portal Kecamatan</a></li>
                        <li><a href="{{ route('auth.demo', 'kabupaten') }}" class="hover:text-teal-700">Executive Command Center (Bappeda)</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-teal-700">Login Administrator</a></li>
                    </ul>
                </div>

            </div>

            <div class="mt-8 pt-6 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3 text-[11px] text-slate-400">
                <p>&copy; {{ date('Y') }} Pemerintah Kabupaten Gresik. Hak Cipta Dilindungi.</p>
                <p>Platform Inovasi Tata Kelola Aset Berkelanjutan</p>
            </div>
        </div>
    </footer>

    @livewireScripts
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
        document.addEventListener('livewire:navigated', () => {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>
</body>
</html>
