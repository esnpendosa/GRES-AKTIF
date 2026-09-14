<?php

namespace App\Livewire;

use App\Models\Asset;
use App\Models\AssetIdea;
use App\Models\AssetReport;
use App\Models\IdeaVote;
use App\Models\User;
use App\Services\Ai\OpenRouterAiService;
use App\Services\AuditLogger;
use App\Services\EmailNotificationService;
use App\Services\GamificationService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Illuminate\Support\Str;

class AiSuggestionChatbot extends Component
{
    public array $messages = [];
    public string $userInput = '';
    public ?int $selectedAssetId = null;
    public bool $isThinking = false;
    public bool $isModalOpen = false;
    public bool $isMaximized = false;

    // Report-via-AI state
    public ?array $pendingReport = null;   // parsed report data ready to be confirmed
    public bool $reportConfirmed = false;  // show post-submit confirmation

    // Guided Interactive & Animated Report Mode
    public bool $isReportMode = false;
    public int $reportStep = 1; // 1: Keterangan & Alur, 2: Radar Deteksi Lokasi GPS, 3: Analisis AI & Konfirmasi, 4: Selesai & Menuju Peta
    public string $reportTitle = '';
    public string $reportDescription = '';
    public string $reportCondition = 'terbengkalai';
    public ?float $userLat = null;
    public ?float $userLng = null;
    public ?int $detectedVillageId = null;
    public ?string $detectedVillageName = null;
    public ?string $detectedDistrictName = null;
    public ?string $detectedAddress = null;
    public bool $isLocating = false;
    public bool $locationLocked = false;
    public ?int $lastCreatedReportId = null;
    public ?string $mapRedirectUrl = null;

    protected $listeners = [
        'open-ai-chatbot' => 'openChatbot',
        'consult-asset-ai' => 'consultAsset',
        'start-ai-report'  => 'startReportMode',
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
                'text' => "Halo! Saya **Asisten AI KENTONGAN**, siap membantu Anda:\n\n- **Laporkan Aset Desa** — ceritakan kondisi dan lokasi aset terbengkalai, AI langsung buatkan laporan resmi & kirimkan notifikasi ke pemerintah.\n- **Rencanakan Pemanfaatan Aset** — analisis potensi BUMDes, UMKM, wisata, dll.\n- **Konsultasi Kelayakan Usaha** & potensi ekonomi wilayah Kabupaten Gresik.\n\nSilakan ketik keluhan atau pertanyaan Anda.",
                'time' => now()->format('H:i'),
                'proposal' => null,
                'pending_report' => null,
            ]
        ];
    }

    public function resetChat()
    {
        $this->userInput = '';
        $this->isThinking = false;
        $this->pendingReport = null;
        $this->reportConfirmed = false;
        $this->isReportMode = false;
        $this->reportStep = 1;
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
        $this->isReportMode = false;
        $asset = Asset::find($assetId);
        if ($asset) {
            $this->sendQuickPrompt("Berikan analisis dan rekomendasi pemanfaatan terbaik untuk {$asset->name}");
        }
    }

    /**
     * Start the animated guided reporting wizard inside chatbot
     */
    public function startReportMode(?string $presetText = null)
    {
        $this->isModalOpen = true;
        $this->isReportMode = true;
        $this->reportStep = 1;
        $this->reportConfirmed = false;
        $this->locationLocked = false;

        if ($presetText) {
            $this->reportDescription = $presetText;
            $this->reportTitle = Str::limit($presetText, 45);
        }

        // Auto-assign default village from user or first village
        $user = Auth::user();
        if ($user && $user->village) {
            $this->detectedVillageId = $user->village->id;
            $this->detectedVillageName = $user->village->name;
            $this->detectedDistrictName = $user->village->district?->name ?? 'Manyar';
            $this->userLat = (float)$user->village->latitude;
            $this->userLng = (float)$user->village->longitude;
            $this->detectedAddress = "Desa {$this->detectedVillageName}, Kec. {$this->detectedDistrictName}, Gresik";
        } else {
            $defaultVillage = \App\Models\Village::with('district')->first();
            if ($defaultVillage) {
                $this->detectedVillageId = $defaultVillage->id;
                $this->detectedVillageName = $defaultVillage->name;
                $this->detectedDistrictName = $defaultVillage->district?->name ?? 'Manyar';
                $this->userLat = (float)$defaultVillage->latitude;
                $this->userLng = (float)$defaultVillage->longitude;
                $this->detectedAddress = "Desa {$this->detectedVillageName}, Kec. {$this->detectedDistrictName}, Gresik";
            }
        }
    }

    public function cancelReportMode()
    {
        $this->isReportMode = false;
        $this->reportStep = 1;
    }

    public function goToStep(int $step)
    {
        if ($step === 2) {
            if (empty(trim($this->reportTitle))) {
                $this->reportTitle = !empty(trim($this->reportDescription)) 
                    ? Str::limit($this->reportDescription, 40)
                    : 'Aset Terbengkalai Temuan Warga';
            }
            $this->isLocating = true;
            $this->dispatch('start-gps-scan');
        } elseif ($step === 3) {
            $this->processAiAnalysis();
        }
        $this->reportStep = $step;
    }

    public function setLocation(float $lat, float $lng, ?string $address = null)
    {
        $this->userLat = round($lat, 6);
        $this->userLng = round($lng, 6);

        // Find the closest village in Gresik using Haversine
        $villages = \App\Models\Village::with('district')->get();
        $closest = null;
        $minDist = PHP_INT_MAX;

        foreach ($villages as $v) {
            $d = $this->calculateDistance($lat, $lng, (float)$v->latitude, (float)$v->longitude);
            if ($d < $minDist) {
                $minDist = $d;
                $closest = $v;
            }
        }

        if ($closest) {
            $this->detectedVillageId = $closest->id;
            $this->detectedVillageName = $closest->name;
            $this->detectedDistrictName = $closest->district?->name ?? 'Manyar';
            $this->detectedAddress = $address ?: "Desa {$this->detectedVillageName}, Kec. {$this->detectedDistrictName}, Gresik";
        }

        $this->locationLocked = true;
        $this->isLocating = false;
    }

    protected function calculateDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371; // km
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $earthRadius * $c;
    }

    public function processAiAnalysis()
    {
        $openRouter = app(OpenRouterAiService::class);
        $text = "{$this->reportTitle}. {$this->reportDescription}. Kondisi: {$this->reportCondition}. Lokasi di Desa {$this->detectedVillageName}, Kecamatan {$this->detectedDistrictName}.";
        
        $parsed = $openRouter->parseAssetReportFromText($text);
        if ($this->detectedVillageId) {
            $parsed['village_id'] = $this->detectedVillageId;
            $parsed['village_name'] = $this->detectedVillageName;
        }
        if ($this->userLat && $this->userLng) {
            $parsed['latitude'] = $this->userLat;
            $parsed['longitude'] = $this->userLng;
        }
        if ($this->detectedAddress) {
            $parsed['address'] = $this->detectedAddress;
        }
        $parsed['condition'] = $this->reportCondition;
        if (!empty($this->reportTitle)) {
            $parsed['title'] = $this->reportTitle;
        }

        $this->pendingReport = $parsed;
    }

    public function submitReportAndRedirectToMap()
    {
        if (empty($this->pendingReport)) {
            $this->processAiAnalysis();
        }

        $user = Auth::user();
        if (!$user) {
            $user = User::where('email', 'masyarakat@gresaktif.id')->first() ?? User::first();
            if ($user) Auth::login($user);
        }

        $data = $this->pendingReport;

        $report = AssetReport::create([
            'user_id'       => $user?->id ?? 1,
            'village_id'    => $data['village_id'] ?? $this->detectedVillageId ?? 1,
            'category_id'   => $data['category_id'] ?? 1,
            'title'         => $data['title'] ?? $this->reportTitle,
            'description'   => $data['description'] ?? $this->reportDescription,
            'condition'     => $data['condition'] ?? $this->reportCondition,
            'suggested_use' => $data['suggested_use'] ?? 'UMKM dan Sentra Kreatif Desa',
            'latitude'      => $data['latitude'] ?? $this->userLat ?? -7.1350,
            'longitude'     => $data['longitude'] ?? $this->userLng ?? 112.6020,
            'address'       => $data['address'] ?? $this->detectedAddress ?? 'Kabupaten Gresik',
            'photos'        => ['https://images.unsplash.com/photo-1513694203232-719a280e022f?auto=format&fit=crop&w=800&q=80'],
            'status'        => 'pending',
        ]);


        AuditLogger::log('created', 'AssetReport', $report->id, null, ['source' => 'AiChatbotInteractive']);

        EmailNotificationService::sendNewAssetReport($report->load(['village', 'user', 'category']), 'Asisten AI Kentongan');

        $this->lastCreatedReportId = $report->id;
        $this->mapRedirectUrl = route('map', [
            'newReport' => $report->id,
            'lat'       => $report->latitude,
            'lng'       => $report->longitude,
        ]);

        $this->reportStep = 4;
        $this->reportConfirmed = true;

        // Dispatch browser redirect event with animation
        $this->dispatch('report-completed-fly-map', [
            'url'     => $this->mapRedirectUrl,
            'lat'     => $report->latitude,
            'lng'     => $report->longitude,
            'title'   => $report->title,
            'village' => $this->detectedVillageName
        ]);
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
            'pending_report' => null,
        ];

        $this->userInput = '';
        $this->generateAiResponse($input);
    }

    /** Detect if user intent is to report an asset */
    protected function isReportIntent(string $prompt): bool
    {
        $lower = strtolower($prompt);
        $reportKeywords = [
            'laporkan', 'lapor', 'ada aset', 'ada bangunan', 'ada lahan',
            'ada lapangan', 'ada pasar', 'ada gedung', 'ada tanah',
            'terbengkalai', 'terlantar', 'tidak dipakai', 'tidak digunakan',
            'mangkrak', 'rusak parah', 'kosong bertahun', 'kosong lama',
            'mau lapor', 'ingin lapor', 'mau melaporkan', 'ingin melaporkan',
        ];

        foreach ($reportKeywords as $kw) {
            if (str_contains($lower, $kw)) {
                return true;
            }
        }
        return false;
    }

    protected function generateAiResponse(string $prompt)
    {
        $asset = $this->selectedAssetId ? Asset::with(['village.district', 'category'])->find($this->selectedAssetId) : null;
        $assetName = $asset ? $asset->name : 'Aset Desa Kabupaten Gresik';
        $villageName = $asset?->village?->name ?? 'Sukomulyo';

        $openRouter = app(OpenRouterAiService::class);

        // ---- REPORT INTENT DETECTION ----
        if ($this->isReportIntent($prompt)) {
            // Auto open interactive report mode with this prompt!
            $this->startReportMode($prompt);
            return;
        }

        // ---- NORMAL CONSULTATION ----
        $result = $openRouter->chat($this->messages, $asset);
        $aiText = $result['reply'];

        $lower = strtolower($prompt);
        $category = 'UMKM';
        $title = "Optimalisasi {$assetName} Menjadi Pusat Ekonomi Kreatif";
        $desc = "Pemanfaatan terpadu berbasis BUMDes dan kemitraan masyarakat lokal.";

        if (str_contains($lower, 'kuliner') || str_contains($lower, 'makan') || str_contains($lower, 'pujasera') || str_contains($lower, 'kafe')) {
            $category = 'Kuliner';
            $title = "Sentra Pujasera & Kuliner Khas Pesisir {$villageName}";
            $desc = "Pembangunan kios kuliner higienis, area santai outdoor, dan pusat jajanan UMKM malam hari.";
        } elseif (str_contains($lower, 'tani') || str_contains($lower, 'tambak') || str_contains($lower, 'hidroponik') || str_contains($lower, 'greenhouse')) {
            $category = 'Pertanian';
            $title = "Integrated Urban Farming & Greenhouse Modern {$villageName}";
            $desc = "Pengembangan pertanian presisi bernilai tinggi (melon hidroponik, sayur organik, budidaya ikan air payau).";
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
            'pending_report' => null,
        ];
    }

    /** Called when user confirms a pending AI-parsed report */
    public function confirmAndSubmitReport()
    {
        if (empty($this->pendingReport)) {
            return;
        }

        $user = Auth::user();
        if (!$user) {
            $user = User::where('email', 'masyarakat@gresaktif.id')->first() ?? User::first();
            if ($user) Auth::login($user);
        }

        $data = $this->pendingReport;

        $report = AssetReport::create([
            'user_id'      => $user?->id ?? 1,
            'village_id'   => $data['village_id'] ?? null,
            'category_id'  => $data['category_id'] ?? null,
            'title'        => $data['title'],
            'description'  => $data['description'],
            'condition'    => $data['condition'],
            'suggested_use' => $data['suggested_use'],
            'latitude'     => $data['latitude'],
            'longitude'    => $data['longitude'],
            'address'      => $data['address'],
            'photos'       => ['https://images.unsplash.com/photo-1513694203232-719a280e022f?auto=format&fit=crop&w=800&q=80'],
            'status'       => 'pending',
        ]);

        AuditLogger::log('created', 'AssetReport', $report->id, null, ['source' => 'AiChatbot']);

        // Send email notification to admin
        EmailNotificationService::sendNewAssetReport($report->load(['village', 'user', 'category']), 'Asisten AI Kentongan');

        $this->pendingReport = null;
        $this->reportConfirmed = true;

        $this->messages[] = [
            'role' => 'assistant',
            'text' => "Laporan **\"{$report->title}\"** telah berhasil disimpan dan diteruskan ke sistem KENTONGAN AI.\n\nNotifikasi email telah dikirimkan secara otomatis kepada aparatur pemerintah terkait untuk ditindaklanjuti. Terima kasih atas partisipasi aktif Anda!",
            'time' => now()->format('H:i'),
            'proposal' => null,
            'pending_report' => null,
        ];
    }

    /** Called when user submits an idea proposal from AI recommendation */
    public function submitProposal(string $title, string $category, string $description, ?int $assetId = null)
    {
        $targetAssetId = $assetId ?? $this->selectedAssetId ?? Asset::first()?->id;

        $user = Auth::user();
        if (!$user) {
            $user = User::where('email', 'masyarakat@gresaktif.id')->first() ?? User::first();
            if ($user) Auth::login($user);
        }

        $idea = AssetIdea::create([
            'asset_id'    => $targetAssetId,
            'user_id'     => $user?->id ?? 1,
            'title'       => $title,
            'category'    => $category,
            'description' => $description,
            'votes_count' => 1,
        ]);

        if ($user) {
            IdeaVote::create([
                'idea_id'   => $idea->id,
                'user_id'   => $user->id,
                'vote_type' => 'upvote',
            ]);
        }

        $asset = Asset::find($targetAssetId);
        if ($asset) {
            app(\App\Services\Ai\AiAnalysisService::class)->updateConsensus($asset);
        }

        AuditLogger::log('created', 'AssetIdea', $idea->id, null, ['title' => $title, 'source' => 'AiChatbot']);

        // Send email notification to admin
        EmailNotificationService::sendNewIdeaProposal($idea->load(['asset', 'user']), 'Asisten AI Kentongan');

        $this->messages[] = [
            'role' => 'assistant',
            'text' => "Usulan gagasan **\"{$title}\"** telah berhasil disimpan ke sistem perencanaan daerah dan notifikasi email telah dikirim ke aparatur terkait.",
            'time' => now()->format('H:i'),
            'proposal' => null,
            'pending_report' => null,
        ];

        $this->dispatch('idea-submitted-globally');
    }

    public function render()
    {
        $assets = Asset::with('village')->orderBy('name')->get();
        $villages = \App\Models\Village::with('district')->orderBy('name')->get();
        return view('livewire.ai-suggestion-chatbot', compact('assets', 'villages'));
    }
}
