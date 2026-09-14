<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

    <!-- Profile Header Banner -->
    <div class="bg-white rounded-2xl p-6 sm:p-7 border border-slate-200 shadow-xs flex flex-col sm:flex-row items-center sm:items-start gap-6">
        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-20 h-20 rounded-2xl object-cover ring-2 ring-teal-600/30 shrink-0" />
        
        <div class="space-y-2 text-center sm:text-left flex-1">
            <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2">
                <h1 class="font-heading font-bold text-2xl text-slate-900">{{ $user->name }}</h1>
                <span class="px-2.5 py-0.5 rounded-md text-xs font-semibold bg-teal-50 text-teal-800 border border-teal-200">
                    {{ $user->role_title ?? 'Warga Aktif' }}
                </span>
            </div>
            <p class="text-xs text-slate-500">Desa Sukomulyo, Kecamatan Manyar, Gresik</p>

            <div class="pt-2 flex items-center justify-center sm:justify-start gap-6 text-xs text-slate-600">
                <div class="flex items-center gap-1.5">
                    <span class="font-extrabold text-slate-900 text-lg">{{ $reports->count() }}</span>
                    <span class="text-slate-500">laporan aset</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="font-extrabold text-slate-900 text-lg">{{ $ideas->count() }}</span>
                    <span class="text-slate-500">ide gagasan</span>
                </div>
            </div>
        </div>

        <a href="{{ route('reports.create') }}" class="px-4 py-2.5 rounded-xl bg-teal-700 hover:bg-teal-800 text-white text-xs font-bold shadow-xs shrink-0 flex items-center gap-1.5 transition-colors">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>+ Lapor Aset Baru</span>
        </a>
    </div>

    <!-- User Activities Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        <!-- My Asset Reports -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="font-heading font-bold text-base text-slate-900">Riwayat Laporan Saya ({{ $reports->count() }})</h3>
                <a href="{{ route('reports.create') }}" class="text-xs text-teal-700 font-bold hover:underline">+ Lapor</a>
            </div>
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
                    <p class="text-xs text-slate-400 text-center py-6">Belum ada laporan aset yang dikirim.</p>
                @endforelse
            </div>
        </div>

        <!-- My Proposed Ideas -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="font-heading font-bold text-base text-slate-900">Usulan Gagasan Pemanfaatan ({{ $ideas->count() }})</h3>
                <a href="{{ route('map') }}" class="text-xs text-teal-700 font-bold hover:underline">Peta Aset</a>
            </div>
            <div class="space-y-3">
                @forelse($ideas as $idea)
                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/80 space-y-1.5 text-xs">
                        <div class="flex items-center justify-between gap-2">
                            <span class="font-bold text-slate-900 line-clamp-1">{{ $idea->title }}</span>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-teal-100 text-teal-800 shrink-0">
                                {{ $idea->category }}
                            </span>
                        </div>
                        <p class="text-slate-600 text-[11px] line-clamp-2 leading-relaxed">{{ $idea->description }}</p>
                        <div class="flex items-center justify-between pt-1 text-[10px] text-slate-400">
                            <span>Aset: {{ $idea->asset ? $idea->asset->name : 'Umum' }}</span>
                            <span>{{ $idea->votes_count ?? 0 }} Dukungan</span>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 text-center py-6">Belum ada usulan gagasan pemanfaatan.</p>
                @endforelse
            </div>
        </div>

    </div>

</div>
