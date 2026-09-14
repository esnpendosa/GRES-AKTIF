<div class="space-y-4">
    {{-- Header & Village Selector --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white p-4 rounded-2xl border border-slate-200 shadow-xs">
        <div>
            <h2 class="text-base font-bold text-slate-900">Editor Batas Wilayah Desa</h2>
            <p class="text-xs text-slate-500 mt-0.5">Gambar batas wilayah desa langsung di peta interaktif. Klik titik-titik sudut pada peta untuk menentukan area delimitasi aset desa.</p>
        </div>

        <div class="flex items-center gap-2 shrink-0">
            <label for="villageSelect" class="text-xs font-bold text-slate-700">Wilayah Desa:</label>
            <select id="villageSelect" wire:model.live="selectedVillageId" class="text-xs font-semibold px-3 py-1.5 rounded-xl border border-slate-200 bg-slate-50 text-slate-800 focus:bg-white focus:ring-2 focus:ring-teal-500 transition-colors">
                @foreach($allVillages as $v)
                    <option value="{{ $v->id }}">{{ $v->name }} (Kec. {{ $v->district?->name ?? '-' }})</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Flash Notifications --}}
    @if(session('success'))
        <div class="flex items-center gap-2 p-3 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-800 text-xs font-semibold shadow-xs">
            <i data-lucide="check-circle" class="w-4 h-4 shrink-0 text-emerald-600"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="flex items-center gap-2 p-3 bg-rose-50 border border-rose-200 rounded-xl text-rose-800 text-xs font-semibold shadow-xs">
            <i data-lucide="alert-circle" class="w-4 h-4 shrink-0 text-rose-600"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    @error('boundary')
        <div class="px-4 py-2.5 bg-rose-50 border border-rose-200 rounded-xl text-rose-700 text-xs font-medium">{{ $message }}</div>
    @enderror

    {{-- Toolbar --}}
    <div class="flex items-center gap-2 flex-wrap">
        @if(auth()->user()?->isDistrictAdmin())
            <div class="flex items-center gap-2 px-3.5 py-2 bg-sky-50 border border-sky-200 text-sky-800 text-xs font-semibold rounded-xl">
                <i data-lucide="shield-alert" class="w-4 h-4 text-sky-600 shrink-0"></i>
                <span>Mode Pengawasan Kecamatan: Anda hanya memiliki hak akses untuk melihat dan memantau batas wilayah desa (Hanya Lihat).</span>
            </div>
        @else
            <button type="button" id="btnDraw"
                class="flex items-center gap-1.5 px-4 py-2 bg-teal-700 hover:bg-teal-800 text-white text-xs font-bold rounded-xl transition-colors shadow-xs cursor-pointer">
                <i data-lucide="pen-line" class="w-3.5 h-3.5"></i>
                Mulai Gambar Batas Baru
            </button>
            <button type="button" id="btnSave"
                class="flex items-center gap-1.5 px-4 py-2 bg-sky-700 hover:bg-sky-800 text-white text-xs font-bold rounded-xl transition-colors shadow-xs cursor-pointer">
                <i data-lucide="save" class="w-3.5 h-3.5"></i>
                <span id="btnSaveText">Simpan Batas ke Database</span>
            </button>
            <button type="button" id="btnClear" wire:click="clearBoundary" wire:confirm="Yakin ingin menghapus data batas desa ini?"
                class="flex items-center gap-1.5 px-4 py-2 bg-slate-100 hover:bg-rose-50 hover:text-rose-700 text-slate-600 text-xs font-bold rounded-xl transition-colors border border-slate-200 cursor-pointer">
                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                Hapus Batas
            </button>
        @endif
        <a href="{{ auth()->user()?->isDistrictAdmin() ? route('dashboard.district') : route('dashboard.village') }}" class="ml-auto flex items-center gap-1.5 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition-colors">
            &larr; Kembali ke Dashboard
        </a>
        @if(!auth()->user()?->isDistrictAdmin())
            <div id="drawHint" class="w-full flex items-center gap-2 px-3 py-2 bg-teal-50 border border-teal-200 text-teal-800 text-xs rounded-xl hidden">
                <span class="w-2 h-2 rounded-full bg-teal-500 animate-ping shrink-0"></span>
                <span><strong>Mode Gambar Aktif:</strong> Klik pada peta untuk membuat titik sudut batas. Klik kembali titik awal atau klik tombol <strong>"Simpan Batas ke Database"</strong> jika sudah selesai.</span>
            </div>
        @endif
    </div>

    {{-- Map container --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div id="boundaryEditorMap" wire:ignore 
             data-lat="{{ $village?->latitude ?? -7.1350 }}"
             data-lng="{{ $village?->longitude ?? 112.6020 }}"
             data-boundary="{{ $boundaryJson }}"
             class="w-full" style="height: 540px;"></div>
    </div>

    {{-- Info koordinat --}}
    <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 text-xs space-y-2">
        <div class="flex items-center justify-between">
            <p class="font-semibold text-slate-700">Data Koordinat Polygon (<span id="pointCount">0</span> titik sudut)</p>
            <span class="text-[10px] text-slate-400">Format: JSON Array [latitude, longitude]</span>
        </div>
        <pre id="coordsDisplay" class="text-[11px] text-slate-600 font-mono whitespace-pre-wrap break-all max-h-36 overflow-y-auto bg-white p-3 rounded-lg border border-slate-200">{{ $boundaryJson }}</pre>
    </div>

    {{-- Hidden input sync with Livewire model --}}
    <input type="hidden" wire:model="boundaryJson" id="boundaryInput" value="{{ $boundaryJson }}">
</div>