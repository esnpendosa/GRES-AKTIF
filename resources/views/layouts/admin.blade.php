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

    <!-- LEFT SIDEBAR (EXACT MATCH TO REFERENCE DESIGN) -->
    <aside class="w-60 flex flex-col flex-shrink-0 bg-white border-r border-slate-200/90 z-30 select-none">
        
        <!-- Top Logo (Kentongan AI Logo Mark) -->
        <div class="h-20 flex items-center justify-between px-5">
            <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                <!-- Modern Kentongan AI Icon -->
                <div class="relative flex items-center justify-center w-9 h-9 rounded-xl bg-slate-900 shadow-xs">
                    <svg viewBox="0 0 32 32" class="w-5 h-5" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="7" y="5" width="12" height="22" rx="3.5" stroke="#00c9a7" stroke-width="2.2" fill="#0f172a"/>
                        <rect x="12" y="9" width="2" height="14" rx="1" fill="#38bdf8"/>
                        <path d="M22 10C23.8 12.2 23.8 17.8 22 20" stroke="#38bdf8" stroke-width="2" stroke-linecap="round"/>
                        <path d="M25 7C28 10.5 28 19.5 25 23" stroke="#00c9a7" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </div>
                <div>
                    <span class="font-heading font-extrabold text-sm text-slate-900 tracking-tight block">KENTONGAN <span class="text-teal-700">AI</span></span>
                    <span class="text-[9px] text-slate-400 font-medium block -mt-1">Command Center</span>
                </div>
            </a>

            <!-- Demo Switcher dropdown trigger on mobile/desktop -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="p-1 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors" title="Switch Demo Role">
                    <i data-lucide="shield-check" class="w-4 h-4 text-teal-600"></i>
                </button>
                <div x-show="open" @click.outside="open = false" x-cloak class="absolute left-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-slate-200 py-1.5 z-50 text-xs">
                    <p class="px-3 py-1 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Demo Quick Switch</p>
                    <a href="{{ route('auth.demo', 'desa') }}" class="block px-3 py-1.5 hover:bg-slate-50 font-medium text-slate-700 {{ auth()->user()?->isVillageAdmin() ? 'text-teal-600 font-bold bg-teal-50' : '' }}">Desa Sukomulyo</a>
                    <a href="{{ route('auth.demo', 'kecamatan') }}" class="block px-3 py-1.5 hover:bg-slate-50 font-medium text-slate-700 {{ auth()->user()?->isDistrictAdmin() ? 'text-teal-600 font-bold bg-teal-50' : '' }}">Kecamatan Manyar</a>
                    <a href="{{ route('auth.demo', 'kabupaten') }}" class="block px-3 py-1.5 hover:bg-slate-50 font-medium text-slate-700 {{ auth()->user()?->isRegencyAdmin() ? 'text-teal-600 font-bold bg-teal-50' : '' }}">Bappeda Kabupaten</a>
                    <a href="{{ route('auth.demo', 'masyarakat') }}" class="block px-3 py-1.5 hover:bg-slate-50 font-medium text-slate-700">Masyarakat Umum</a>
                </div>
            </div>
        </div>

        <!-- Sidebar Navigation Menu Items (Exact Labels & Icons) -->
        <div class="flex-1 overflow-y-auto px-4 py-2 space-y-1 text-sm font-medium">
            
            <!-- 1. Dashboard (Active Pill State) -->
            <a 
                href="{{ auth()->user()?->isVillageAdmin() ? route('dashboard.village') : (auth()->user()?->isDistrictAdmin() ? route('dashboard.district') : route('dashboard.regency')) }}" 
                class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-semibold transition-all {{ request()->routeIs('dashboard.*') && !request()->routeIs('dashboard.projects') && !request()->routeIs('dashboard.verification') ? 'bg-[#008080] text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}"
            >
                <i data-lucide="bar-chart-2" class="w-4 h-4 shrink-0 {{ request()->routeIs('dashboard.*') && !request()->routeIs('dashboard.projects') && !request()->routeIs('dashboard.verification') ? 'text-white' : 'text-slate-500' }}"></i>
                <span>Dashboard</span>
            </a>

            <!-- 2. Calendar -->
            <a href="{{ route('dashboard.projects') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-lg text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition-colors">
                <i data-lucide="grid" class="w-4 h-4 shrink-0 text-slate-500"></i>
                <span>Calendar</span>
            </a>

            <!-- 3. Inspections (With turquoise badge 12) -->
            <a href="{{ route('dashboard.verification') }}" class="flex items-center justify-between px-3.5 py-2 rounded-lg text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition-colors">
                <div class="flex items-center gap-3">
                    <i data-lucide="search" class="w-4 h-4 shrink-0 text-slate-500"></i>
                    <span>Inspections</span>
                </div>
                <span class="w-5 h-5 rounded-full bg-[#00c9a7] text-white text-[11px] font-bold flex items-center justify-center">12</span>
            </a>

            <!-- 4. Jobs -->
            <a href="{{ route('dashboard.projects') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-lg text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition-colors">
                <i data-lucide="wrench" class="w-4 h-4 shrink-0 text-slate-500"></i>
                <span>Jobs</span>
            </a>

            <!-- 5. Defects (With coral badge 5) -->
            <a href="{{ route('dashboard.verification') }}" class="flex items-center justify-between px-3.5 py-2 rounded-lg text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition-colors">
                <div class="flex items-center gap-3">
                    <i data-lucide="alert-triangle" class="w-4 h-4 shrink-0 text-slate-500"></i>
                    <span>Defects</span>
                </div>
                <span class="w-5 h-5 rounded-full bg-[#ff5722] text-white text-[11px] font-bold flex items-center justify-center">5</span>
            </a>

            <!-- 6. Routines -->
            <a href="{{ route('dashboard.projects') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-lg text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition-colors">
                <i data-lucide="rotate-cw" class="w-4 h-4 shrink-0 text-slate-500"></i>
                <span>Routines</span>
            </a>

            <!-- 7. Assets -->
            <a href="{{ route('explore') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-lg text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition-colors">
                <i data-lucide="box" class="w-4 h-4 shrink-0 text-slate-500"></i>
                <span>Assets</span>
            </a>

            <!-- Laporan Desa -->
            <a href="{{ route('reports.villages') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-lg {{ request()->routeIs('reports.villages') ? 'bg-[#008080] text-white' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }} transition-colors">
                <i data-lucide="building-2" class="w-4 h-4 shrink-0 {{ request()->routeIs('reports.villages') ? 'text-white' : 'text-slate-500' }}"></i>
                <span>Laporan Desa</span>
            </a>

            <!-- 8. Maps -->
            <a href="{{ route('map') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-lg {{ request()->routeIs('map') ? 'bg-[#008080] text-white' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }} transition-colors">
                <i data-lucide="globe" class="w-4 h-4 shrink-0 {{ request()->routeIs('map') ? 'text-white' : 'text-slate-500' }}"></i>
                <span>Maps</span>
            </a>

            <!-- 9. Documents -->
            <a href="{{ route('dashboard.audit') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-lg text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition-colors">
                <i data-lucide="file-text" class="w-4 h-4 shrink-0 text-slate-500"></i>
                <span>Documents</span>
            </a>

            <!-- 10. Regulations -->
            <a href="{{ route('dashboard.consensus') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-lg text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition-colors">
                <i data-lucide="badge-check" class="w-4 h-4 shrink-0 text-slate-500"></i>
                <span>Regulations</span>
            </a>

            <!-- 11. Settings -->
            <a href="{{ route('profile') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-lg text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition-colors">
                <i data-lucide="settings" class="w-4 h-4 shrink-0 text-slate-500"></i>
                <span>Settings</span>
            </a>

        </div>

        <!-- Sidebar Footer (Provide Feedback, Feature Voting, User Profile Pill) -->
        <div class="p-4 border-t border-slate-100 space-y-3">
            
            <div class="space-y-1 px-1 text-xs text-slate-400 font-medium">
                <a href="{{ route('explore') }}" class="block hover:text-slate-600 transition-colors">Provide Feedback</a>
                <a href="{{ route('map') }}" class="block hover:text-slate-600 transition-colors">Feature Voting</a>
            </div>

            <!-- User Pill Card (Reference Match) -->
            <div class="flex items-center justify-between p-2 rounded-xl bg-[#80cbc4]/35 border border-[#80cbc4]/40">
                <div class="flex items-center gap-2.5 overflow-hidden">
                    <div class="w-8 h-8 rounded-full bg-[#008080] text-white flex items-center justify-center font-bold text-xs shrink-0">
                        <i data-lucide="user" class="w-4 h-4"></i>
                    </div>
                    <div class="truncate">
                        <p class="text-xs font-bold text-slate-900 truncate">{{ auth()->user()?->name ?? 'John Fagan' }}</p>
                        <p class="text-[10px] text-teal-800 font-medium truncate">{{ auth()->user()?->role_title ?? 'Administrator' }}</p>
                    </div>
                </div>

                @if(auth()->check())
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="p-1 text-slate-500 hover:text-rose-600 transition-colors" title="Logout">
                            <i data-lucide="log-out" class="w-3.5 h-3.5"></i>
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="p-1 text-teal-700 hover:text-teal-900" title="Login">
                        <i data-lucide="log-in" class="w-3.5 h-3.5"></i>
                    </a>
                @endif
            </div>

        </div>

    </aside>

    <!-- MAIN DASHBOARD CONTENT AREA -->
    <div class="flex-1 flex flex-col min-w-0 overflow-y-auto">
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
        });
        document.addEventListener('livewire:navigated', () => {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>
</body>
</html>
