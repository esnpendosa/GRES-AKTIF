<div>
    <!-- Floating Trigger Button (Bottom Right) -->
    <div class="fixed bottom-6 right-6 z-40">
        <button 
            type="button" 
            wire:click="$toggle('isModalOpen')" 
            class="group inline-flex items-center gap-2.5 px-4 py-3 rounded-full bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs shadow-2xl border border-teal-500/40 hover:border-teal-400 hover:scale-105 active:scale-95 transition-all cursor-pointer"
            title="Buka Asisten AI Kentongan"
        >
            <div class="w-7 h-7 rounded-full bg-teal-500/20 text-teal-300 flex items-center justify-center">
                <svg class="w-4 h-4 text-teal-300 group-hover:rotate-12 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
            <span class="tracking-wide">Tanya AI (+10 Pts)</span>
            <span class="w-2 h-2 rounded-full bg-teal-400 animate-ping"></span>
        </button>
    </div>

    <!-- ChatGPT Style Modal / Window -->
    @if($isModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-2 sm:p-4 bg-slate-950/70 backdrop-blur-sm animate-fade-in">
            <div class="bg-white rounded-3xl w-full border border-slate-200 shadow-2xl flex flex-col overflow-hidden transition-all duration-300 {{ $isMaximized ? 'h-[96vh] max-w-[96vw]' : 'h-[88vh] max-h-[720px] max-w-3xl' }}">
                
                <!-- ChatGPT Top Navigation Bar -->
                <div class="bg-slate-900 px-4 py-3 text-white flex items-center justify-between gap-3 shrink-0 border-b border-slate-800">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-xl bg-teal-600/30 border border-teal-500/50 text-teal-300 flex items-center justify-center shadow-xs shrink-0">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="font-heading font-extrabold text-sm text-white">KENTONGAN AI</h3>
                                <span class="flex items-center gap-1 text-[10px] text-teal-400 font-medium">
                                    <span class="w-1.5 h-1.5 rounded-full bg-teal-400 animate-pulse"></span>
                                    <span>Konsultasi Aset Daerah</span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Actions: New Chat, Maximize, Close -->
                    <div class="flex items-center gap-1.5">
                        <button 
                            type="button" 
                            wire:click="resetChat" 
                            class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white text-xs font-semibold transition-colors cursor-pointer"
                            title="Mulai Percakapan Baru"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                            <span class="hidden sm:inline">Chat Baru</span>
                        </button>

                        <button 
                            type="button" 
                            wire:click="toggleMaximize" 
                            class="p-1.5 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800 transition-colors cursor-pointer"
                            title="{{ $isMaximized ? 'Perkecil' : 'Perbesar Layar Penuh' }}"
                        >
                            @if($isMaximized)
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 9L4 4m0 0l5 0m-5 0l0 5m6 6l5 5m0 0l-5 0m5 0l0-5" />
                                </svg>
                            @else
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 8V4m0 0h4M4 4l5 5m11-5h-4m4 0v4m0-4l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                                </svg>
                            @endif
                        </button>

                        <button 
                            type="button" 
                            wire:click="$set('isModalOpen', false)" 
                            class="p-1.5 rounded-xl text-slate-400 hover:text-white hover:bg-rose-500/20 hover:text-rose-400 transition-colors cursor-pointer"
                            title="Tutup"
                        >
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Asset Focus Header Selector -->
                <div class="bg-slate-50 border-b border-slate-200 px-4 py-2 flex items-center justify-between gap-3 text-xs shrink-0">
                    <div class="flex items-center gap-2 text-slate-700 font-medium shrink-0">
                        <span class="w-2 h-2 rounded-full bg-teal-500"></span>
                        <span class="text-slate-500">Aset Sasaran:</span>
                    </div>
                    <select wire:model.live="selectedAssetId" class="flex-1 max-w-md px-3 py-1.5 rounded-xl border border-slate-200 bg-white text-slate-800 text-xs font-semibold focus:ring-2 focus:ring-teal-500">
                        @foreach($assets as $a)
                            <option value="{{ $a->id }}">{{ $a->name }} (Desa {{ $a->village->name ?? '-' }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Chat Feed -->
                <div class="flex-1 overflow-y-auto p-4 sm:p-6 space-y-6 bg-slate-50/50" id="chatFeedContainer" x-data x-init="$el.scrollTop = $el.scrollHeight" x-effect="$el.scrollTop = $el.scrollHeight">
                    
                    @if(count($messages) <= 1)
                        <!-- ChatGPT Welcome Hero & Prompt Starters -->
                        <div class="max-w-xl mx-auto text-center space-y-6 pt-4 pb-2 animate-fade-in">
                            <div class="w-12 h-12 rounded-2xl bg-slate-900 text-teal-400 flex items-center justify-center mx-auto shadow-md border border-teal-500/30">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-heading font-extrabold text-lg text-slate-900">Perencanaan Aset Desa Cerdas</h3>
                                <p class="text-xs text-slate-500 mt-1 max-w-md mx-auto">Konsultasikan kelayakan bisnis, proyeksi pendapatan BUMDes, atau bentuk kemitraan usaha untuk aset desa di Gresik.</p>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-left">
                                <button type="button" wire:click="sendQuickPrompt('Bagaimana rencana kelayakan pemanfaatan aset ini menjadi Sentra Kuliner UMKM & Pujasera BUMDes?')" class="p-3.5 rounded-2xl bg-white border border-slate-200 hover:border-teal-500 hover:shadow-md transition-all group cursor-pointer">
                                    <span class="text-xs font-bold text-slate-800 group-hover:text-teal-700 block">Sentra Kuliner & Pujasera</span>
                                    <span class="text-[11px] text-slate-500 mt-1 block">Konsep 10-15 kios terstandar bagi pelaku usaha mikro.</span>
                                </button>
                                <button type="button" wire:click="sendQuickPrompt('Kaji potensi pertanian hidroponik presisi, greenhouse melon, atau perikanan air payau di aset ini.')" class="p-3.5 rounded-2xl bg-white border border-slate-200 hover:border-teal-500 hover:shadow-md transition-all group cursor-pointer">
                                    <span class="text-xs font-bold text-slate-800 group-hover:text-teal-700 block">Pertanian & Green House</span>
                                    <span class="text-[11px] text-slate-500 mt-1 block">Pemberdayaan petani milenial & kemitraan pasar.</span>
                                </button>
                                <button type="button" wire:click="sendQuickPrompt('Susun konsep Balai Vokasi & Pelatihan Keterampilan Kerja Industri Manyar.')" class="p-3.5 rounded-2xl bg-white border border-slate-200 hover:border-teal-500 hover:shadow-md transition-all group cursor-pointer">
                                    <span class="text-xs font-bold text-slate-800 group-hover:text-teal-700 block">Balai Vokasi & Digital Hub</span>
                                    <span class="text-[11px] text-slate-500 mt-1 block">Kemitraan CSR kawasan industri & sertifikasi K3.</span>
                                </button>
                                <button type="button" wire:click="sendQuickPrompt('Bagaimana strategi pengembangan ekowisata ramah lingkungan dan ruang publik warga?')" class="p-3.5 rounded-2xl bg-white border border-slate-200 hover:border-teal-500 hover:shadow-md transition-all group cursor-pointer">
                                    <span class="text-xs font-bold text-slate-800 group-hover:text-teal-700 block">Ekowisata & Ruang Terbuka</span>
                                    <span class="text-[11px] text-slate-500 mt-1 block">Taman tematik terintegrasi pasar kaget akhir pekan.</span>
                                </button>
                            </div>
                        </div>
                    @endif

                    <!-- Message History -->
                    @foreach($messages as $msg)
                        @if($msg['role'] === 'assistant')
                            <!-- ChatGPT Assistant Message -->
                            <div class="flex items-start gap-3.5 max-w-[92%] sm:max-w-[88%]" x-data="{ copied: false }">
                                <div class="w-8 h-8 rounded-xl bg-slate-900 text-teal-400 flex items-center justify-center shrink-0 shadow-xs mt-1 border border-teal-500/30">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                </div>
                                    <div class="bg-white rounded-3xl rounded-tl-none p-4 sm:p-5 border border-slate-200 shadow-xs text-xs sm:text-[13px] text-slate-800 leading-relaxed space-y-2">
                                        <div class="prose prose-sm prose-slate max-w-none text-slate-800 leading-relaxed font-sans prose-headings:font-bold prose-headings:text-slate-900 prose-headings:text-sm prose-headings:mt-3 prose-headings:mb-1.5 prose-p:my-1.5 prose-ul:my-1.5 prose-ul:pl-4 prose-ol:my-1.5 prose-ol:pl-4 prose-li:my-0.5 prose-hr:my-3 prose-hr:border-slate-200 prose-strong:text-slate-900 prose-strong:font-bold">
                                            {!! \Illuminate\Support\Str::markdown($msg['text']) !!}
                                        </div>
                                    </div>

                                    <!-- Actionable Citizen Proposal Card -->
                                    @if(!empty($msg['proposal']))
                                        <div class="bg-teal-50/90 rounded-2xl p-4 border border-teal-200 shadow-xs space-y-2.5">
                                            <div class="flex items-center justify-between gap-2">
                                                <span class="text-[10px] font-extrabold px-2.5 py-1 rounded-md bg-teal-700 text-white uppercase tracking-wider">
                                                    Rekomendasi {{ $msg['proposal']['category'] }}
                                                </span>
                                                <span class="text-[11px] text-teal-800 font-bold">+10 Poin Warga</span>
                                            </div>
                                            <div>
                                                <h4 class="font-heading font-bold text-xs sm:text-sm text-slate-900">{{ $msg['proposal']['title'] }}</h4>
                                                <p class="text-xs text-slate-600 mt-1 leading-normal">{{ $msg['proposal']['description'] }}</p>
                                            </div>
                                            <button 
                                                type="button" 
                                                wire:click="submitProposal('{{ addslashes($msg['proposal']['title']) }}', '{{ addslashes($msg['proposal']['category']) }}', '{{ addslashes($msg['proposal']['description']) }}', {{ $msg['proposal']['asset_id'] ?? 'null' }})"
                                                class="w-full py-2.5 px-4 rounded-xl bg-teal-700 hover:bg-teal-800 text-white font-bold text-xs flex items-center justify-center gap-2 shadow-xs transition-all active:scale-95 cursor-pointer"
                                            >
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <span>Jadikan Usulan Resmi Warga</span>
                                            </button>
                                        </div>
                                    @endif

                                    <!-- Bottom Assistant Bar (Copy & Timestamp) -->
                                    <div class="flex items-center gap-3 text-[11px] text-slate-400 pl-1">
                                        <span>{{ $msg['time'] ?? '' }}</span>
                                        <button 
                                            type="button" 
                                            @click="navigator.clipboard.writeText('{{ addslashes($msg['text']) }}'); copied = true; setTimeout(() => copied = false, 2000)"
                                            class="hover:text-slate-600 flex items-center gap-1 transition-colors cursor-pointer"
                                            title="Salin Respons"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                            </svg>
                                            <span x-text="copied ? 'Tersalin!' : 'Salin'">Salin</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- User Message -->
                            <div class="flex items-start justify-end gap-2.5 ml-auto max-w-[85%]">
                                <div class="space-y-1 text-right">
                                    <div class="bg-slate-900 text-white rounded-3xl rounded-tr-none px-4 py-3 shadow-xs text-xs sm:text-[13px] leading-relaxed text-left">
                                        {{ $msg['text'] }}
                                    </div>
                                    <span class="text-[10px] text-slate-400 block mr-1">{{ $msg['time'] ?? '' }}</span>
                                </div>
                            </div>
                        @endif
                    @endforeach

                    <!-- Loading / Thinking Indicator -->
                    <div wire:loading wire:target="sendMessage,sendQuickPrompt" class="flex items-center gap-3 max-w-[80%] animate-fade-in">
                        <div class="w-8 h-8 rounded-xl bg-slate-900 text-teal-400 flex items-center justify-center shrink-0 border border-teal-500/30">
                            <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                        </div>
                        <div class="bg-white rounded-2xl rounded-tl-none px-4 py-3 border border-slate-200 shadow-xs flex items-center gap-2">
                            <span class="text-xs text-slate-500 font-medium">Asisten AI sedang merumuskan analisis...</span>
                            <span class="flex gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-teal-500 animate-bounce"></span>
                                <span class="w-1.5 h-1.5 rounded-full bg-teal-500 animate-bounce [animation-delay:0.2s]"></span>
                                <span class="w-1.5 h-1.5 rounded-full bg-teal-500 animate-bounce [animation-delay:0.4s]"></span>
                            </span>
                        </div>
                    </div>

                </div>

                <!-- ChatGPT Style Bottom Input Bar -->
                <div class="bg-white border-t border-slate-200 p-3 sm:p-4 shrink-0 space-y-2">
                    <form wire:submit="sendMessage" class="relative flex items-center">
                        <input 
                            type="text" 
                            wire:model="userInput" 
                            placeholder="Kirim pesan ke KENTONGAN AI..." 
                            class="w-full pl-4 pr-12 py-3 rounded-2xl border border-slate-300 bg-slate-50 text-slate-900 text-xs sm:text-sm focus:ring-2 focus:ring-slate-900 focus:bg-white focus:outline-none transition-all placeholder:text-slate-400"
                        />
                        <button 
                            type="submit" 
                            class="absolute right-2 p-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold transition-all shadow-xs cursor-pointer active:scale-95 disabled:opacity-50"
                            title="Kirim"
                        >
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                            </svg>
                        </button>
                    </form>
                    <p class="text-[10px] text-center text-slate-400">KENTONGAN AI dapat memberikan rekomendasi strategis. Periksa kembali kebijakan &amp; kelayakan lapangan sebelum realisasi.</p>
                </div>

            </div>
        </div>
    @endif
</div>

