<?php

namespace App\Services\Ai;

use App\Models\Asset;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenRouterAiService
{
    protected string $apiKey;
    protected string $model;
    protected string $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.openrouter.api_key') ?? env('OPENROUTER_API_KEY', '');
        $this->model = config('services.openrouter.model') ?? env('OPENROUTER_MODEL', 'deepseek/deepseek-chat');
        $this->baseUrl = config('services.openrouter.base_url') ?? 'https://openrouter.ai/api/v1';
    }

    /**
     * Send Realtime Chat Completion to OpenRouter with automatic multi-model fallback
     */
    public function chat(array $messages, ?Asset $asset = null): array
    {
        $villageName = $asset?->village?->name ?? 'Sukomulyo';
        $districtName = $asset?->village?->district?->name ?? 'Manyar';
        $assetName = $asset ? $asset->name : 'Aset Desa Kabupaten Gresik';
        $assetArea = $asset ? $asset->area : 500;
        $assetCond = $asset ? $asset->condition : 'kurang produktif';

        $systemPrompt = <<<SYS
Anda adalah "Asisten AI KENTONGAN", pakar tata kelola aset daerah, perencanaan spasial, dan pemberdayaan ekonomi desa Kabupaten Gresik.
Tugas Anda:
1. Memberikan rekomendasi pemanfaatan aset non-aktif atau lahan tidur desa yang aplikatif, bernilai ekonomi tinggi, dan memberdayakan warga/BUMDes.
2. Gaya bahasa profesional, ramah, dan terstruktur jelas (gunakan poin-poin tebal, list, dan langkah konkret tanpa stiker emoji).
3. Format rekomendasi dengan poin:
   - Analisis Peluang Strategis & Kesesuaian Lokasi
   - Rekomendasi Unit Usaha Prioritas (BUMDes / Koperasi / Kemitraan)
   - Proyeksi Ekonomi & Pendapatan Asli Desa (PADes)
   - Langkah Aksi Implementasi Warga & Pemdes

Konteks Wilayah & Aset Terpilih:
- Nama Aset: {$assetName}
- Lokasi: Desa {$villageName}, Kecamatan {$districtName}, Kabupaten Gresik
- Luas: {$assetArea} m²
- Kondisi: {$assetCond}
SYS;

        // Candidate models in priority order
        $candidateModels = array_unique([
            $this->model,
            'deepseek/deepseek-chat',
            'openai/gpt-4o-mini',
            'meta-llama/llama-3.3-70b-instruct',
        ]);

        if (!empty($this->apiKey)) {
            $payloadMessages = [
                ['role' => 'system', 'content' => $systemPrompt]
            ];

            foreach ($messages as $msg) {
                $payloadMessages[] = [
                    'role' => $msg['role'] === 'user' ? 'user' : 'assistant',
                    'content' => $msg['text'] ?? $msg['content'] ?? ''
                ];
            }

            foreach ($candidateModels as $candidate) {
                try {
                    $response = Http::withHeaders([
                        'Authorization' => 'Bearer ' . $this->apiKey,
                        'HTTP-Referer' => config('app.url', 'http://localhost:8000'),
                        'X-Title' => 'KENTONGAN AI Kabupaten Gresik',
                        'Content-Type' => 'application/json',
                    ])->timeout(20)->post($this->baseUrl . '/chat/completions', [
                        'model' => $candidate,
                        'messages' => $payloadMessages,
                        'temperature' => 0.7,
                        'max_tokens' => 800,
                    ]);

                    if ($response->successful()) {
                        $data = $response->json();
                        $reply = $data['choices'][0]['message']['content'] ?? null;
                        if (!empty($reply)) {
                            return [
                                'success' => true,
                                'reply' => $reply,
                                'model' => $data['model'] ?? $candidate,
                            ];
                        }
                    } else {
                        Log::warning("OpenRouter model {$candidate} error: " . $response->body());
                    }
                } catch (\Throwable $e) {
                    Log::error("OpenRouter exception for {$candidate}: " . $e->getMessage());
                }
            }
        }

        // Realtime Local Fallback Engine (clean, professional, no emojis/stickers)
        $latestUserMsg = end($messages)['text'] ?? '';
        $fallbackReply = $this->generateHeuristicResponse($latestUserMsg, $assetName, $villageName, $districtName);

        return [
            'success' => true,
            'reply' => $fallbackReply,
            'model' => 'KENTONGAN-Engine (Standby)',
        ];
    }

    /**
     * Clean heuristic fallback without emojis or stickers
     */
    protected function generateHeuristicResponse(string $prompt, string $assetName, string $villageName, string $districtName): string
    {
        $lower = strtolower($prompt);

        if (str_contains($lower, 'kuliner') || str_contains($lower, 'makan') || str_contains($lower, 'pujasera') || str_contains($lower, 'kafe')) {
            return "Berdasarkan analisis spasial dan demografi Desa {$villageName} (Kecamatan {$districtName}), aset {$assetName} memiliki potensi strategis tinggi untuk dialokasikan sebagai Sentra Usaha Kuliner & Pujasera Terpadu BUMDes.\n\n" .
                "1. Analisis Kelayakan Lokasi:\n" .
                "Aksesibilitas berada di koridor permukiman dan zona mobilitas kerja, sangat potensial menarik pelanggan lokal dan pekerja industri.\n\n" .
                "2. Rekomendasi Pemanfaatan:\n" .
                "Penyediaan 10 hingga 15 kios terstandar untuk pelaku kuliner mikro, ruang komunal terbuka ramah keluarga, serta sistem pembayaran digital terpadu.\n\n" .
                "3. Estimasi Pendapatan & Manfaat Desa:\n" .
                "Skema sewa kios fleksibel dan retribusi kebersihan berpotensi menyumbang Rp 35.000.000 hingga Rp 75.000.000 per tahun untuk Pendapatan Asli Desa (PADes).";
        } elseif (str_contains($lower, 'tani') || str_contains($lower, 'tambak') || str_contains($lower, 'hidroponik') || str_contains($lower, 'greenhouse')) {
            return "Karakteristik tanah dan lingkungan aset {$assetName} di Desa {$villageName} sangat relevan untuk pengembangan Integrated Agriculture & Green House BUMDes.\n\n" .
                "1. Analisis Kesesuaian Lahan:\n" .
                "Lahan dapat dimanfaatkan untuk budidaya komoditas bernilai tinggi seperti melon hidroponik presisi, sayuran organik, atau kolam bioflok.\n\n" .
                "2. Model Kemitraan:\n" .
                "Melibatkan kelompok tani muda (petani milenial desa) dengan pendampingan teknis dari dinas pertanian dan penyerapan hasil panen oleh pasar retail Gresik.\n\n" .
                "3. Prospek Ekonomi:\n" .
                "Estimasi perputaran modal tercepat dalam siklus panen 3-4 bulan dengan proyeksi margin laba bersih 25-35%.";
        } elseif (str_contains($lower, 'wisata') || str_contains($lower, 'budaya') || str_contains($lower, 'taman') || str_contains($lower, 'rekreasi')) {
            return "Aset {$assetName} berpeluang besar dikembangkan menjadi Ruang Terbuka Hijau Edukatif & Ekowisata Komunitas Desa {$villageName}.\n\n" .
                "1. Konsep Revitalisasi:\n" .
                "Penataan lanskap taman tematik terintegrasi, jalur pejalan kaki yang nyaman, spot interaksi warga, dan panggung pertunjukan budaya mingguan.\n\n" .
                "2. Manfaat Sosial & Kelembagaan:\n" .
                "Meningkatkan indeks kebahagiaan warga serta memfasilitasi pasar kaget UMKM akhir pekan yang dikelola karang taruna dan BUMDes.\n\n" .
                "3. Rencana Keuangan:\n" .
                "Investasi bertahap bersumber dari penguatan modal BUMDes dan dana program inovasi desa berorientasi keberlanjutan lingkungan.";
        } elseif (str_contains($lower, 'vokasi') || str_contains($lower, 'pelatihan') || str_contains($lower, 'kursus') || str_contains($lower, 'skill')) {
            return "Mengingat Kabupaten Gresik merupakan sentra industri manufaktur dan pelabuhan utama Jawa Timur, alokasi {$assetName} sebagai Balai Vokasi & Pusat Keahlian Terpadu sangat tepat sasaran.\n\n" .
                "1. Ruang Lingkup Pelatihan:\n" .
                "Fasilitasi pelatihan kejuruan teknis industri, sertifikasi keselamatan kerja (K3), dan laboratorium pemasaran digital bagi UMKM desa.\n\n" .
                "2. Kemitraan Strategis:\n" .
                "Kerja sama dengan kawasan industri (JIIPE / Manyar) dan CSR perusahaan terdekat sebagai penyedia instruktur dan jaminan penyerapan tenaga kerja lokal.\n\n" .
                "3. Dampak Ekonomi:\n" .
                "Menurunkan angka pengangguran terbuka tingkat desa dan meningkatkan daya saing generasi muda lokal.";
        }

        return "Berdasarkan evaluasi kelayakan aset {$assetName} di Desa {$villageName} (Kecamatan {$districtName}), sistem AI merekomendasikan aktivasi berbasis Sentra Bisnis & Inkubator UMKM Terpadu BUMDes.\n\n" .
            "1. Zonasi Pemanfaatan:\n" .
            "Pembagian proporsional: 60% untuk area perdagangan/kios produktif, 20% area pelayanan warga, dan 20% ruang terbuka ramah lingkungan.\n\n" .
            "2. Langkah Realisasi:\n" .
            "Penyusunan proposal studi kelayakan bisnis sederhana, musyawarah desa pemdes bersama BPD, dan pembentukan unit usaha pengelola di bawah BUMDes.\n\n" .
            "3. Proyeksi Kinerja:\n" .
            "Diharapkan mampu menyerap 15-25 tenaga kerja lokal serta menghasilkan nilai perputaran ekonomi baru di tingkat desa.";
    }

    /**
     * Parse natural language report from user into structured AssetReport attributes
     */
    public function parseAssetReportFromText(string $userText): array
    {
        $categories = \App\Models\AssetCategory::all();
        $villages = \App\Models\Village::with('district')->get();
        
        $categoryList = $categories->pluck('name')->implode(', ');
        $villageList = $villages->pluck('name')->implode(', ');

        $systemPrompt = <<<SYS
Anda adalah parser AI cerdas untuk sistem KENTONGAN AI Kabupaten Gresik.
Tugas Anda: mengekstrak informasi aset daerah yang ingin dilaporkan warga dari teks menjadi format JSON murni tanpa markdown codeblock atau teks lain.

Kategori yang valid: {$categoryList}
Pilihan kondisi yang valid: tidak_digunakan, jarang_digunakan, kurang_produktif, rusak, terbengkalai
Pilihan rekomendasi pemanfaatan (suggested_use): UMKM, Kuliner, Pertanian, Wisata, Pendidikan, Olahraga, Coworking, Lainnya
Daftar desa di Gresik: {$villageList}

Format output JSON:
{
  "title": "Nama/Judul Aset Singkat Jelas",
  "village_name": "Nama Desa",
  "category_name": "Kategori yang paling cocok",
  "condition": "kondisi yang valid",
  "suggested_use": "usulan yang valid",
  "address": "Perkiraan alamat atau patokan lokasi",
  "description": "Deskripsi rinci mengenai kondisi dan potensi aset"
}
SYS;

        if (!empty($this->apiKey)) {
            try {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'HTTP-Referer' => config('app.url', 'http://localhost:8000'),
                    'X-Title' => 'KENTONGAN AI Report Parser',
                    'Content-Type' => 'application/json',
                ])->timeout(15)->post($this->baseUrl . '/chat/completions', [
                    'model' => $this->model,
                    'messages' => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user', 'content' => $userText]
                    ],
                    'temperature' => 0.2,
                    'max_tokens' => 500,
                ]);

                if ($response->successful()) {
                    $content = $response->json('choices.0.message.content');
                    $cleanJson = preg_replace('/^```(?:json)?\s*|\s*```$/i', '', trim($content));
                    $data = json_decode($cleanJson, true);
                    if (is_array($data) && !empty($data['title'])) {
                        return $this->resolveExtractedReport($data, $userText, $villages, $categories);
                    }
                }
            } catch (\Throwable $e) {
                Log::warning("OpenRouter parseAssetReportFromText failed: " . $e->getMessage());
            }
        }

        return $this->fallbackParseAssetReport($userText, $villages, $categories);
    }

    protected function resolveExtractedReport(array $data, string $rawText, $villages, $categories): array
    {
        $villageName = $data['village_name'] ?? '';
        $matchedVillage = $villages->first(fn($v) => stripos($v->name, $villageName) !== false) 
            ?? $villages->first();

        $categoryName = $data['category_name'] ?? '';
        $matchedCategory = $categories->first(fn($c) => stripos($c->name, $categoryName) !== false)
            ?? $categories->first();

        $condition = in_array($data['condition'] ?? '', ['tidak_digunakan', 'jarang_digunakan', 'kurang_produktif', 'rusak', 'terbengkalai'])
            ? $data['condition']
            : 'terbengkalai';

        $suggestedUse = in_array($data['suggested_use'] ?? '', ['UMKM', 'Kuliner', 'Pertanian', 'Wisata', 'Pendidikan', 'Olahraga', 'Coworking', 'Lainnya'])
            ? $data['suggested_use']
            : 'UMKM';

        return [
            'title' => $data['title'] ?? 'Laporan Aset Desa Terbengkalai',
            'village_id' => $matchedVillage?->id,
            'village_name' => $matchedVillage?->name ?? 'Sukomulyo',
            'district_id' => $matchedVillage?->district_id,
            'district_name' => $matchedVillage?->district?->name ?? 'Manyar',
            'category_id' => $matchedCategory?->id,
            'category_name' => $matchedCategory?->name ?? 'Tanah Kas Desa & Pekarangan',
            'condition' => $condition,
            'suggested_use' => $suggestedUse,
            'latitude' => (float)($matchedVillage?->latitude ?? -7.1350),
            'longitude' => (float)($matchedVillage?->longitude ?? 112.6020),
            'address' => $data['address'] ?? ($matchedVillage ? "Desa {$matchedVillage->name}, Manyar, Gresik" : 'Gresik, Jawa Timur'),
            'description' => $data['description'] ?? $rawText,
        ];
    }

    protected function fallbackParseAssetReport(string $rawText, $villages, $categories): array
    {
        $lower = strtolower($rawText);

        // Find village
        $matchedVillage = null;
        foreach ($villages as $v) {
            if (str_contains($lower, strtolower($v->name))) {
                $matchedVillage = $v;
                break;
            }
        }
        $matchedVillage = $matchedVillage ?? $villages->first();

        // Find category
        $matchedCategory = null;
        if (str_contains($lower, 'gedung') || str_contains($lower, 'bangunan') || str_contains($lower, 'balai') || str_contains($lower, 'gudang') || str_contains($lower, 'ruko') || str_contains($lower, 'kantor')) {
            $matchedCategory = $categories->first(fn($c) => str_contains(strtolower($c->name), 'bangunan'));
        } elseif (str_contains($lower, 'pasar') || str_contains($lower, 'kios') || str_contains($lower, 'lapak')) {
            $matchedCategory = $categories->first(fn($c) => str_contains(strtolower($c->name), 'pasar'));
        } elseif (str_contains($lower, 'lapangan') || str_contains($lower, 'olahraga') || str_contains($lower, 'gor')) {
            $matchedCategory = $categories->first(fn($c) => str_contains(strtolower($c->name), 'olahraga'));
        } elseif (str_contains($lower, 'tambak') || str_contains($lower, 'sawah') || str_contains($lower, 'tani') || str_contains($lower, 'kebun')) {
            $matchedCategory = $categories->first(fn($c) => str_contains(strtolower($c->name), 'pertanian'));
        } elseif (str_contains($lower, 'wisata') || str_contains($lower, 'pantai') || str_contains($lower, 'taman')) {
            $matchedCategory = $categories->first(fn($c) => str_contains(strtolower($c->name), 'wisata'));
        }
        $matchedCategory = $matchedCategory ?? $categories->first(fn($c) => str_contains(strtolower($c->name), 'tanah')) ?? $categories->first();

        // Find condition
        $condition = 'terbengkalai';
        if (str_contains($lower, 'rusak')) {
            $condition = 'rusak';
        } elseif (str_contains($lower, 'jarang')) {
            $condition = 'jarang_digunakan';
        } elseif (str_contains($lower, 'kurang') || str_contains($lower, 'sepi')) {
            $condition = 'kurang_produktif';
        } elseif (str_contains($lower, 'kosong') || str_contains($lower, 'tidak dipakai') || str_contains($lower, 'tidak digunakan')) {
            $condition = 'tidak_digunakan';
        }

        // Find suggested use
        $suggestedUse = 'UMKM';
        if (str_contains($lower, 'kuliner') || str_contains($lower, 'makan') || str_contains($lower, 'pujasera') || str_contains($lower, 'kafe')) {
            $suggestedUse = 'Kuliner';
        } elseif (str_contains($lower, 'wisata') || str_contains($lower, 'rekreasi') || str_contains($lower, 'taman')) {
            $suggestedUse = 'Wisata';
        } elseif (str_contains($lower, 'tani') || str_contains($lower, 'hidroponik') || str_contains($lower, 'tambak')) {
            $suggestedUse = 'Pertanian';
        } elseif (str_contains($lower, 'olahraga') || str_contains($lower, 'lapangan')) {
            $suggestedUse = 'Olahraga';
        } elseif (str_contains($lower, 'vokasi') || str_contains($lower, 'kursus') || str_contains($lower, 'belajar')) {
            $suggestedUse = 'Pendidikan';
        }

        $title = "Laporan {$matchedCategory->name} di Desa {$matchedVillage->name}";

        return [
            'title' => $title,
            'village_id' => $matchedVillage->id,
            'village_name' => $matchedVillage->name,
            'district_id' => $matchedVillage->district_id,
            'district_name' => $matchedVillage->district?->name ?? 'Manyar',
            'category_id' => $matchedCategory->id,
            'category_name' => $matchedCategory->name,
            'condition' => $condition,
            'suggested_use' => $suggestedUse,
            'latitude' => (float)$matchedVillage->latitude,
            'longitude' => (float)$matchedVillage->longitude,
            'address' => "Area Desa {$matchedVillage->name}, Kecamatan Manyar, Kabupaten Gresik",
            'description' => $rawText,
        ];
    }
}
