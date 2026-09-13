<div wire:poll.20s class="bg-gradient-to-r from-teal-900 via-slate-900 to-teal-950 text-white text-xs border-b border-teal-800/60 shadow-inner overflow-hidden" x-data="{ currentIndex: 0, itemsCount: {{ count($feedItems) }} }" x-init="if (itemsCount > 1) { setInterval(() => { currentIndex = (currentIndex + 1) % itemsCount }, 6000) }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2 flex items-center justify-between gap-4">
        
        <!-- Live Badge -->
        <div class="flex items-center gap-2 shrink-0">
            <span class="flex h-2 w-2 relative">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-teal-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-teal-500"></span>
            </span>
            <span class="font-bold text-[11px] uppercase tracking-wider text-teal-300 flex items-center gap-1">
                <span>Pemberitahuan Terkini</span>
            </span>
            <span class="text-teal-700 hidden sm:inline">|</span>
        </div>

        <!-- Ticker Items Carousel -->
        <div class="flex-1 overflow-hidden relative h-5">
            @forelse($feedItems as $index => $item)
                <div 
                    x-show="currentIndex === {{ $index }}" 
                    x-transition:enter="transition ease-out duration-300 transform" 
                    x-transition:enter-start="opacity-0 translate-y-3" 
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-200 transform absolute inset-0"
                    x-transition:leave-start="opacity-100 translate-y-0" 
                    x-transition:leave-end="opacity-0 -translate-y-3"
                    class="flex items-center gap-2 text-xs truncate"
                >
                    <span class="px-1.5 py-0.5 rounded text-[9.5px] font-bold {{ $item['type'] === 'report' ? 'bg-amber-500/20 text-amber-300 border border-amber-500/40' : ($item['type'] === 'asset' ? 'bg-teal-500/20 text-teal-300 border border-teal-500/40' : 'bg-sky-500/20 text-sky-300 border border-sky-500/40') }}">
                        {{ $item['title'] }}
                    </span>
                    <a href="{{ $item['link'] }}" class="hover:text-teal-300 transition-colors truncate font-medium text-slate-200">
                        {{ $item['message'] }}
                    </a>
                    <span class="text-[10px] text-slate-400 shrink-0">({{ $item['time'] }})</span>
                </div>
            @empty
                <div class="text-slate-400 text-xs">Belum ada pembaruan aktivitas terkini.</div>
            @endforelse
        </div>

        <!-- Dismiss / Action Button -->
        <div class="flex items-center gap-2 shrink-0">
            <a href="{{ route('reports.villages') }}" class="hidden sm:inline-flex items-center gap-1 text-[11px] text-teal-300 hover:text-teal-200 font-semibold underline underline-offset-2">
                Rekap Desa &rarr;
            </a>
            <button wire:click="dismiss" class="text-slate-400 hover:text-white transition-colors p-0.5" title="Tutup pemberitahuan">
                <i data-lucide="x" class="w-3.5 h-3.5"></i>
            </button>
        </div>

    </div>
</div>
