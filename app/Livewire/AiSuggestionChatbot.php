<?php

namespace App\Livewire;

use App\Models\Asset;
use App\Models\AssetIdea;
use App\Models\IdeaVote;
use App\Models\User;
use App\Services\Ai\OpenRouterAiService;
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
    public bool $isMaximized = false;

    protected $listeners = [
        'open-ai-chatbot' => 'openChatbot',
        'consult-asset-ai' => 'consultAsset'
    ];

    public function mount(?int $assetId = null)
    {
        $this->selectedAssetId = $assetId ?? Asset::first()?->id;
        $this->initWelcomeMessage();
    }

    public function initWelcomeMessage()
    {
        $this->messages = [
            [
                'role' => 'assistant',
                'text' => "Halo! Saya **Asisten AI KENTONGAN**, siap membantu Anda merencanakan pemanfaatan aset desa non-aktif, optimalisasi BUMDes, analisis kelayakan usaha, dan studi potensi ekonomi wilayah Kabupaten Gresik.\n\nSilakan pilih aset fokus di atas atau tanyakan ide pengembangan apapun untuk kemajuan desa Anda.",
                'time' => now()->format('H:i'),
                'proposal' => null,
            ]
        ];
    }

    public function resetChat()
    {
        $this->userInput = '';
        $this->isThinking = false;
        $this->initWelcomeMessage();
    }

    public function toggleMaximize()
    {
        $this->isMaximized = !$this->isMaximized;
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
            $this->sendQuickPrompt("Berikan analisis dan rekomendasi pemanfaatan terbaik untuk {$asset->name}");
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
        $assetName = $asset ? $asset->name : 'Aset Desa Kabupaten Gresik';
        $villageName = $asset?->village?->name ?? 'Sukomulyo';

        // Realtime AI integration via OpenRouter
        $openRouter = app(OpenRouterAiService::class);
        $result = $openRouter->chat($this->messages, $asset);
        $aiText = $result['reply'];

        $lower = strtolower($prompt);
        $category = 'UMKM';
        $title = "Optimalisasi {$assetName} Menjadi Pusat Ekonomi Kreatif";
        $desc = "Pemanfaatan terpadu berbasis BUMDes dan kemitraan masyarakat lokal.";

        if (str_contains($lower, 'kuliner') || str_contains($lower, 'makan') || str_contains($lower, 'pujasera') || str_contains($lower, 'kafe')) {
            $category = 'Kuliner';
            $title = "Sentra Pujasera & Kuliner Khas Pesisir {$villageName}";
            $desc = "Pembangunan kios kuliner higienis, area santai outdoor, dan pusat jajanan UMKM malam hari untuk menghidupkan perekonomian desa.";
        } elseif (str_contains($lower, 'tani') || str_contains($lower, 'tambak') || str_contains($lower, 'hidroponik') || str_contains($lower, 'greenhouse')) {
            $category = 'Pertanian';
            $title = "Integrated Urban Farming & Greenhouse Modern {$villageName}";
            $desc = "Pengembangan pertanian presisi bernilai tinggi (melon hidroponik, sayur organik, dan budidaya ikan air payau).";
        } elseif (str_contains($lower, 'wisata') || str_contains($lower, 'budaya') || str_contains($lower, 'taman') || str_contains($lower, 'rekreasi')) {
            $category = 'Wisata';
            $title = "Taman Ekowisata Edukasi & Spot Kreatif Warga {$villageName}";
            $desc = "Ruang terbuka hijau ramah anak dengan panggung seni budaya dan spot foto instagramable.";
        } elseif (str_contains($lower, 'vokasi') || str_contains($lower, 'pelatihan') || str_contains($lower, 'kursus') || str_contains($lower, 'skill')) {
            $category = 'Pendidikan';
            $title = "Balai Vokasi & Digital Creative Hub Desa {$villageName}";
            $desc = "Pusat pelatihan keahlian kerja industri, digital marketing UMKM, dan sertifikasi teknis anak muda.";
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
            'text' => "Usulan gagasan \"{$title}\" telah berhasil disimpan ke dalam sistem basis data perencanaan daerah dan tercatat di papan aspirasi warga (+10 Poin Partisipasi).",
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
