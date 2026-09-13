<div>
    <!-- Floating Trigger Button (Bottom Right) -->
    <div class="fixed bottom-6 right-6 z-40">
        <button 
            type="button" 
            wire:click="$toggle('isModalOpen')" 
            class="group inline-flex items-center gap-2.5 px-4 py-3 rounded-full bg-gradient-to-r from-teal-800 via-teal-700 to-slate-900 text-white font-bold text-xs shadow-2xl border border-teal-500/30 hover:scale-105 active:scale-95 transition-all cursor-pointer"
            title="Buka Asisten AI Kentongan"
        >
            <div class="w-7 h-7 rounded-full bg-white/20 flex items-center justify-center">
                <svg class="w-4 h-4 text-amber-300 group-hover:rotate-12 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                </svg>
            </div>
            <span>Konsultasi AI Ide (+10 Pts)</span>
            <span class="w-2 h-2 rounded-full bg-teal-300 animate-ping"></span>
        </button>
    </div>

    <!-- AI Chatbot Modal / Drawer -->
    @if($isModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 bg-slate-900/60 backdrop-blur-xs animate-fade-in">
            <div class="bg-white rounded-3xl max-w-2xl w-full h-[85vh] max-h-[680px] border border-slate-200 shadow-2xl flex flex-col overflow-hidden animate-scale-in">
                
                <!-- Chatbot Header -->
                <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-teal-950 p-4 text-white flex items-center justify-between gap-3 shrink-0">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-teal-600/30 border border-teal-500/40 text-teal-300 flex items-center justify-center shadow-xs shrink-0">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
                                <rect x="7" y="5" width="12" height="22" rx="3.5" stroke="#00c9a7" stroke-width="2.2" fill="#0f172a"/>
                                <rect x="12" y="9" width="2" height="14" rx="1" fill="#38bdf8"/>
                                <path d="M22 10C23.8 12.2 23.8 17.8 22 20" stroke="#38bdf8" stroke-width="2" stroke-linecap="round"/>
                                <path d="M25 7C28 10.5 28 19.5 25 23" stroke="#00c9a7" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="font-heading font-extrabold text-sm sm:text-base text-white">Asisten AI KENTONGAN</h3>
                                <span class="px-2 py-0.5 rounded-full bg-teal-500/20 text-teal-300 text-[9px] font-bold border border-teal-500/30">AI Ide & Saran</span>
                            </div>
                            <p class="text-[11px] text-slate-300">Konsultasi cerdas ide pemanfaatan aset desa bernilai ekonomi</p>
                        </div>
                    </div>

                    <button 
                        type="button" 
                        wire:click="$set('isModalOpen', false)" 
                        class="p-1.5 rounded-xl text-slate-400 hover:text-white hover:bg-white/10 transition-colors"
                        title="Tutup Chatbot"
                    >
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Asset Context Bar -->
                <div class="bg-slate-50 border-b border-slate-200 px-4 py-2.5 flex items-center justify-between gap-3 text-xs shrink-0">
                    <span class="text-slate-600 font-semibold shrink-0">Fokus Aset Diskusi:</span>
                    <select wire:model.live="selectedAssetId" class="flex-1 max-w-sm px-3 py-1.5 rounded-xl border border-slate-200 bg-white text-slate-800 text-xs focus:ring-2 focus:ring-teal-500">
                        @foreach($assets as $a)
                            <option value="{{ $a->id }}">{{ $a->name }} (Desa {{ $a->village->name ?? '-' }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Chat Message Feed -->
                <div class="flex-1 overflow-y-auto p-4 sm:p-5 space-y-4 bg-slate-100/60" id="chatFeedContainer" x-data x-init="$el.scrollTop = $el.scrollHeight" x-effect="$el.scrollTop = $el.scrollHeight">
                    @foreach($messages as $msg)
                        @if($msg['role'] === 'assistant')
                            <!-- Assistant Bubble -->
                            <div class="flex items-start gap-3 max-w-[90%] sm:max-w-[85%]">
                                <div class="w-8 h-8 rounded-xl bg-slate-900 text-teal-300 flex items-center justify-center shrink-0 shadow-xs mt-0.5">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                </div>
                                <div class="space-y-2.5">
                                    <div class="bg-white rounded-2xl rounded-tl-none p-4 border border-slate-200 shadow-sm text-xs text-slate-700 leading-relaxed space-y-2">
                                        {!! nl2br(e($msg['text'])) !!}
                                    </div>

                                    <!-- If AI generated a concrete idea proposal, offer 1-click submit card -->
                                    @if(!empty($msg['proposal']))
                                        <div class="bg-teal-50/80 rounded-2xl p-3.5 border border-teal-200 shadow-xs space-y-2">
                                            <div class="flex items-center justify-between gap-2">
                                                <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-teal-700 text-white uppercase tracking-wider">
                                                    Rekomendasi {{ $msg['proposal']['category'] }}
                                                </span>
                                                <span class="text-[10px] text-teal-800 font-bold">+10 Poin Warga</span>
                                            </div>
                                            <div>
                                                <h4 class="font-heading font-bold text-xs text-slate-900">{{ $msg['proposal']['title'] }}</h4>
                                                <p class="text-[11px] text-slate-600 mt-0.5">{{ $msg['proposal']['description'] }}</p>
                                            </div>
                                            <button 
                                                type="button" 
                                                wire:click="submitProposal('{{ addslashes($msg['proposal']['title']) }}', '{{ addslashes($msg['proposal']['category']) }}', '{{ addslashes($msg['proposal']['description']) }}', {{ $msg['proposal']['asset_id'] ?? 'null' }})"
                                                class="w-full py-2 px-3 rounded-xl bg-teal-700 hover:bg-teal-800 text-white font-bold text-xs flex items-center justify-center gap-1.5 shadow-xs transition-all active:scale-95 cursor-pointer"
                                            >
                                                <i data-lucide="plus-circle" class="w-4 h-4"></i>
                                                <span>Jadikan Usulan Resmi Warga</span>
                                            </button>
                                        </div>
                                    @endif

                                    <span class="text-[10px] text-slate-400 block">{{ $msg['time'] ?? '' }}</span>
                                </div>
                            </div>
                        @else
                            <!-- User Bubble -->
                            <div class="flex items-start justify-end gap-2.5 ml-auto max-w-[85%]">
                                <div class="space-y-1 text-right">
                                    <div class="bg-teal-700 text-white rounded-2xl rounded-tr-none p-3.5 shadow-sm text-xs leading-relaxed text-left">
                                        {{ $msg['text'] }}
                                    </div>
                                    <span class="text-[10px] text-slate-400 block mr-1">{{ $msg['time'] ?? '' }}</span>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>

                <!-- Quick Prompt Chips -->
                <div class="px-4 py-2 bg-white border-t border-slate-100 flex items-center gap-2 overflow-x-auto [scrollbar-width:none] [-ms-overflow-style:none] [&::-webkit-scrollbar]:hidden text-[11px] shrink-0">
                    <span class="text-slate-400 font-bold shrink-0">Contoh:</span>
                    <button type="button" wire:click="sendQuickPrompt('Rekomendasi konsep Sentra Kuliner & Pujasera UMKM')" class="px-3 py-1 rounded-full bg-slate-100 hover:bg-teal-50 hover:text-teal-800 text-slate-700 font-medium shrink-0 transition-colors">
                        Pusat Kuliner
                    </button>
                    <button type="button" wire:click="sendQuickPrompt('Ide Greenhouse Pertanian Modern & Tambak')" class="px-3 py-1 rounded-full bg-slate-100 hover:bg-teal-50 hover:text-teal-800 text-slate-700 font-medium shrink-0 transition-colors">
                        Pertanian Modern
                    </button>
                    <button type="button" wire:click="sendQuickPrompt('Konsep Balai Pelatihan Vokasi & Digital Hub')" class="px-3 py-1 rounded-full bg-slate-100 hover:bg-teal-50 hover:text-teal-800 text-slate-700 font-medium shrink-0 transition-colors">
                        Balai Vokasi
                    </button>
                    <button type="button" wire:click="sendQuickPrompt('Konsep Ekowisata Budaya & Taman Terbuka')" class="px-3 py-1 rounded-full bg-slate-100 hover:bg-teal-50 hover:text-teal-800 text-slate-700 font-medium shrink-0 transition-colors">
                        Ekowisata
                    </button>
                </div>

                <!-- Message Input Form -->
                <form wire:submit="sendMessage" class="p-3 sm:p-4 bg-white border-t border-slate-200 flex items-center gap-2 shrink-0">
                    <input 
                        type="text" 
                        wire:model="userInput" 
                        placeholder="Ketik ide, pertanyaan, atau konsultasikan potensi aset desa..." 
                        class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-slate-800 text-xs focus:ring-2 focus:ring-teal-500 focus:bg-white transition-all"
                    />
                    <button 
                        type="submit" 
                        class="p-2.5 rounded-xl bg-teal-700 hover:bg-teal-800 text-white font-bold transition-all shadow-xs shrink-0 cursor-pointer"
                        title="Kirim Pesan"
                    >
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>
                </form>

            </div>
        </div>
    @endif
</div>
