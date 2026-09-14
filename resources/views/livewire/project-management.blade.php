<div class="space-y-6">

    <!-- Toast Notification -->
    @if($successMessage)
        <div class="p-4 rounded-2xl bg-teal-900 text-white flex items-center justify-between gap-3 shadow-lg border border-teal-500/40 animate-fade-in">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-teal-500/20 text-teal-300 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-teal-200">Berhasil Disimpan</p>
                    <p class="text-sm font-semibold text-white">{{ $successMessage }}</p>
                </div>
            </div>
            <button wire:click="dismissToast" class="p-1.5 rounded-lg text-teal-300 hover:text-white hover:bg-teal-800/60 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    @endif

    <!-- Page Header & Action Controls -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-teal-100 text-teal-800 border border-teal-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-teal-600 animate-pulse"></span>
                    Eksekusi Lapangan & Revitalisasi
                </span>
            </div>
            <h1 class="font-heading font-extrabold text-2xl sm:text-3xl text-slate-900 tracking-tight mt-1">Proyek Aktivasi Aset Desa</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Monitoring realisasi fisik, anggaran APBDes/CSR, dan perkembangan operasional di Kabupaten Gresik.</p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5">
            <!-- Search Input -->
            <div class="relative min-w-[220px] flex-1 sm:flex-initial">
                <input 
                    type="text" 
                    wire:model.live.debounce.300ms="search" 
                    placeholder="Cari proyek, aset, desa..." 
                    class="w-full pl-9 pr-3 py-2 text-xs rounded-xl bg-white border border-slate-200 shadow-xs focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-slate-800"
                />
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>

            <!-- Create Project Button -->
            <button 
                type="button"
                wire:click="openCreateModal"
                class="px-4 py-2 rounded-xl bg-teal-700 hover:bg-teal-800 text-white text-xs font-bold shadow-sm hover:shadow transition-all flex items-center gap-1.5 cursor-pointer shrink-0"
            >
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                <span>Buat Proyek Baru</span>
            </button>
        </div>
    </div>

    <!-- Overview Statistics Row -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3.5">
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs space-y-1">
            <span class="text-[11px] font-semibold text-slate-500 block">Total Proyek Terdaftar</span>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl font-extrabold text-slate-900">{{ $totalProjects }}</span>
                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-slate-100 text-slate-600">Agenda</span>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs space-y-1">
            <span class="text-[11px] font-semibold text-teal-700 block">Dalam Pelaksanaan</span>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl font-extrabold text-teal-700">{{ $inProgressCount }}</span>
                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-teal-50 text-teal-700 border border-teal-200">Fisik Aktif</span>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs space-y-1">
            <span class="text-[11px] font-semibold text-amber-700 block">Perencanaan & Musdes</span>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl font-extrabold text-amber-600">{{ $planningCount }}</span>
                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-amber-50 text-amber-700 border border-amber-200">Draft / RAB</span>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs space-y-1">
            <span class="text-[11px] font-semibold text-emerald-700 block">Selesai & Beroperasi</span>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl font-extrabold text-emerald-700">{{ $completedCount }}</span>
                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">PADes Berjalan</span>
            </div>
        </div>
    </div>

    <!-- Filter Tabs & Category Selection -->
    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 pb-3">
        <!-- Status Filter Pills -->
        <div class="flex items-center gap-1.5 overflow-x-auto [scrollbar-width:none] [-ms-overflow-style:none] [&::-webkit-scrollbar]:hidden text-xs">
            <button 
                type="button"
                wire:click="$set('statusFilter', 'all')" 
                class="px-3.5 py-1.5 rounded-xl font-bold transition-all {{ $statusFilter === 'all' ? 'bg-slate-900 text-white shadow-xs' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}"
            >
                Semua Proyek ({{ $totalProjects }})
            </button>
            <button 
                type="button"
                wire:click="$set('statusFilter', 'in_progress')" 
                class="px-3.5 py-1.5 rounded-xl font-bold transition-all {{ $statusFilter === 'in_progress' ? 'bg-teal-700 text-white shadow-xs' : 'bg-white border border-slate-200 text-slate-600 hover:bg-teal-50' }}"
            >
                Dalam Pelaksanaan ({{ $inProgressCount }})
            </button>
            <button 
                type="button"
                wire:click="$set('statusFilter', 'planning')" 
                class="px-3.5 py-1.5 rounded-xl font-bold transition-all {{ $statusFilter === 'planning' ? 'bg-amber-600 text-white shadow-xs' : 'bg-white border border-slate-200 text-slate-600 hover:bg-amber-50' }}"
            >
                Perencanaan ({{ $planningCount }})
            </button>
            <button 
                type="button"
                wire:click="$set('statusFilter', 'completed')" 
                class="px-3.5 py-1.5 rounded-xl font-bold transition-all {{ $statusFilter === 'completed' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-white border border-slate-200 text-slate-600 hover:bg-emerald-50' }}"
            >
                Selesai & Aktif ({{ $completedCount }})
            </button>
        </div>

        <!-- Total Budget Info Badge -->
        <div class="hidden sm:flex items-center gap-2 text-xs text-slate-600 bg-slate-100 px-3 py-1.5 rounded-xl font-semibold">
            <svg class="w-4 h-4 text-teal-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>Total Anggaran: <strong class="text-slate-900">Rp {{ number_format($totalBudget, 0, ',', '.') }}</strong></span>
        </div>
    </div>

    <!-- Projects Grid -->
    @if($projects->isEmpty())
        <div class="bg-white rounded-3xl p-12 text-center border border-slate-200 shadow-xs space-y-4 max-w-lg mx-auto">
            <div class="w-16 h-16 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center mx-auto">
                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            </div>
            <div class="space-y-1">
                <h3 class="font-heading font-bold text-lg text-slate-800">Belum Ada Proyek Sesuai Filter</h3>
                <p class="text-xs text-slate-500">Tidak ada proyek yang sesuai dengan kriteria pencarian atau status filter saat ini.</p>
            </div>
            <div class="flex items-center justify-center gap-2 pt-2">
                @if($statusFilter !== 'all' || !empty($search))
                    <button 
                        type="button" 
                        wire:click="$set('statusFilter', 'all'); $set('search', '')" 
                        class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50"
                    >
                        Reset Filter
                    </button>
                @endif
                <button 
                    type="button" 
                    wire:click="openCreateModal" 
                    class="px-4 py-2 rounded-xl bg-teal-700 text-white text-xs font-bold shadow-xs hover:bg-teal-800"
                >
                    + Daftarkan Proyek Baru
                </button>
            </div>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($projects as $p)
                <div class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm hover:shadow-md transition-shadow space-y-4 flex flex-col justify-between">
                    <div class="space-y-3.5">
                        
                        <!-- Top Meta Header -->
                        <div class="flex items-center justify-between gap-2">
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] font-extrabold px-2.5 py-0.5 rounded-full uppercase tracking-wider
                                    @if($p->status === 'completed') bg-emerald-100 text-emerald-800 border border-emerald-200
                                    @elseif($p->status === 'in_progress') bg-teal-100 text-teal-800 border border-teal-200
                                    @elseif($p->status === 'planning') bg-amber-100 text-amber-800 border border-amber-200
                                    @else bg-slate-100 text-slate-700
                                    @endif"
                                >
                                    {{ $p->status_label }}
                                </span>
                                <span class="text-[11px] font-semibold text-slate-500 px-2 py-0.5 rounded-lg bg-slate-50 border border-slate-100">
                                    {{ $p->category ?? 'UMKM' }}
                                </span>
                            </div>

                            <!-- Action Dropdown / Buttons -->
                            <div class="flex items-center gap-1">
                                <button 
                                    type="button"
                                    wire:click="openEditModal({{ $p->id }})" 
                                    title="Edit Informasi Proyek" 
                                    class="p-1.5 rounded-lg text-slate-400 hover:text-teal-700 hover:bg-teal-50 transition-colors cursor-pointer"
                                >
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                <button 
                                    type="button"
                                    wire:click="confirmDelete({{ $p->id }})" 
                                    title="Hapus Proyek" 
                                    class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors cursor-pointer"
                                >
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </div>

                        <!-- Title & Asset Location -->
                        <div>
                            <h3 class="font-heading font-bold text-lg text-slate-900 leading-snug">{{ $p->title }}</h3>
                            <div class="flex items-center gap-1.5 text-xs text-teal-800 font-semibold mt-1">
                                <svg class="w-3.5 h-3.5 text-teal-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span>{{ $p->asset ? $p->asset->name : 'Aset Desa' }}</span>
                                <span class="text-slate-300">&bull;</span>
                                <span class="text-slate-500 font-normal">
                                    {{ $p->asset && $p->asset->village ? $p->asset->village->name : 'Gresik' }}
                                    @if($p->asset && $p->asset->village && $p->asset->village->district)
                                        ({{ $p->asset->village->district->name }})
                                    @endif
                                </span>
                            </div>
                            <p class="text-xs text-slate-600 mt-2.5 leading-relaxed line-clamp-2">{{ $p->objective }}</p>
                        </div>

                        <!-- Real-time Progress Bar -->
                        <div class="space-y-1.5 pt-1">
                            <div class="flex items-center justify-between text-xs">
                                <span class="font-semibold text-slate-600 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-teal-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                    Progres Realisasi Fisik
                                </span>
                                <span class="font-extrabold text-sm {{ $p->progress_percentage >= 100 ? 'text-emerald-700' : ($p->progress_percentage >= 40 ? 'text-teal-700' : 'text-amber-600') }}">
                                    {{ $p->progress_percentage }}%
                                </span>
                            </div>
                            <div class="w-full bg-slate-100 h-2.5 rounded-full overflow-hidden p-0.5 border border-slate-200/60">
                                <div 
                                    class="h-full rounded-full transition-all duration-500 {{ $p->progress_percentage >= 100 ? 'bg-emerald-500' : ($p->progress_percentage >= 40 ? 'bg-gradient-to-r from-teal-500 to-teal-700' : 'bg-amber-500') }}" 
                                    style="width: {{ $p->progress_percentage }}%"
                                ></div>
                            </div>
                        </div>

                        <!-- Budget & Completion Target Details -->
                        <div class="grid grid-cols-2 gap-2 pt-1 text-xs">
                            <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                                <span class="text-[10px] text-slate-400 block font-medium">Estimasi Anggaran</span>
                                <span class="font-bold text-slate-900">
                                    Rp {{ number_format($p->budget_estimate / 1000000, 1, ',', '.') }} Juta
                                </span>
                                <span class="text-[10px] text-slate-500 block truncate mt-0.5">{{ $p->funding_source }}</span>
                            </div>
                            <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                                <span class="text-[10px] text-slate-400 block font-medium">Target Selesai</span>
                                <span class="font-bold text-slate-900">
                                    {{ $p->target_completion ? $p->target_completion->format('d M Y') : 'Dalam Proses' }}
                                </span>
                                <span class="text-[10px] text-slate-500 block truncate mt-0.5">{{ $p->responsible_department }}</span>
                            </div>
                        </div>

                        <!-- Latest Progress Note Snippet -->
                        @if($p->updates->isNotEmpty())
                            @php $latestUpdate = $p->updates->first(); @endphp
                            <div class="p-2.5 rounded-xl bg-teal-50/70 border border-teal-100 text-xs text-teal-900 space-y-0.5">
                                <div class="flex items-center justify-between text-[10px] text-teal-700 font-semibold">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3 h-3 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Catatan Milestone Terbaru ({{ $latestUpdate->created_at->diffForHumans() }})
                                    </span>
                                    <span class="font-bold text-teal-800">{{ $latestUpdate->progress_percentage }}%</span>
                                </div>
                                <p class="font-bold text-[11px] text-slate-900 truncate">{{ $latestUpdate->title }}</p>
                                @if($latestUpdate->notes)
                                    <p class="text-[10.5px] text-slate-600 line-clamp-1 italic">"{{ $latestUpdate->notes }}"</p>
                                @endif
                            </div>
                        @endif

                    </div>

                    <!-- Footer Action Buttons -->
                    <div class="pt-3 border-t border-slate-100 flex flex-wrap items-center justify-between gap-2 text-xs">
                        <div class="flex items-center gap-1.5">
                            <!-- Update Progress Button -->
                            <button 
                                type="button"
                                wire:click="openUpdateModal({{ $p->id }})"
                                class="px-3 py-1.5 rounded-xl bg-teal-700 hover:bg-teal-800 text-white font-bold shadow-xs flex items-center gap-1 cursor-pointer transition-colors"
                            >
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                <span>Update Progres</span>
                            </button>

                            <!-- Milestone History Button -->
                            <button 
                                type="button"
                                wire:click="openHistoryModal({{ $p->id }})"
                                class="px-3 py-1.5 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-700 font-semibold flex items-center gap-1 cursor-pointer transition-colors"
                            >
                                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>Riwayat ({{ $p->updates->count() }})</span>
                            </button>
                        </div>

                        @if($p->asset)
                            <a href="{{ route('assets.show', $p->asset->slug ?? $p->asset->id) }}" class="font-bold text-teal-700 hover:text-teal-900 flex items-center gap-1">
                                <span>Lihat Aset</span>
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- ============================================================ -->
    <!-- MODAL 1: BUAT / EDIT PROYEK BARU -->
    <!-- ============================================================ -->
    @if($showCreateModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs overflow-y-auto">
            <div class="bg-white rounded-3xl max-w-2xl w-full p-6 sm:p-7 shadow-2xl border border-slate-100 my-8 space-y-5 animate-scale-up">
                
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div>
                        <h3 class="font-heading font-bold text-lg text-slate-900">
                            {{ $isEditing ? 'Perbarui Proyek Aktivasi' : 'Daftarkan Proyek Aktivasi Baru' }}
                        </h3>
                        <p class="text-xs text-slate-500">Kaitkan aset desa tidur dengan rencana anggaran dan eksekusi fisik BUMDes/CSR.</p>
                    </div>
                    <button wire:click="$set('showCreateModal', false)" class="p-2 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors cursor-pointer">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form wire:submit="saveProject" class="space-y-4 text-xs">
                    
                    <!-- Asset Selection -->
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Pilih Aset Desa yang Diaktivasi <span class="text-rose-500">*</span></label>
                        <select wire:model="asset_id" class="w-full px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-teal-500 text-xs text-slate-800">
                            <option value="">-- Pilih Aset dari Database Desa --</option>
                            @foreach($availableAssets as $ast)
                                <option value="{{ $ast->id }}">
                                    {{ $ast->name }} &bull; {{ $ast->village ? $ast->village->name : 'Gresik' }} ({{ $ast->village && $ast->village->district ? $ast->village->district->name : '-' }})
                                </option>
                            @endforeach
                        </select>
                        @error('asset_id') <span class="text-rose-500 text-[11px]">{{ $message }}</span> @enderror
                    </div>

                    <!-- Project Title -->
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Judul Agenda / Proyek Aktivasi <span class="text-rose-500">*</span></label>
                        <input 
                            type="text" 
                            wire:model="title" 
                            placeholder="Contoh: Revitalisasi Sentra Pujasera & UMKM Sukomulyo" 
                            class="w-full px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-teal-500 text-xs text-slate-800 font-semibold"
                        />
                        @error('title') <span class="text-rose-500 text-[11px]">{{ $message }}</span> @enderror
                    </div>

                    <!-- Category & Funding Source Row -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Kategori Sektor Aktivasi</label>
                            <select wire:model="category" class="w-full px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-teal-500 text-xs text-slate-800">
                                <option value="UMKM">Sentra UMKM & Pujasera</option>
                                <option value="Pertanian & Pangan">Pertanian, Pangan & Tambak</option>
                                <option value="Pariwisata & Budaya">Pariwisata, Alam & Budaya</option>
                                <option value="Olahraga & Komunitas">Fasilitas Olahraga & Ruang Publik</option>
                                <option value="Pergudangan & Logistik">Pergudangan & Logistik Desa</option>
                                <option value="Pendidikan & Pelatihan">Pusat Edukasi & Pelatihan</option>
                            </select>
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Sumber Pendanaan Utama</label>
                            <select wire:model="funding_source" class="w-full px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-teal-500 text-xs text-slate-800">
                                <option value="BUMDes & Dana Desa">BUMDes & Dana Desa (APBDes)</option>
                                <option value="APBDes Murni">APBDes Murni</option>
                                <option value="APBD Kab. Gresik">APBD Kab. Gresik / Hibah Daerah</option>
                                <option value="CSR PT Petrokimia & JIIPE">CSR Perusahaan Kawasan (Petrokimia / JIIPE)</option>
                                <option value="CSR Semen Indonesia Group">CSR Semen Indonesia Group (SIG)</option>
                                <option value="Kemitraan Swasta / Bagi Hasil">Kemitraan Swasta / Bagi Hasil Usaha</option>
                            </select>
                        </div>
                    </div>

                    <!-- Objective -->
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Tujuan Aktivasi & Target Pemanfaatan <span class="text-rose-500">*</span></label>
                        <textarea 
                            wire:model="objective" 
                            rows="2" 
                            placeholder="Jelaskan tujuan aktivasi, misal menampung 20 pelaku UMKM lokal dan membuka 15 lapangan kerja baru." 
                            class="w-full px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-teal-500 text-xs text-slate-800"
                        ></textarea>
                        @error('objective') <span class="text-rose-500 text-[11px]">{{ $message }}</span> @enderror
                    </div>

                    <!-- Budget & Responsible Dept -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Estimasi Kebutuhan Anggaran (Rp) <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center font-bold text-slate-400">Rp</span>
                                <input 
                                    type="number" 
                                    wire:model="budget_estimate" 
                                    placeholder="85000000" 
                                    class="w-full pl-9 pr-3 py-2 rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-teal-500 text-xs text-slate-800 font-bold"
                                />
                            </div>
                            @error('budget_estimate') <span class="text-rose-500 text-[11px]">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Instansi / Penanggung Jawab</label>
                            <input 
                                type="text" 
                                wire:model="responsible_department" 
                                placeholder="Contoh: Pemerintah Desa Sukomulyo & BUMDes" 
                                class="w-full px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-teal-500 text-xs text-slate-800"
                            />
                        </div>
                    </div>

                    <!-- Dates & Status Row -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Tanggal Mulai</label>
                            <input type="date" wire:model="start_date" class="w-full px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-800" />
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Target Selesai</label>
                            <input type="date" wire:model="target_completion" class="w-full px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-800" />
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Status Proyek</label>
                            <select wire:model="status" class="w-full px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-800 font-semibold">
                                <option value="planning">Perencanaan (Planning)</option>
                                <option value="in_progress">Dalam Pelaksanaan (In Progress)</option>
                                <option value="completed">Selesai & Aktif (Completed)</option>
                                <option value="cancelled">Dibatalkan</option>
                            </select>
                        </div>
                    </div>

                    <!-- Progress Percentage -->
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label class="font-bold text-slate-700">Progres Awal Realisasi: <span class="text-teal-700 font-extrabold">{{ $progress_percentage }}%</span></label>
                        </div>
                        <input 
                            type="range" 
                            min="0" 
                            max="100" 
                            step="5" 
                            wire:model.live="progress_percentage" 
                            class="w-full accent-teal-600 cursor-pointer"
                        />
                    </div>

                    <!-- Modal Actions -->
                    <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-2">
                        <button 
                            type="button" 
                            wire:click="$set('showCreateModal', false)" 
                            class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 cursor-pointer"
                        >
                            Batal
                        </button>
                        <button 
                            type="submit" 
                            class="px-5 py-2 rounded-xl bg-teal-700 hover:bg-teal-800 text-white text-xs font-bold shadow-xs cursor-pointer flex items-center gap-1.5"
                        >
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            <span>{{ $isEditing ? 'Simpan Perubahan' : 'Simpan & Daftarkan Proyek' }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- ============================================================ -->
    <!-- MODAL 2: UPDATE PROGRES & REALISASI LAPANGAN -->
    <!-- ============================================================ -->
    @if($showUpdateModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs overflow-y-auto">
            <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-7 shadow-2xl border border-slate-100 my-8 space-y-5 animate-scale-up">
                
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div>
                        <span class="text-[10px] font-extrabold uppercase tracking-wider text-teal-700 bg-teal-50 px-2 py-0.5 rounded-md">Update Realisasi Fisik</span>
                        <h3 class="font-heading font-bold text-lg text-slate-900 mt-1">Catat Progres Perkembangan</h3>
                    </div>
                    <button wire:click="$set('showUpdateModal', false)" class="p-2 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors cursor-pointer">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form wire:submit="saveProgressUpdate" class="space-y-4 text-xs">
                    
                    <!-- Interactive Progress Slider -->
                    <div class="p-4 rounded-2xl bg-teal-50/60 border border-teal-100 space-y-2.5">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-teal-900">Tingkat Capaian Fisik</span>
                            <span class="text-2xl font-extrabold text-teal-700">{{ $update_progress_percentage }}%</span>
                        </div>
                        <input 
                            type="range" 
                            min="0" 
                            max="100" 
                            step="5" 
                            wire:model.live="update_progress_percentage" 
                            class="w-full accent-teal-600 h-2 bg-teal-200 rounded-lg cursor-pointer"
                        />
                        <div class="flex items-center justify-between gap-1 text-[10px] text-teal-800">
                            <button type="button" wire:click="$set('update_progress_percentage', 25)" class="px-2 py-0.5 rounded bg-white hover:bg-teal-100 font-semibold border border-teal-200">25% Pondasi</button>
                            <button type="button" wire:click="$set('update_progress_percentage', 50)" class="px-2 py-0.5 rounded bg-white hover:bg-teal-100 font-semibold border border-teal-200">50% Struktur</button>
                            <button type="button" wire:click="$set('update_progress_percentage', 75)" class="px-2 py-0.5 rounded bg-white hover:bg-teal-100 font-semibold border border-teal-200">75% Finishing</button>
                            <button type="button" wire:click="$set('update_progress_percentage', 100)" class="px-2 py-0.5 rounded bg-emerald-600 text-white font-bold">100% Tuntas</button>
                        </div>
                    </div>

                    <!-- Milestone Title -->
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Judul Pencapaian / Milestone Baru <span class="text-rose-500">*</span></label>
                        <input 
                            type="text" 
                            wire:model="update_title" 
                            placeholder="Contoh: Pemasangan atap kios dan instalasi meteran listrik tuntas" 
                            class="w-full px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-teal-500 text-xs text-slate-800 font-semibold"
                        />
                        @error('update_title') <span class="text-rose-500 text-[11px]">{{ $message }}</span> @enderror
                    </div>

                    <!-- Status Selection -->
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Sesuaikan Status Proyek</label>
                        <select wire:model="update_status" class="w-full px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-teal-500 text-xs text-slate-800 font-semibold">
                            <option value="in_progress">Dalam Pelaksanaan (In Progress)</option>
                            <option value="planning">Perencanaan (Planning)</option>
                            <option value="completed">Selesai & Beroperasi (Completed)</option>
                            <option value="cancelled">Dibatalkan</option>
                        </select>
                    </div>

                    <!-- Field Notes -->
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Catatan Lapangan & Rekomendasi Evaluasi</label>
                        <textarea 
                            wire:model="update_notes" 
                            rows="3" 
                            placeholder="Tuliskan catatan kemajuan fisik, koordinasi tukang, atau kendala lapangan jika ada..." 
                            class="w-full px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-teal-500 text-xs text-slate-800"
                        ></textarea>
                    </div>

                    <!-- Actions -->
                    <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2">
                        <button 
                            type="button" 
                            wire:click="$set('showUpdateModal', false)" 
                            class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 cursor-pointer"
                        >
                            Batal
                        </button>
                        <button 
                            type="submit" 
                            class="px-5 py-2 rounded-xl bg-teal-700 hover:bg-teal-800 text-white text-xs font-bold shadow-xs cursor-pointer flex items-center gap-1.5"
                        >
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            <span>Simpan Perkembangan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- ============================================================ -->
    <!-- MODAL 3: RIWAYAT TIMELINE MILESTONE PROYEK -->
    <!-- ============================================================ -->
    @if($showHistoryModal && $selectedProjectForHistory)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs overflow-y-auto">
            <div class="bg-white rounded-3xl max-w-xl w-full p-6 sm:p-7 shadow-2xl border border-slate-100 my-8 space-y-5 animate-scale-up">
                
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div>
                        <span class="text-[10px] font-extrabold uppercase tracking-wider text-teal-700 bg-teal-50 px-2 py-0.5 rounded-md">Log Perkembangan</span>
                        <h3 class="font-heading font-bold text-base sm:text-lg text-slate-900 mt-1">{{ $selectedProjectForHistory->title }}</h3>
                        <p class="text-xs text-slate-500">{{ $selectedProjectForHistory->asset ? $selectedProjectForHistory->asset->name : 'Aset Desa' }} &bull; {{ $selectedProjectForHistory->asset && $selectedProjectForHistory->asset->village ? $selectedProjectForHistory->asset->village->name : 'Gresik' }}</p>
                    </div>
                    <button wire:click="$set('showHistoryModal', false)" class="p-2 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors cursor-pointer">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- Timeline List -->
                <div class="space-y-4 max-h-[60vh] overflow-y-auto pr-1">
                    @if($selectedProjectForHistory->updates->isEmpty())
                        <div class="py-8 text-center text-slate-400 text-xs">
                            Belum ada catatan pembaruan progres lapangan.
                        </div>
                    @else
                        <div class="relative pl-6 space-y-6 before:absolute before:left-2 before:top-2 before:bottom-2 before:w-0.5 before:bg-teal-200">
                            @foreach($selectedProjectForHistory->updates as $up)
                                <div class="relative group">
                                    <div class="absolute -left-6 top-1 w-4 h-4 rounded-full border-2 border-white bg-teal-600 shadow-xs flex items-center justify-center">
                                        <div class="w-1.5 h-1.5 rounded-full bg-white"></div>
                                    </div>
                                    <div class="bg-slate-50 hover:bg-teal-50/50 p-3.5 rounded-2xl border border-slate-200/80 transition-colors space-y-1">
                                        <div class="flex items-center justify-between gap-2 text-xs">
                                            <span class="font-bold text-slate-900">{{ $up->title }}</span>
                                            <span class="font-extrabold px-2 py-0.5 rounded-full bg-teal-100 text-teal-800 text-[10px]">
                                                {{ $up->progress_percentage }}%
                                            </span>
                                        </div>
                                        @if($up->notes)
                                            <p class="text-xs text-slate-600 leading-relaxed">{{ $up->notes }}</p>
                                        @endif
                                        <div class="flex items-center justify-between text-[10.5px] text-slate-400 pt-1">
                                            <span>Oleh: <strong class="text-slate-600">{{ $up->user ? $up->user->name : 'Tim Bappeda / Pemdes' }}</strong></span>
                                            <span>{{ $up->created_at->format('d M Y, H:i') }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                    <button 
                        type="button" 
                        wire:click="openUpdateModal({{ $selectedProjectForHistory->id }}); $set('showHistoryModal', false)" 
                        class="px-4 py-2 rounded-xl bg-teal-700 hover:bg-teal-800 text-white text-xs font-bold shadow-xs flex items-center gap-1 cursor-pointer"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                        <span>+ Tambah Catatan Progres</span>
                    </button>
                    <button 
                        type="button" 
                        wire:click="$set('showHistoryModal', false)" 
                        class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 cursor-pointer"
                    >
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- ============================================================ -->
    <!-- MODAL 4: KONFIRMASI HAPUS PROYEK -->
    <!-- ============================================================ -->
    @if($showDeleteConfirmModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
            <div class="bg-white rounded-3xl max-w-sm w-full p-6 shadow-2xl border border-slate-100 space-y-4 text-center animate-scale-up">
                <div class="w-12 h-12 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center mx-auto">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </div>
                <div>
                    <h4 class="font-heading font-bold text-base text-slate-900">Hapus Proyek Ini?</h4>
                    <p class="text-xs text-slate-500 mt-1">Data proyek dan seluruh riwayat catatan milestone terkait akan dihapus secara permanen dari sistem.</p>
                </div>
                <div class="flex items-center justify-center gap-2 pt-2 text-xs">
                    <button 
                        type="button" 
                        wire:click="$set('showDeleteConfirmModal', false)" 
                        class="px-4 py-2 rounded-xl border border-slate-200 font-bold text-slate-600 hover:bg-slate-50 cursor-pointer"
                    >
                        Batal
                    </button>
                    <button 
                        type="button" 
                        wire:click="deleteProject" 
                        class="px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold shadow-xs cursor-pointer"
                    >
                        Ya, Hapus Proyek
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>

