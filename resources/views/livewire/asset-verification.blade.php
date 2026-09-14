<div class="space-y-6">

    <!-- Toast Notification -->
    @if($successMessage)
        <div class="p-4 rounded-2xl bg-teal-900 text-white flex items-center justify-between gap-3 shadow-lg border border-teal-500/40 animate-fade-in">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-teal-500/20 text-teal-300 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-teal-200">Verifikasi Berhasil</p>
                    <p class="text-sm font-semibold text-white">{{ $successMessage }}</p>
                </div>
            </div>
            <button wire:click="dismissToast" class="p-1.5 rounded-lg text-teal-300 hover:text-white hover:bg-teal-800/60 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    @endif

    <!-- Header Section -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-teal-100 text-teal-800 border border-teal-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-teal-600 animate-pulse"></span>
                    Otoritas Bappedalitbang Kab. Gresik
                </span>
                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-slate-100 text-slate-600">
                    18 Kecamatan &bull; 330 Desa
                </span>
            </div>
            <h1 class="font-heading font-extrabold text-2xl sm:text-3xl text-slate-900 tracking-tight mt-1">Pusat Verifikasi Laporan Aset</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Validasi laporan aset tidur warga, tetapkan legalitas, dan terbitkan langsung menjadi Aset Resmi Desa ke Peta GIS & SIPADES.</p>
        </div>
    </div>

    <!-- Overview Statistics Row -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3.5">
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs space-y-1">
            <span class="text-[11px] font-semibold text-amber-700 block">Menunggu Verifikasi</span>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl font-extrabold text-amber-600">{{ $pendingCount }}</span>
                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-amber-50 text-amber-700 border border-amber-200">Perlu Tindakan</span>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs space-y-1">
            <span class="text-[11px] font-semibold text-emerald-700 block">Disetujui / Terbit Aset</span>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl font-extrabold text-emerald-700">{{ $approvedCount }}</span>
                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">Aset Resmi</span>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs space-y-1">
            <span class="text-[11px] font-semibold text-rose-700 block">Laporan Ditolak</span>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl font-extrabold text-rose-600">{{ $rejectedCount }}</span>
                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-rose-50 text-rose-700 border border-rose-200">Tidak Sah</span>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs space-y-1">
            <span class="text-[11px] font-semibold text-slate-500 block">Total Partisipasi Warga</span>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl font-extrabold text-slate-900">{{ $totalCount }}</span>
                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-slate-100 text-slate-600">Laporan Masuk</span>
            </div>
        </div>
    </div>

    <!-- Filter Toolbar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs space-y-3">
        <!-- Status Tabs -->
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 pb-3">
            <div class="flex items-center gap-1.5 overflow-x-auto [scrollbar-width:none] [-ms-overflow-style:none] [&::-webkit-scrollbar]:hidden text-xs">
                <button 
                    type="button" 
                    wire:click="$set('statusFilter', 'pending')" 
                    class="px-3.5 py-1.5 rounded-xl font-bold transition-all {{ $statusFilter === 'pending' ? 'bg-amber-600 text-white shadow-xs' : 'bg-slate-50 text-slate-600 hover:bg-slate-100' }}"
                >
                    Menunggu Verifikasi ({{ $pendingCount }})
                </button>
                <button 
                    type="button" 
                    wire:click="$set('statusFilter', 'approved')" 
                    class="px-3.5 py-1.5 rounded-xl font-bold transition-all {{ $statusFilter === 'approved' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-slate-50 text-slate-600 hover:bg-slate-100' }}"
                >
                    Disetujui & Terbit Aset ({{ $approvedCount }})
                </button>
                <button 
                    type="button" 
                    wire:click="$set('statusFilter', 'rejected')" 
                    class="px-3.5 py-1.5 rounded-xl font-bold transition-all {{ $statusFilter === 'rejected' ? 'bg-rose-600 text-white shadow-xs' : 'bg-slate-50 text-slate-600 hover:bg-slate-100' }}"
                >
                    Ditolak ({{ $rejectedCount }})
                </button>
                <button 
                    type="button" 
                    wire:click="$set('statusFilter', 'all')" 
                    class="px-3.5 py-1.5 rounded-xl font-bold transition-all {{ $statusFilter === 'all' ? 'bg-slate-900 text-white shadow-xs' : 'bg-slate-50 text-slate-600 hover:bg-slate-100' }}"
                >
                    Semua ({{ $totalCount }})
                </button>
            </div>
        </div>

        <!-- Filter Dropdowns & Search -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5 text-xs">
            <!-- Filter Kecamatan -->
            <div>
                <select wire:model.live="districtFilter" class="w-full px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-800 font-semibold focus:bg-white">
                    <option value="">-- Semua Kecamatan (18) --</option>
                    @foreach($districts as $d)
                        <option value="{{ $d->id }}">Kecamatan {{ $d->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Desa -->
            <div>
                <select wire:model.live="villageFilter" class="w-full px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-800 font-semibold focus:bg-white" {{ empty($districtFilter) ? '' : '' }}>
                    <option value="">-- Semua Desa / Kelurahan --</option>
                    @foreach($villages as $v)
                        <option value="{{ $v->id }}">Desa {{ $v->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Search Keyword -->
            <div class="relative">
                <input 
                    type="text" 
                    wire:model.live.debounce.300ms="search" 
                    placeholder="Cari judul, alamat, pelapor..." 
                    class="w-full pl-9 pr-3 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:bg-white text-slate-800"
                />
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Reports Cards List -->
    @if($reports->isEmpty())
        <div class="bg-white rounded-3xl p-12 text-center border border-slate-200 shadow-xs space-y-4 max-w-lg mx-auto">
            <div class="w-16 h-16 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center mx-auto">
                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div class="space-y-1">
                <h3 class="font-heading font-bold text-lg text-slate-800">Tidak Ada Laporan yang Perlu Ditindak</h3>
                <p class="text-xs text-slate-500">Seluruh laporan dalam filter ini telah diproses atau belum ada aspirasi baru dari warga.</p>
            </div>
            @if($statusFilter !== 'all' || !empty($districtFilter) || !empty($search))
                <button 
                    type="button" 
                    wire:click="$set('statusFilter', 'all'); $set('districtFilter', ''); $set('villageFilter', ''); $set('search', '')" 
                    class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 cursor-pointer"
                >
                    Reset Filter
                </button>
            @endif
        </div>
    @else
        <div class="space-y-4">
            @foreach($reports as $r)
                <div class="bg-white rounded-3xl p-5 sm:p-6 border border-slate-200/90 shadow-xs hover:shadow-md transition-all flex flex-col lg:flex-row items-start lg:items-center justify-between gap-5">
                    
                    <!-- Left: Photo & Main Info -->
                    <div class="flex items-start gap-4 flex-1 min-w-0">
                        <!-- Thumbnail Photo -->
                        <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-2xl overflow-hidden bg-slate-100 shrink-0 border border-slate-200 relative group">
                            <img 
                                src="{{ $r->primary_photo_url }}" 
                                alt="" 
                                onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1513694203232-719a280e022f?auto=format&fit=crop&w=800&q=80';" 
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                            />
                            @if(is_array($r->photos) && count($r->photos) > 1)
                                <span class="absolute bottom-1 right-1 bg-slate-900/85 backdrop-blur-xs text-white text-[9px] font-bold px-1.5 py-0.5 rounded-md flex items-center gap-1 shadow-xs">
                                    <svg class="w-2.5 h-2.5 text-amber-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    {{ count($r->photos) }} Foto
                                </span>
                            @else
                                <span class="absolute bottom-1 right-1 bg-slate-900/70 backdrop-blur-xs text-white text-[9px] font-bold px-1.5 py-0.5 rounded shadow-xs">GPS</span>
                            @endif
                        </div>

                        <!-- Content -->
                        <div class="space-y-1.5 flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <!-- Status Badge -->
                                <span class="inline-flex items-center gap-1 text-[10px] font-extrabold px-2.5 py-0.5 rounded-full uppercase tracking-wider
                                    @if($r->status === 'approved') bg-emerald-50 text-emerald-700 border border-emerald-200
                                    @elseif($r->status === 'pending') bg-amber-50 text-amber-700 border border-amber-200
                                    @elseif($r->status === 'rejected') bg-rose-50 text-rose-700 border border-rose-200
                                    @else bg-slate-100 text-slate-700
                                    @endif"
                                >
                                    <span class="w-1.5 h-1.5 rounded-full 
                                        @if($r->status === 'approved') bg-emerald-500
                                        @elseif($r->status === 'pending') bg-amber-500 animate-pulse
                                        @elseif($r->status === 'rejected') bg-rose-500
                                        @else bg-slate-400
                                        @endif"
                                    ></span>
                                    {{ $r->status_label }}
                                </span>

                                <!-- Category Badge -->
                                <span class="text-[10.5px] font-bold text-teal-800 px-2 py-0.5 rounded-md bg-teal-50 border border-teal-100">
                                    {{ $r->category ? $r->category->name : 'Aset Desa' }}
                                </span>

                                <span class="text-slate-300">&bull;</span>
                                <span class="text-[11px] text-slate-400">{{ $r->created_at->format('d M Y, H:i') }}</span>
                            </div>

                            <h3 class="font-heading font-bold text-base sm:text-lg text-slate-900 leading-snug truncate">{{ $r->title }}</h3>
                            
                            <div class="flex flex-wrap items-center gap-1.5 text-xs text-slate-600">
                                <svg class="w-3.5 h-3.5 text-teal-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span class="font-semibold text-slate-800">{{ $r->village ? $r->village->name : 'Desa' }}</span>
                                <span class="text-slate-400">({{ $r->village && $r->village->district ? 'Kec. ' . $r->village->district->name : 'Gresik' }})</span>
                                <span class="text-slate-300">&bull;</span>
                                <span class="text-slate-500 font-mono text-[11px] bg-slate-50 px-1.5 py-0.5 rounded border border-slate-100">Lat: {{ round($r->latitude, 4) }}, Lng: {{ round($r->longitude, 4) }}</span>
                            </div>

                            @if($r->description)
                                <p class="text-xs text-slate-500 line-clamp-1 italic bg-slate-50/70 p-2 rounded-xl border border-slate-100/80">"{{ $r->description }}"</p>
                            @endif

                            <div class="flex flex-wrap items-center gap-3 pt-1 text-[11px] text-slate-500">
                                <span class="inline-flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    Pelapor: <strong class="text-slate-700">{{ $r->user ? $r->user->name : 'Warga Masyarakat' }}</strong>
                                </span>
                                <span>Usulan: <strong class="text-teal-700 bg-teal-50 px-1.5 py-0.5 rounded border border-teal-100">{{ $r->suggested_use ?: 'Sentra UMKM' }}</strong></span>
                                <span>Kondisi: <strong class="text-amber-700 bg-amber-50 px-1.5 py-0.5 rounded border border-amber-100">{{ $r->condition_label }}</strong></span>
                            </div>

                            @if($r->verification_notes)
                                <div class="mt-1 p-2 rounded-xl bg-slate-50 border border-slate-200 text-[11px] text-slate-700">
                                    <strong>Catatan Bappeda:</strong> {{ $r->verification_notes }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Right: Action Buttons -->
                    <div class="flex flex-wrap lg:flex-col items-center lg:items-end gap-2 shrink-0 w-full lg:w-auto pt-3 lg:pt-0 border-t lg:border-t-0 border-slate-100">
                        
                        <!-- Edit & Manage Photos Button -->
                        <button 
                            type="button" 
                            wire:click="openEditModal({{ $r->id }})" 
                            class="flex-1 lg:flex-initial px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-bold flex items-center justify-center gap-1.5 cursor-pointer transition-all border border-slate-200/80 shadow-2xs"
                            title="Edit Data Laporan & Kelola/Upload Foto"
                        >
                            <svg class="w-4 h-4 text-teal-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            <span>Edit & Foto</span>
                        </button>

                        @if($r->status === 'pending')
                            <!-- Quick Approve Button -->
                            <button 
                                type="button" 
                                wire:click="openApproveModal({{ $r->id }})" 
                                class="flex-1 lg:flex-initial px-4 py-2 rounded-xl bg-teal-700 hover:bg-teal-800 text-white text-xs font-bold shadow-xs flex items-center justify-center gap-1.5 cursor-pointer transition-all"
                            >
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                <span>Setujui & Terbitkan</span>
                            </button>

                            <!-- Reject Button -->
                            <button 
                                type="button" 
                                wire:click="openRejectModal({{ $r->id }})" 
                                class="px-3 py-2 rounded-xl border border-rose-200 text-rose-600 hover:bg-rose-50 text-xs font-bold flex items-center justify-center gap-1 cursor-pointer transition-all"
                            >
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                <span>Tolak</span>
                            </button>
                        @elseif($r->status === 'approved' && $r->createdAsset)
                            <a 
                                href="{{ route('assets.show', $r->createdAsset->slug ?? $r->createdAsset->id) }}" 
                                class="px-3.5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-xs flex items-center gap-1.5 transition-all"
                            >
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                <span>Buka Lembar Aset Resmi</span>
                            </a>
                            <a 
                                href="{{ route('map', ['lat' => $r->latitude, 'lng' => $r->longitude]) }}" 
                                class="text-xs font-bold text-teal-700 hover:text-teal-900 flex items-center gap-1"
                            >
                                <span>Lihat di Peta GIS</span>
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                        @endif

                        <!-- Detail Button -->
                        <button 
                            type="button" 
                            wire:click="openDetailModal({{ $r->id }})" 
                            class="px-3 py-2 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-semibold flex items-center justify-center gap-1 cursor-pointer transition-colors"
                        >
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <span>Detail</span>
                        </button>
                    </div>

                </div>
            @endforeach

            <!-- Pagination -->
            <div class="pt-2">
                {{ $reports->links() }}
            </div>
        </div>
    @endif

    <!-- ============================================================ -->
    <!-- MODAL 1: PERSETUJUAN & PENERBITAN ASET RESMI OLEH BAPPEDA -->
    <!-- ============================================================ -->
    @if($showApproveModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs overflow-y-auto">
            <div class="bg-white rounded-3xl max-w-xl w-full p-6 sm:p-7 shadow-2xl border border-slate-100 my-8 space-y-5 animate-scale-up">
                
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div>
                        <span class="text-[10px] font-extrabold uppercase tracking-wider text-teal-700 bg-teal-50 px-2 py-0.5 rounded-md">Keputusan Bappeda</span>
                        <h3 class="font-heading font-bold text-lg text-slate-900 mt-1">Setujui & Terbitkan Sebagai Aset Desa</h3>
                        <p class="text-xs text-slate-500">Laporan warga ini akan langsung dikonversi menjadi Aset Resmi Desa dan masuk ke Peta GIS & SIPADES.</p>
                    </div>
                    <button wire:click="$set('showApproveModal', false)" class="p-2 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors cursor-pointer">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form wire:submit="approveReport" class="space-y-4 text-xs">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Nama / Judul Aset Resmi Desa <span class="text-rose-500">*</span></label>
                        <input 
                            type="text" 
                            wire:model="approved_title" 
                            class="w-full px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-teal-500 text-xs text-slate-800 font-semibold"
                        />
                        @error('approved_title') <span class="text-rose-500 text-[11px]">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Kategori Aset <span class="text-rose-500">*</span></label>
                            <select wire:model="approved_category_id" class="w-full px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-800 font-semibold">
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Estimasi Luas (m²) <span class="text-rose-500">*</span></label>
                            <input 
                                type="number" 
                                wire:model="approved_area" 
                                class="w-full px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-800 font-bold"
                            />
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Rekomendasi Rencana Pemanfaatan / Aktivasi</label>
                        <input 
                            type="text" 
                            wire:model="approved_target_use" 
                            placeholder="Contoh: Sentra UMKM & Pujasera Kuliner BUMDes" 
                            class="w-full px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-800"
                        />
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Catatan Validasi Bappedalitbang</label>
                        <textarea 
                            wire:model="approved_notes" 
                            rows="2" 
                            class="w-full px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-800"
                        ></textarea>
                    </div>

                    <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2">
                        <button 
                            type="button" 
                            wire:click="$set('showApproveModal', false)" 
                            class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 cursor-pointer"
                        >
                            Batal
                        </button>
                        <button 
                            type="submit" 
                            class="px-5 py-2 rounded-xl bg-teal-700 hover:bg-teal-800 text-white text-xs font-bold shadow-xs cursor-pointer flex items-center gap-1.5"
                        >
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            <span>Sahkan & Terbitkan Aset</span>
                        </button>
                    </div>
                </form>

            </div>
        </div>
    @endif

    <!-- ============================================================ -->
    <!-- MODAL 2: PENOLAKAN LAPANGAN / BUKAN ASET DESA -->
    <!-- ============================================================ -->
    @if($showRejectModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
            <div class="bg-white rounded-3xl max-w-sm w-full p-6 shadow-2xl border border-slate-100 space-y-4 animate-scale-up">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <div>
                        <h4 class="font-heading font-bold text-sm text-slate-900">Tolak Laporan Warga Ini?</h4>
                        <p class="text-[11px] text-slate-500">Berikan alasan mengapa laporan ini tidak dapat disahkan.</p>
                    </div>
                </div>

                <div class="space-y-2 text-xs">
                    <label class="block font-bold text-slate-700">Alasan Penolakan <span class="text-rose-500">*</span></label>
                    <textarea 
                        wire:model="rejection_reason" 
                        rows="3" 
                        placeholder="Contoh: Bukan merupakan aset tanah kas desa, melainkan tanah hak milik pribadi warga." 
                        class="w-full px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-800"
                    ></textarea>
                    @error('rejection_reason') <span class="text-rose-500 text-[11px]">{{ $message }}</span> @enderror
                </div>

                <div class="pt-2 flex items-center justify-end gap-2 text-xs">
                    <button 
                        type="button" 
                        wire:click="$set('showRejectModal', false)" 
                        class="px-4 py-2 rounded-xl border border-slate-200 font-bold text-slate-600 hover:bg-slate-50 cursor-pointer"
                    >
                        Batal
                    </button>
                    <button 
                        type="button" 
                        wire:click="rejectReport" 
                        class="px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold shadow-xs cursor-pointer"
                    >
                        Tolak Laporan
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- ============================================================ -->
    <!-- MODAL 3: DETAIL LENGKAP LAPORAN WARGA -->
    <!-- ============================================================ -->
    @if($showDetailModal && $selectedReport)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs overflow-y-auto">
            <div class="bg-white rounded-3xl max-w-2xl w-full p-6 sm:p-7 shadow-2xl border border-slate-100 my-8 space-y-5 animate-scale-up">
                
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div>
                        <span class="text-[10px] font-extrabold uppercase tracking-wider text-teal-700 bg-teal-50 px-2 py-0.5 rounded-md">Detail Aspirasi Laporan</span>
                        <h3 class="font-heading font-bold text-lg text-slate-900 mt-1">{{ $selectedReport->title }}</h3>
                        <p class="text-xs text-slate-500">Desa {{ $selectedReport->village ? $selectedReport->village->name : '-' }}, Kec. {{ $selectedReport->village && $selectedReport->village->district ? $selectedReport->village->district->name : '-' }}</p>
                    </div>
                    <button wire:click="$set('showDetailModal', false)" class="p-2 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors cursor-pointer">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="space-y-4 text-xs">
                    <!-- Photo Gallery -->
                    @if(is_array($selectedReport->photos) && count($selectedReport->photos) > 0)
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5">
                            @foreach($selectedReport->formatted_photos as $pUrl)
                                <div class="rounded-2xl overflow-hidden border border-slate-200 h-36 bg-slate-100 relative group">
                                    <img 
                                        src="{{ $pUrl }}" 
                                        alt="" 
                                        onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1513694203232-719a280e022f?auto=format&fit=crop&w=800&q=80';" 
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform"
                                    />
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- Detail Grid -->
                    <div class="grid grid-cols-2 gap-3 p-4 rounded-2xl bg-slate-50 border border-slate-100">
                        <div>
                            <span class="text-[10px] text-slate-400 block">Kategori</span>
                            <span class="font-bold text-slate-800">{{ $selectedReport->category ? $selectedReport->category->name : '-' }}</span>
                        </div>
                        <div>
                            <span class="text-[10px] text-slate-400 block">Kondisi Aset</span>
                            <span class="font-bold text-amber-700">{{ $selectedReport->condition_label }}</span>
                        </div>
                        <div>
                            <span class="text-[10px] text-slate-400 block">Usulan Pemanfaatan</span>
                            <span class="font-bold text-teal-700">{{ $selectedReport->suggested_use ?: '-' }}</span>
                        </div>
                        <div>
                            <span class="text-[10px] text-slate-400 block">Pelapor</span>
                            <span class="font-bold text-slate-800">{{ $selectedReport->user ? $selectedReport->user->name : 'Warga' }}</span>
                        </div>
                        <div class="col-span-2">
                            <span class="text-[10px] text-slate-400 block">Alamat / Koordinat GPS</span>
                            <span class="font-semibold text-slate-700 block">{{ $selectedReport->address ?: '-' }}</span>
                            <span class="font-mono text-[10.5px] text-slate-500">Lat: {{ $selectedReport->latitude }}, Lng: {{ $selectedReport->longitude }}</span>
                        </div>
                    </div>

                    @if($selectedReport->description)
                        <div>
                            <span class="font-bold text-slate-700 block mb-1">Deskripsi Warga:</span>
                            <p class="p-3 rounded-xl bg-slate-50 border border-slate-100 text-slate-600 leading-relaxed">{{ $selectedReport->description }}</p>
                        </div>
                    @endif
                </div>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
                    <div class="flex items-center gap-2">
                        <!-- Edit Button from Detail Modal -->
                        <button 
                            type="button" 
                            wire:click="openEditModal({{ $selectedReport->id }}); $set('showDetailModal', false)" 
                            class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-bold flex items-center gap-1.5 cursor-pointer border border-slate-200/80 transition-colors"
                        >
                            <svg class="w-4 h-4 text-teal-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            <span>Edit & Foto</span>
                        </button>

                        @if($selectedReport->status === 'pending')
                            <button 
                                type="button" 
                                wire:click="openApproveModal({{ $selectedReport->id }}); $set('showDetailModal', false)" 
                                class="px-4 py-2 rounded-xl bg-teal-700 hover:bg-teal-800 text-white text-xs font-bold shadow-xs cursor-pointer"
                            >
                                Setujui & Terbitkan
                            </button>
                            <button 
                                type="button" 
                                wire:click="openRejectModal({{ $selectedReport->id }}); $set('showDetailModal', false)" 
                                class="px-3.5 py-2 rounded-xl border border-rose-200 text-rose-600 hover:bg-rose-50 text-xs font-bold cursor-pointer"
                            >
                                Tolak
                            </button>
                        @endif
                    </div>

                    <button 
                        type="button" 
                        wire:click="$set('showDetailModal', false)" 
                        class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 cursor-pointer"
                    >
                        Tutup
                    </button>
                </div>

            </div>
        </div>
    @endif

    <!-- ============================================================ -->
    <!-- MODAL 4: EDIT DATA & KELOLA / UNGGAH FOTO LAPORAN ASET -->
    <!-- ============================================================ -->
    @if($showEditModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs overflow-y-auto">
            <div class="bg-white rounded-3xl max-w-3xl w-full p-6 sm:p-7 shadow-2xl border border-slate-100 my-8 space-y-5 animate-scale-up">
                
                <!-- Modal Header -->
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-[10px] font-extrabold uppercase tracking-wider text-teal-700 bg-teal-50 px-2 py-0.5 rounded-md border border-teal-200/60">
                                Otoritas Bappeda Gresik
                            </span>
                            <span class="text-[10px] font-bold text-slate-500 bg-slate-100 px-2 py-0.5 rounded-md">
                                ID Laporan: #{{ $editReportId }}
                            </span>
                        </div>
                        <h3 class="font-heading font-bold text-xl text-slate-900 mt-1">Edit Data & Kelola Foto Aset</h3>
                        <p class="text-xs text-slate-500">Perbaiki informasi aset, lengkapi koordinat & luas, serta kelola atau unggah dokumentasi foto lapangan.</p>
                    </div>
                    <button wire:click="$set('showEditModal', false)" class="p-2 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors cursor-pointer">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- Edit Form -->
                <div class="space-y-4 text-xs max-h-[70vh] overflow-y-auto pr-1">
                    
                    <!-- Section 1: Data Identitas Aset -->
                    <div class="bg-slate-50/70 p-4 rounded-2xl border border-slate-200/80 space-y-3">
                        <h4 class="font-bold text-slate-800 text-xs flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            <span>1. Identitas & Profil Aset</span>
                        </h4>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Nama / Judul Aset <span class="text-rose-500">*</span></label>
                            <input 
                                type="text" 
                                wire:model="editTitle" 
                                class="w-full px-3 py-2 rounded-xl bg-white border border-slate-200 focus:ring-2 focus:ring-teal-500 text-xs text-slate-800 font-semibold"
                                placeholder="Contoh: Gedung Serbaguna Bekas Balai Desa..."
                            />
                            @error('editTitle') <span class="text-rose-500 text-[11px]">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Kategori Aset <span class="text-rose-500">*</span></label>
                                <select wire:model="editCategoryId" class="w-full px-3 py-2 rounded-xl bg-white border border-slate-200 text-xs text-slate-800 font-semibold">
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                                @error('editCategoryId') <span class="text-rose-500 text-[11px]">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Kondisi Aset <span class="text-rose-500">*</span></label>
                                <select wire:model="editCondition" class="w-full px-3 py-2 rounded-xl bg-white border border-slate-200 text-xs text-slate-800 font-semibold">
                                    <option value="kurang_produktif">Kurang Produktif</option>
                                    <option value="tidak_digunakan">Tidak Digunakan</option>
                                    <option value="jarang_digunakan">Jarang Digunakan</option>
                                    <option value="rusak">Rusak</option>
                                    <option value="terbengkalai">Terbengkalai</option>
                                </select>
                            </div>

                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Estimasi Luas (m²) <span class="text-rose-500">*</span></label>
                                <input 
                                    type="number" 
                                    wire:model="editArea" 
                                    min="1"
                                    class="w-full px-3 py-2 rounded-xl bg-white border border-slate-200 text-xs text-slate-800 font-bold"
                                />
                                @error('editArea') <span class="text-rose-500 text-[11px]">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Rekomendasi Usulan Pemanfaatan</label>
                                <input 
                                    type="text" 
                                    wire:model="editSuggestedUse" 
                                    placeholder="Contoh: Sentra Kuliner BUMDes / Gudang Logistik"
                                    class="w-full px-3 py-2 rounded-xl bg-white border border-slate-200 text-xs text-slate-800"
                                />
                            </div>
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Deskripsi / Keterangan Tambahan</label>
                                <input 
                                    type="text" 
                                    wire:model="editDescription" 
                                    placeholder="Keterangan singkat mengenai kondisi fisik atau sejarah aset..."
                                    class="w-full px-3 py-2 rounded-xl bg-white border border-slate-200 text-xs text-slate-800"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Lokasi & Koordinat Wilayah -->
                    <div class="bg-slate-50/70 p-4 rounded-2xl border border-slate-200/80 space-y-3">
                        <h4 class="font-bold text-slate-800 text-xs flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span>2. Lokasi Administratif & Koordinat Peta GIS</span>
                        </h4>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Kecamatan <span class="text-rose-500">*</span></label>
                                <select wire:model.live="editDistrictId" class="w-full px-3 py-2 rounded-xl bg-white border border-slate-200 text-xs text-slate-800 font-semibold">
                                    @foreach($districts as $d)
                                        <option value="{{ $d->id }}">Kecamatan {{ $d->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Desa / Kelurahan <span class="text-rose-500">*</span></label>
                                <select wire:model="editVillageId" class="w-full px-3 py-2 rounded-xl bg-white border border-slate-200 text-xs text-slate-800 font-semibold">
                                    @foreach($editVillages as $v)
                                        <option value="{{ $v->id }}">Desa {{ $v->name }}</option>
                                    @endforeach
                                </select>
                                @error('editVillageId') <span class="text-rose-500 text-[11px]">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Alamat Lengkap / Patokan Lapangan</label>
                            <input 
                                type="text" 
                                wire:model="editAddress" 
                                placeholder="Contoh: Jl. Raya Manyar No. 12, Sebelah Balai Desa Suci"
                                class="w-full px-3 py-2 rounded-xl bg-white border border-slate-200 text-xs text-slate-800"
                            />
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Latitude (Garis Lintang) <span class="text-rose-500">*</span></label>
                                <input 
                                    type="number" 
                                    step="0.0000001" 
                                    wire:model="editLatitude" 
                                    class="w-full px-3 py-2 rounded-xl bg-white border border-slate-200 text-xs text-slate-800 font-mono font-semibold"
                                />
                                @error('editLatitude') <span class="text-rose-500 text-[11px]">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Longitude (Garis Bujur) <span class="text-rose-500">*</span></label>
                                <input 
                                    type="number" 
                                    step="0.0000001" 
                                    wire:model="editLongitude" 
                                    class="w-full px-3 py-2 rounded-xl bg-white border border-slate-200 text-xs text-slate-800 font-mono font-semibold"
                                />
                                @error('editLongitude') <span class="text-rose-500 text-[11px]">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Section 3: Kelola & Tambah Foto (With Livewire File Upload) -->
                    <div class="bg-slate-50/70 p-4 rounded-2xl border border-slate-200/80 space-y-3">
                        <div class="flex items-center justify-between">
                            <h4 class="font-bold text-slate-800 text-xs flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span>3. Dokumentasi Foto Aset Lapangan</span>
                            </h4>
                            <span class="text-[10px] font-bold text-slate-500 bg-white px-2 py-0.5 rounded-md border border-slate-200">
                                Total: {{ count($existingPhotos) + count($newPhotos) }} Foto
                            </span>
                        </div>

                        <!-- Foto yang Sudah Ada -->
                        @if(!empty($existingPhotos))
                            <div>
                                <label class="block font-bold text-slate-600 text-[11px] mb-2">Foto Saat Ini (Klik ikon silang untuk membuang):</label>
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                                    @foreach($existingPhotos as $idx => $photoPath)
                                        @php
                                            $thumbUrl = str_starts_with($photoPath, 'http') ? $photoPath : '/storage/' . ltrim($photoPath, '/');
                                        @endphp
                                        <div class="relative group rounded-2xl overflow-hidden border border-slate-200 bg-slate-100 aspect-4/3">
                                            <img 
                                                src="{{ $thumbUrl }}" 
                                                alt="" 
                                                onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1513694203232-719a280e022f?auto=format&fit=crop&w=800&q=80';" 
                                                class="w-full h-full object-cover"
                                            />
                                            @if($idx === 0)
                                                <span class="absolute top-1.5 left-1.5 bg-teal-600/90 text-white text-[9px] font-extrabold px-1.5 py-0.5 rounded-md shadow-xs">
                                                    Utama
                                                </span>
                                            @endif
                                            <!-- Delete Photo Button -->
                                            <button 
                                                type="button" 
                                                wire:click="removeExistingPhoto({{ $idx }})" 
                                                class="absolute top-1.5 right-1.5 w-6 h-6 rounded-full bg-rose-600/90 hover:bg-rose-700 text-white flex items-center justify-center shadow-xs transition-colors cursor-pointer"
                                                title="Hapus foto ini"
                                            >
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Unggah Foto Baru -->
                        <div class="space-y-2">
                            <label class="block font-bold text-slate-600 text-[11px]">Unggah / Tambah Foto Baru (Dapat memilih lebih dari 1 file):</label>
                            
                            <div class="relative">
                                <input 
                                    type="file" 
                                    wire:model="newPhotos" 
                                    multiple 
                                    accept="image/png,image/jpeg,image/webp,image/jpg" 
                                    class="hidden" 
                                    id="input-verification-photos"
                                />
                                <label 
                                    for="input-verification-photos" 
                                    class="flex flex-col items-center justify-center p-4 border-2 border-dashed border-teal-300 hover:border-teal-500 rounded-2xl bg-teal-50/30 hover:bg-teal-50/60 transition-all cursor-pointer text-center group"
                                >
                                    <div class="w-10 h-10 rounded-xl bg-teal-100/70 text-teal-700 flex items-center justify-center mb-1.5 group-hover:scale-110 transition-transform">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                    </div>
                                    <p class="font-bold text-slate-800 text-xs">Pilih atau Seret Foto Tambahan ke Sini</p>
                                    <p class="text-[10px] text-slate-500 mt-0.5">Mendukung file JPG, PNG, WEBP hingga 10MB per gambar</p>
                                </label>
                            </div>

                            <!-- Upload Loading State -->
                            <div wire:loading wire:target="newPhotos" class="p-3 rounded-xl bg-teal-50 border border-teal-200 text-teal-800 text-xs font-semibold flex items-center gap-2">
                                <svg class="animate-spin w-4 h-4 text-teal-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                <span>Sedang mengunggah dan memproses foto tambahan...</span>
                            </div>

                            <!-- Preview Foto Baru yang Siap Disimpan -->
                            @if(!empty($newPhotos))
                                <div class="pt-2">
                                    <label class="block font-bold text-emerald-700 text-[11px] mb-1.5">Foto Baru yang Akan Disimpan (Preview):</label>
                                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                                        @foreach($newPhotos as $nIdx => $nPhoto)
                                            <div class="relative group rounded-2xl overflow-hidden border-2 border-emerald-400 bg-slate-100 aspect-4/3 shadow-xs">
                                                <img src="{{ $nPhoto->temporaryUrl() }}" class="w-full h-full object-cover">
                                                <span class="absolute top-1.5 left-1.5 bg-emerald-600 text-white text-[9px] font-extrabold px-1.5 py-0.5 rounded-md shadow-xs">
                                                    Foto Baru
                                                </span>
                                                <button 
                                                    type="button" 
                                                    wire:click="removeNewPhoto({{ $nIdx }})" 
                                                    class="absolute top-1.5 right-1.5 w-6 h-6 rounded-full bg-rose-600 hover:bg-rose-700 text-white flex items-center justify-center shadow-xs transition-colors cursor-pointer"
                                                    title="Batalkan foto ini"
                                                >
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @error('newPhotos.*') <span class="text-rose-500 text-[11px] block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Section 4: Catatan Validasi Bappedalitbang -->
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Catatan Verifikasi / Keterangan Bappeda</label>
                        <textarea 
                            wire:model="editVerificationNotes" 
                            rows="2" 
                            placeholder="Catatan hasil pengecekan berkas lapangan atau dasar keputusan..."
                            class="w-full px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-800"
                        ></textarea>
                    </div>

                </div>

                <!-- Modal Footer -->
                <div class="pt-3 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3">
                    <button 
                        type="button" 
                        wire:click="$set('showEditModal', false)" 
                        class="w-full sm:w-auto px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 cursor-pointer transition-colors"
                    >
                        Batal
                    </button>

                    <div class="w-full sm:w-auto flex flex-col sm:flex-row items-center gap-2">
                        <!-- Simpan Draft Laporan -->
                        <button 
                            type="button" 
                            wire:click="saveReportEdit(false)" 
                            class="w-full sm:w-auto px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold shadow-xs cursor-pointer flex items-center justify-center gap-1.5 transition-colors"
                        >
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                            <span>Simpan Perubahan Laporan</span>
                        </button>

                        <!-- Simpan & Sahkan Aset -->
                        <button 
                            type="button" 
                            wire:click="saveReportEdit(true)" 
                            class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-teal-700 hover:bg-teal-800 text-white text-xs font-bold shadow-xs cursor-pointer flex items-center justify-center gap-1.5 transition-colors"
                        >
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            <span>Simpan & Sahkan Aset Resmi</span>
                        </button>
                    </div>
                </div>

            </div>
        </div>
    @endif

</div>