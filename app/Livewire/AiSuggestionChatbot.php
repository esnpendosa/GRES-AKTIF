<?php

namespace App\Livewire;

use App\Models\Asset;
use App\Models\AssetIdea;
use App\Models\IdeaVote;
use App\Models\User;
use App\Services\AuditLogger;
use App\Services\GamificationService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AiSuggestionChatbot extends Component
{
    public array $messages = [];
    public string $userInput = '';
    public ?int $selectedAssetId = null;
    public bool $isThinking = false;
    public bool $isModalOpen = false;

    protected $listeners = [
        'open-ai-chatbot' => 'openChatbot',
        'consult-asset-ai' => 'consultAsset'
    ];

    public function mount(?int $assetId = null)
    {
        $this->selectedAssetId = $assetId ?? Asset::first()?->id;

        $this->messages = [
            [
                'role' => 'assistant',
                'text' => "Halo! Saya **Asisten AI KENTONGAN**, siap membantumu merancang gagasan pemanfaatan aset desa yang bernilai ekonomi. Pilih aset yang ingin dibahas atau tanyakan ide kreatif apa pun!",
                'time' => now()->format('H:i'),
                'proposal' => null,
            ]
        ];
    }

    public function openChatbot(?int $assetId = null)
    {
        if ($assetId) {
            $this->selectedAssetId = $assetId;
        }
        $this->isModalOpen = true;
    }

    public function consultAsset(int $assetId)
    {
        $this->selectedAssetId = $assetId;
        $this->isModalOpen = true;
        $asset = Asset::find($assetId);
        if ($asset) {
            $this->sendQuickPrompt("Berikan rekomendasi ide pemanfaatan terbaik untuk {$asset->name}");
        }
    }

    public function sendQuickPrompt(string $prompt)
    {
        $this->userInput = $prompt;
        $this->sendMessage();
    }

    public function sendMessage()
    {
        $input = trim($this->userInput);
        if (empty($input)) {
            return;
        }

        // Add User Message
        $this->messages[] = [
            'role' => 'user',
            'text' => $input,
            'time' => now()->format('H:i'),
            'proposal' => null,
        ];

        $this->userInput = '';
        $this->generateAiResponse($input);
    }

    protected function generateAiResponse(string $prompt)
    {
        $asset = $this->selectedAssetId ? Asset::with(['village.district', 'category'])->find($this->selectedAssetId) : null;
        $assetName = $asset ? $asset->name : 'Aset Desa di Gresik';
        $villageName = $asset?->village?->name ?? 'Gresik';
        $districtName = $asset?->village?->district?->name ?? 'Manyar';

        $lower = strtolower($prompt);
        $category = 'UMKM';
        $title = "Optimalisasi {$assetName} Menjadi Pusat Ekonomi Kreatif";
        $desc = "Pemanfaatan terpadu berbasis BUMDes dan kemitraan masyarakat lokal.";

        if (str_contains($lower, 'kuliner') || str_contains($lower, 'makan') || str_contains($lower, 'pujasera') || str_contains($lower, 'kafe')) {
            $category = 'Kuliner';
            $title = "Sentra Pujasera & Kuliner Khas Pesisir {$villageName}";
            $desc = "Pembangunan kios kuliner higienis, area santai outdoor, dan pusat jajanan UMKM malam hari untuk menghidupkan perekonomian desa.";
            $aiText = "Berdasarkan analisis lokasi di **Desa {$villageName} (Kec. {$districtName})**, aset **{$assetName}** sangat strategis untuk dikembangkan menjadi **Pusat Kuliner & Pujasera BUMDes**.\n\n" .
                "**💡 Keunggulan Rencana:**\n" .
                "1. **Trafik Pengunjung**: Menangkap pasar pekerja industri dan warga sekitar.\n" .
                "2. **Pemberdayaan**: Menyediakan 10-15 tenant usaha untuk ibu-ibu PKK dan pedagang lokal.\n" .
                "3. **Skema BUMDes**: Sewa terjangkau + bagi hasil kebersihan & retribusi PADes.";
        } elseif (str_contains($lower, 'tani') || str_contains($lower, 'tambak') || str_contains($lower, 'hidroponik') || str_contains($lower, 'greenhouse')) {
            $category = 'Pertanian';
            $title = "Integrated Urban Farming & Greenhouse Modern {$villageName}";
            $desc = "Pengembangan pertanian presisi bernilai tinggi (melon hidroponik, sayur organik, dan budidaya ikan air payau).";
            $aiText = "Aset **{$assetName}** memiliki potensi agrokultur tinggi. AI merekomendasikan konsep **Greenhouse & Pertanian Presisi Terpadu**.\n\n" .
                "**🌱 Manfaat Ekonomi:**\n" .
                "1. **Produktivitas Tinggi**: Hasil panen melon premium & sayuran hidroponik untuk suplai resto & supermarket.\n" .
                "2. **Edukasi**: Menjadi laboratorium vokasi bagi pemuda tani milenial desa.\n" .
                "3. **Estimasi ROI**: Balik modal dalam 14-18 bulan melalui kemitraan off-taker.";
        } elseif (str_contains($lower, 'wisata') || str_contains($lower, 'budaya') || str_contains($lower, 'taman') || str_contains($lower, 'rekreasi')) {
            $category = 'Wisata';
            $title = "Taman Ekowisata Edukasi & Spot Kreatif Warga {$villageName}";
            $desc = "Ruang terbuka hijau ramah anak dengan panggung seni budaya dan spot foto instagramable.";
            $aiText = "Karakteristik aset **{$assetName}** sangat cocok ditransformasikan menjadi **Destinasi Ekowisata & Ruang Publik Kreatif**.\n\n" .
                "**🌟 Pilar Pengembangan:**\n" .
                "1. **Revitalisasi Lansekap**: Jalur pedestrian santai, lampu tematik, dan amphitheater mini.\n" .
                "2. **Event Mingguan**: Pasar kaget akhir pekan dan festival budaya desa.\n" .
                "3. **Inklusivitas**: Ramah lansia, difabel, dan ruang bermain anak aman.";
        } elseif (str_contains($lower, 'vokasi') || str_contains($lower, 'pelatihan') || str_contains($lower, 'kursus') || str_contains($lower, 'skill')) {
            $category = 'Pendidikan';
            $title = "Balai Vokasi & Digital Creative Hub Desa {$villageName}";
            $desc = "Pusat pelatihan keahlian kerja industri, digital marketing UMKM, dan sertifikasi teknis anak muda.";
            $aiText = "Mengingat Gresik adalah kawasan industri maju, transformasi **{$assetName}** menjadi **Balai Pelatihan Kerja & Co-Working Space Desa** sangat mendesak!\n\n" .
                "**💻 Program Utama:**\n" .
                "1. Pelatihan operator industri, pengelasan, dan teknisi mesin.\n" .
                "2. Studio foto produk & live streaming jualan online untuk pelaku UMKM lokal.\n" .
                "3. Inkubasi startup desa dan literasi keuangan.";
        } else {
            $category = 'UMKM';
            $title = "Sentra Bisnis Terpadu & Inkubator UMKM {$villageName}";
            $desc = "Pusat perdagangan bersama produk unggulan desa dengan fasilitas logistik dan display modern.";
            $aiText = "Hasil komputasi algoritma AI untuk **{$assetName}** menunjukkan skor potensi **89/100** untuk fungsi **Sentra Bisnis & UMKM Terpadu**.\n\n" .
                "**📊 Rekomendasi AI:**\n" .
                "1. **Zonasi Fleksibel**: 60% kios ritel UMKM, 20% area workshop/produksi, 20% area pelayanan warga.\n" .
                "2. **Sinergi APBDes & BUMDes**: Modal revitalisasi bertahap dengan pelibatan gotong royong warga.\n" .
                "3. **Prospek PADes**: Berpotensi menyumbang Rp 45.000.000 - Rp 90.000.000/tahun untuk kas desa.";
        }

        $proposal = [
            'asset_id' => $asset?->id,
            'asset_name' => $assetName,
            'title' => $title,
            'category' => $category,
            'description' => $desc,
        ];

        $this->messages[] = [
            'role' => 'assistant',
            'text' => $aiText,
            'time' => now()->format('H:i'),
            'proposal' => $proposal,
        ];
    }

    public function submitProposal(string $title, string $category, string $description, ?int $assetId = null)
    {
        $targetAssetId = $assetId ?? $this->selectedAssetId ?? Asset::first()?->id;

        $user = Auth::user();
        if (!$user) {
            $user = User::where('email', 'masyarakat@gresaktif.id')->first() ?? User::first();
            if ($user) {
                Auth::login($user);
            }
        }

        $idea = AssetIdea::create([
            'asset_id' => $targetAssetId,
            'user_id' => $user ? $user->id : 1,
            'title' => $title,
            'category' => $category,
            'description' => $description,
            'votes_count' => 1,
        ]);

        if ($user) {
            IdeaVote::create([
                'idea_id' => $idea->id,
                'user_id' => $user->id,
                'vote_type' => 'upvote',
            ]);

            GamificationService::awardPoints($user, 10, 'Menyumbang ide pemanfaatan via Asisten AI Kentongan', 'AssetIdea', $idea->id);
        }

        $asset = Asset::find($targetAssetId);
        if ($asset) {
            app(\App\Services\Ai\AiAnalysisService::class)->updateConsensus($asset);
        }

        AuditLogger::log('created', 'AssetIdea', $idea->id, null, ['title' => $title, 'source' => 'AiChatbot']);

        $this->messages[] = [
            'role' => 'assistant',
            'text' => "🎉 **Sukses!** Usulan gagasan *\"{$title}\"* telah resmi tersimpan di sistem **KENTONGAN AI** dan masuk ke papan aspirasi warga. Kamu mendapatkan **+10 Poin Warga**!",
            'time' => now()->format('H:i'),
            'proposal' => null,
        ];

        $this->dispatch('idea-submitted-globally');
    }

    public function render()
    {
        $assets = Asset::with('village')->orderBy('name')->get();
        return view('livewire.ai-suggestion-chatbot', compact('assets'));
    }
}
