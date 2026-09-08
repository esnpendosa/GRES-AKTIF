<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <span class="text-[11px] font-bold text-teal-600 uppercase tracking-wider">Eksekusi Lapangan</span>
            <h1 class="font-heading font-extrabold text-2xl text-slate-900 tracking-tight">Proyek Aktivasi Aset Desa</h1>
            <p class="text-xs text-slate-500">Monitoring realisasi fisik, anggaran APBDes/CSR, dan perkembangan operasional.</p>
        </div>

        <div class="flex items-center gap-2">
            <button class="px-3.5 py-2 rounded-xl bg-teal-600 text-white text-xs font-bold shadow-xs hover:bg-teal-700 flex items-center gap-1.5">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>+ Buat Proyek Baru</span>
            </button>
        </div>
    </div>

    <!-- Filter Pills -->
    <div class="flex items-center gap-2 overflow-x-auto pb-1 text-xs">
        <button wire:click="$set('statusFilter', 'all')" class="px-3 py-1.5 rounded-xl font-bold {{ $statusFilter === 'all' ? 'bg-slate-900 text-white' : 'bg-white border border-slate-200 text-slate-600' }}">Semua Proyek</button>
        <button wire:click="$set('statusFilter', 'in_progress')" class="px-3 py-1.5 rounded-xl font-bold {{ $statusFilter === 'in_progress' ? 'bg-teal-600 text-white' : 'bg-white border border-slate-200 text-slate-600' }}">Dalam Pelaksanaan</button>
        <button wire:click="$set('statusFilter', 'planning')" class="px-3 py-1.5 rounded-xl font-bold {{ $statusFilter === 'planning' ? 'bg-amber-600 text-white' : 'bg-white border border-slate-200 text-slate-600' }}">Perencanaan</button>
        <button wire:click="$set('statusFilter', 'completed')" class="px-3 py-1.5 rounded-xl font-bold {{ $statusFilter === 'completed' ? 'bg-emerald-600 text-white' : 'bg-white border border-slate-200 text-slate-600' }}">Selesai & Aktif</button>
    </div>

    <!-- Projects Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($projects as $p)
            <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm space-y-4 flex flex-col justify-between">
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-bold px-2.5 py-0.5 rounded-full {{ $p->status_badge_color === 'emerald' ? 'bg-emerald-100 text-emerald-800' : 'bg-teal-100 text-teal-800' }}">
                            {{ $p->status_label }}
                        </span>
                        <span class="text-xs text-slate-400 font-medium">BUMDes & Dana Desa</span>
                    </div>

                    <div>
                        <h3 class="font-heading font-bold text-base text-slate-900 leading-snug">{{ $p->title }}</h3>
                        <p class="text-xs text-teal-700 font-semibold mt-0.5">{{ $p->asset ? $p->asset->name : 'Aset Desa' }} &bull; {{ $p->asset && $p->asset->village ? $p->asset->village->name : 'Gresik' }}</p>
                        <p class="text-xs text-slate-500 mt-2 leading-relaxed">{{ $p->objective }}</p>
                    </div>

                    <!-- Progress Bar -->
                    <div class="space-y-1.5 pt-2">
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-semibold text-slate-600">Progres Realisasi</span>
                            <span class="font-extrabold text-teal-700">{{ $p->progress_percentage }}%</span>
                        </div>
                        <div class="w-full bg-slate-100 h-2.5 rounded-full overflow-hidden">
                            <div class="bg-gradient-to-r from-teal-500 to-teal-700 h-full rounded-full" style="width: {{ $p->progress_percentage }}%"></div>
                        </div>
                    </div>

                    <!-- Budget & Dept Details -->
                    <div class="grid grid-cols-2 gap-2 pt-2 border-t border-slate-100 text-xs">
                        <div class="p-2.5 rounded-xl bg-slate-50">
                            <span class="text-[10px] text-slate-400 block">Estimasi Anggaran</span>
                            <span class="font-bold text-slate-900">Rp {{ number_format($p->budget_estimate / 1000000, 0) }} Juta</span>
                        </div>
                        <div class="p-2.5 rounded-xl bg-slate-50">
                            <span class="text-[10px] text-slate-400 block">Target Selesai</span>
                            <span class="font-bold text-slate-900">{{ $p->target_completion ? $p->target_completion->format('M Y') : '3 Bulan' }}</span>
                        </div>
                    </div>
                </div>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                    <span class="text-slate-400">{{ $p->responsible_department }}</span>
                    @if($p->asset)
                        <a href="{{ route('assets.show', $p->asset->slug ?? $p->asset->id) }}" class="font-bold text-teal-600 hover:text-teal-700">
                            Lihat Aset &rarr;
                        </a>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

</div>
