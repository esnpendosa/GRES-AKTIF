<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

    <!-- Profile Header Banner -->
    <div class="bg-white rounded-2xl p-6 sm:p-7 border border-slate-200 shadow-xs flex flex-col sm:flex-row items-center sm:items-start gap-6">
        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-20 h-20 rounded-2xl object-cover ring-2 ring-teal-600/30 shrink-0" />
        
        <div class="space-y-2 text-center sm:text-left flex-1">
            <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2">
                <h1 class="font-heading font-bold text-2xl text-slate-900">{{ $user->name }}</h1>
                <span class="px-2.5 py-0.5 rounded-md text-xs font-semibold bg-teal-50 text-teal-800 border border-teal-200">
                    {{ $user->reputation_level }}
                </span>
            </div>
            <p class="text-xs text-slate-500">{{ $user->role_title }} &bull; Desa Sukomulyo, Kecamatan Manyar, Gresik</p>

            <div class="pt-2 flex items-center justify-center sm:justify-start gap-5 text-xs text-slate-600">
                <div>
                    <span class="font-extrabold text-teal-700 text-base">{{ $user->points }}</span> <span class="text-slate-500">pts total</span>
                </div>
                <div>
                    <span class="font-extrabold text-slate-900 text-base">{{ $reports->count() }}</span> <span class="text-slate-500">laporan</span>
                </div>
                <div>
                    <span class="font-extrabold text-slate-900 text-base">{{ $ideas->count() }}</span> <span class="text-slate-500">ide gagasan</span>
                </div>
            </div>
        </div>

        <a href="{{ route('reports.create') }}" class="px-4 py-2.5 rounded-xl bg-teal-700 hover:bg-teal-800 text-white text-xs font-bold shadow-xs shrink-0 flex items-center gap-1.5 transition-colors">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>+ Lapor Aset Baru</span>
        </a>
    </div>

    <!-- Gamification Badges Section -->
    <div class="bg-white rounded-2xl p-6 sm:p-7 border border-slate-200 shadow-xs space-y-4">
        <div>
            <h3 class="font-heading font-bold text-base text-slate-900">Lencana Kontribusi Warga (Badges)</h3>
            <p class="text-xs text-slate-500">Penghargaan atas partisipasi aktif dalam pemetaan dan gagasan re-aktivasi aset daerah.</p>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 pt-1">
            @foreach($allBadges as $badge)
                @php
                    $isUnlocked = $userBadges->contains('id', $badge->id);
                @endphp
                <div class="p-4 rounded-xl border text-center space-y-2 {{ $isUnlocked ? 'border-teal-300 bg-teal-50/50' : 'border-slate-200 bg-slate-50/50 opacity-60' }}">
                    <div class="w-12 h-12 rounded-xl mx-auto flex items-center justify-center text-lg {{ $isUnlocked ? 'bg-teal-700 text-white' : 'bg-slate-200 text-slate-500' }}">
                        <i data-lucide="{{ $badge->icon ?? 'award' }}" class="w-5 h-5"></i>
                    </div>
                    <h4 class="font-bold text-xs text-slate-900">{{ $badge->name }}</h4>
                    <p class="text-[10px] text-slate-500 leading-snug">{{ $badge->description }}</p>
                    <span class="inline-block text-[10px] font-bold px-2 py-0.5 rounded {{ $isUnlocked ? 'bg-teal-100 text-teal-800' : 'bg-slate-200 text-slate-600' }}">
                        {{ $isUnlocked ? 'Aktif' : 'Terkunci (' . $badge->min_points . ' pts)' }}
                    </span>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Contribution History Tabs -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        <!-- My Asset Reports -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs space-y-4">
            <h3 class="font-heading font-bold text-base text-slate-900">Riwayat Laporan Saya ({{ $reports->count() }})</h3>
            <div class="space-y-3">
                @forelse($reports as $rep)
                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/80 space-y-1.5 text-xs">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-slate-900 line-clamp-1">{{ $rep->title }}</span>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded {{ $rep->status_badge_color === 'emerald' ? 'bg-teal-100 text-teal-800' : 'bg-amber-100 text-amber-800' }}">
                                {{ $rep->status_label }}
                            </span>
                        </div>
                        <p class="text-slate-500 text-[11px] line-clamp-1">{{ $rep->address ?? 'Manyar, Gresik' }} &bull; Usulan: {{ $rep->suggested_use }}</p>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 text-center py-4">Belum ada laporan aset yang dikirim.</p>
                @endforelse
            </div>
        </div>

        <!-- Points History Log -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs space-y-4">
            <h3 class="font-heading font-bold text-base text-slate-900">Aktivitas Poin Kontribusi</h3>
            <div class="space-y-2.5">
                @forelse($pointsHistory as $ph)
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between text-xs">
                        <div>
                            <p class="font-semibold text-slate-800">{{ $ph->reason }}</p>
                            <span class="text-[10px] text-slate-400">{{ $ph->created_at->diffForHumans() }}</span>
                        </div>
                        <span class="font-extrabold text-teal-700 bg-teal-50 px-2.5 py-0.5 rounded-md border border-teal-100">+{{ $ph->points }} pts</span>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 text-center py-4">Belum ada riwayat poin.</p>
                @endforelse
            </div>
        </div>

    </div>

</div>
