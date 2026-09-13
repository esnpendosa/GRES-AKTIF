<?php

namespace Database\Seeders;

use App\Models\AiAnalysis;
use App\Models\Asset;
use App\Models\AssetCategory;
use App\Models\AssetComment;
use App\Models\AssetIdea;
use App\Models\AssetImage;
use App\Models\AssetProject;
use App\Models\AssetProjectUpdate;
use App\Models\AssetReport;
use App\Models\CommunityConsensus;
use App\Models\IdeaVote;
use App\Models\User;
use App\Models\Village;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AssetSeeder extends Seeder
{
    public function run(): void
    {
        $communityUser = User::where('email', 'masyarakat@gresaktif.id')->first();
        $villageAdmin = User::where('email', 'desa@gresaktif.id')->first();

        $catBangunan = AssetCategory::where('slug', 'bangunan-gedung')->first();
        $catTanah = AssetCategory::where('slug', 'tanah-kas-desa')->first();
        $catPasar = AssetCategory::where('slug', 'pasar-desa')->first();
        $catOlahraga = AssetCategory::where('slug', 'fasilitas-olahraga')->first();
        $catPertanian = AssetCategory::where('slug', 'pertanian-tambak')->first();
        $catWisata = AssetCategory::where('slug', 'wisata-budaya')->first();
        $catInfrastruktur = AssetCategory::where('slug', 'infrastruktur-sarana')->first();
        $catPendidikan = AssetCategory::where('slug', 'pendidikan-pelatihan')->first();

        $sukomulyo = Village::where('code', '3525010001')->first();
        $peganden = Village::where('code', '3525010002')->first();
        $suci = Village::where('code', '3525010003')->first();
        $kembangan = Village::where('code', '3525020001')->first();
        $dahanrejo = Village::where('code', '3525020002')->first();
        $bedilan = Village::where('code', '3525030001')->first();
        $hulaan = Village::where('code', '3525040001')->first();
        $bunderan = Village::where('code', '3525070001')->first();
        $bungah = Village::where('code', '3525080001')->first();
        $pangkahkulon = Village::where('code', '3525100001')->first();

        // ==========================================
        // 1. FLAGSHIP DEMO SCENARIO: Gedung Serbaguna Desa Sukomulyo
        // ==========================================
        $flagshipAsset = Asset::create([
            'village_id' => $sukomulyo?->id ?? 1,
            'category_id' => $catBangunan->id,
            'created_by' => $communityUser->id,
            'name' => 'Gedung Serbaguna Desa Sukomulyo',
            'slug' => 'gedung-serbaguna-desa-sukomulyo',
            'description' => 'Gedung pertemuan milik Pemerintah Desa Sukomulyo seluas 500 m² yang sebelumnya hanya digunakan 2-3 kali setahun untuk acara hajatan. Terletak di tepi jalan utama desa dengan aksesibilitas tinggi dan listrik memadai.',
            'condition' => 'kurang_produktif',
            'status' => 'planning', // Progressed through lifecycle: Reported -> Verified -> AI Analyzed -> Community Discussion -> Prioritized -> Planning
            'ownership_type' => 'Pemerintah Desa',
            'area' => 500,
            'latitude' => -7.1350,
            'longitude' => 112.6020,
            'address' => 'Jl. Raya Sukomulyo No. 45, Manyar, Gresik',
            'potential_score' => 89,
            'location_score' => 92,
            'accessibility_score' => 88,
            'condition_score' => 70,
            'infrastructure_score' => 85,
            'community_demand_score' => 95,
            'economic_score' => 91,
            'target_activation_use' => 'Sentra UMKM & Pusat Kuliner Desa',
            'estimated_economic_value' => 450000000,
            'supporters_count' => 213,
            'verified_at' => now()->subDays(15),
            'verified_by' => $villageAdmin->id,
        ]);

        // Images for Flagship
        AssetImage::create([
            'asset_id' => $flagshipAsset->id,
            'image_path' => 'https://images.unsplash.com/photo-1513694203232-719a280e022f?auto=format&fit=crop&w=1000&q=80',
            'caption' => 'Tampak Depan Gedung Serbaguna Sukomulyo',
            'is_primary' => true,
        ]);
        AssetImage::create([
            'asset_id' => $flagshipAsset->id,
            'image_path' => 'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=1000&q=80',
            'caption' => 'Interior Luas Bagian Dalam Gedung',
            'is_primary' => false,
        ]);

        // Flagship AI Analysis
        AiAnalysis::create([
            'asset_id' => $flagshipAsset->id,
            'provider' => 'local_heuristic',
            'model' => 'gresik-asset-v1',
            'analysis_type' => 'comprehensive',
            'location_score' => 92,
            'accessibility_score' => 88,
            'condition_score' => 70,
            'infrastructure_score' => 85,
            'community_score' => 95,
            'economic_score' => 91,
            'potential_score' => 89,
            'confidence_score' => 89,
            'summary' => 'Aset memiliki luas 500 m² dan akses jalan yang sangat mendukung aktivitas ekonomi skala kecamatan. Lokasi dekat kawasan industri Manyar dan pemukiman padat meningkatkan kelayakan sebagai Sentra UMKM dan Pusat Kuliner Warga.',
            'recommendations' => [
                ['rank' => 1, 'title' => 'Sentra UMKM & Oleh-Oleh Khas Gresik', 'match_percentage' => 94, 'category' => 'UMKM', 'description' => 'Konversi ruang utama menjadi 15 kios modular untuk produk olahan bandeng, pudak, dan kerajinan lokal.'],
                ['rank' => 2, 'title' => 'Pusat Kuliner Kreatif & Pujasera Malam', 'match_percentage' => 87, 'category' => 'Kuliner', 'description' => 'Pemanfaatan pelataran depan untuk 12 stan kuliner higienis dengan sistem pembayaran digital.'],
                ['rank' => 3, 'title' => 'Balai Pelatihan Kerja & Co-working Vokasi', 'match_percentage' => 81, 'category' => 'Pendidikan', 'description' => 'Penyediaan ruang pelatihan keterampilan kerja industri bagi pemuda desa.'],
            ],
            'economic_scenarios' => [
                [
                    'code' => 'A',
                    'title' => 'Sentra UMKM & Produk Unggulan Desa',
                    'category' => 'UMKM',
                    'potential_score' => 94,
                    'estimated_tenants' => 15,
                    'estimated_jobs' => '30 - 40 Orang',
                    'estimated_renovation' => 'Sedang (Rp 85 Juta)',
                    'economic_impact' => 'Tinggi (Perputaran Rp 120 Juta/Bulan)',
                    'rationale' => 'Lokasi strategis dekat lingkar Manyar menyerap traffic pekerja harian dan wisatawan ziarah.',
                    'confidence' => 94,
                ],
                [
                    'code' => 'B',
                    'title' => 'Pusat Kuliner Pujasera Modern',
                    'category' => 'Kuliner',
                    'potential_score' => 87,
                    'estimated_tenants' => 12,
                    'estimated_jobs' => '20 - 25 Orang',
                    'estimated_renovation' => 'Ringan (Rp 45 Juta)',
                    'economic_impact' => 'Menengah-Tinggi (Perputaran Rp 80 Juta/Bulan)',
                    'rationale' => 'Pemberdayaan ibu-ibu PKK dan pedagang makanan mikro di lingkungan RW sekitar.',
                    'confidence' => 87,
                ],
                [
                    'code' => 'C',
                    'title' => 'Balai Pelatihan & Inkubasi Bisnis',
                    'category' => 'Pendidikan',
                    'potential_score' => 81,
                    'estimated_tenants' => 4,
                    'estimated_jobs' => '10 Orang',
                    'estimated_renovation' => 'Ringan (Rp 35 Juta)',
                    'economic_impact' => 'Jangka Panjang (Peningkatan Keterampilan)',
                    'rationale' => 'Kemitraan dengan CSR industri Manyar untuk sertifikasi keahlian teknis.',
                    'confidence' => 81,
                ],
            ],
            'raw_response' => json_encode(['status' => 'validated']),
        ]);

        // Community Ideas for Flagship
        $idea1 = AssetIdea::create([
            'asset_id' => $flagshipAsset->id,
            'user_id' => $communityUser->id,
            'title' => 'Jadikan Sentra UMKM & Gerai Produk Olahan Bandeng',
            'category' => 'UMKM',
            'description' => 'Desa kita banyak pengolah bandeng tanpa duri tapi tidak punya lapak representatif di pinggir jalan raya. Gedung ini pas sekali untuk dibuat etalase bersama.',
            'votes_count' => 42,
            'is_ai_recommended' => true,
        ]);
        $idea2 = AssetIdea::create([
            'asset_id' => $flagshipAsset->id,
            'user_id' => $communityUser->id,
            'title' => 'Bangun Pusat Kuliner & Food Court Malam Hari',
            'category' => 'Kuliner',
            'description' => 'Halaman depan cukup luas untuk meja kursi santai pekerja industri pulang sore hari.',
            'votes_count' => 31,
            'is_ai_recommended' => true,
        ]);
        $idea3 = AssetIdea::create([
            'asset_id' => $flagshipAsset->id,
            'user_id' => $communityUser->id,
            'title' => 'Ruang Pelatihan Digital & Co-working Space Pemuda',
            'category' => 'Pendidikan',
            'description' => 'Beri fasilitas wifi cepat dan proyektor agar pemuda Karang Taruna bisa belajar live commerce.',
            'votes_count' => 18,
            'is_ai_recommended' => false,
        ]);

        // Community Consensus for Flagship
        CommunityConsensus::create([
            'asset_id' => $flagshipAsset->id,
            'total_suggestions' => 18,
            'dominant_category' => 'UMKM & Kuliner',
            'consensus_summary' => 'Mayoritas masyarakat (75%) mengusulkan pemanfaatan aset ini sebagai perpaduan Sentra Display UMKM dan Pusat Kuliner Warga untuk mendorong pendapatan ekonomi keluarga.',
            'clusters' => [
                ['category' => 'Sentra UMKM', 'percentage' => 44, 'votes' => 42],
                ['category' => 'Pusat Kuliner', 'percentage' => 31, 'votes' => 31],
                ['category' => 'Balai Pelatihan', 'percentage' => 17, 'votes' => 18],
                ['category' => 'Lainnya', 'percentage' => 8, 'votes' => 7],
            ],
            'confidence_percentage' => 92,
        ]);

        // Comments for Flagship
        AssetComment::create([
            'asset_id' => $flagshipAsset->id,
            'user_id' => $communityUser->id,
            'comment' => 'Sangat setuju! Sudah bertahun-tahun gedung ini mubazir hanya dipakai kalau ada resepsi nikah.',
        ]);
        AssetComment::create([
            'asset_id' => $flagshipAsset->id,
            'user_id' => $villageAdmin->id,
            'comment' => 'Pemerintah Desa Sukomulyo sudah memasukkan revitalisasi ini ke Musrenbangdes tahun berjalan. Dukungan warga sangat diapresiasi.',
        ]);

        // Active Project for Flagship
        $project = AssetProject::create([
            'asset_id' => $flagshipAsset->id,
            'title' => 'Revitalisasi Sentra UMKM Sukomulyo Creative Hub',
            'objective' => 'Mengubah gedung serbaguna non-produktif menjadi sentra 15 tenant UMKM BUMDes dan pujasera modern.',
            'category' => 'UMKM',
            'budget_estimate' => 85000000,
            'funding_source' => 'Dana Desa & Alokasi BUMDes',
            'responsible_department' => 'Pemerintah Desa Sukomulyo & Dinas PMD',
            'start_date' => now()->subMonth(),
            'target_completion' => now()->addMonths(3),
            'status' => 'in_progress',
            'progress_percentage' => 45,
            'description' => 'Tahap perbaikan partisi dinding, instalasi meteran listrik kios individu, dan perapihan kanopi pelataran depan.',
        ]);

        AssetProjectUpdate::create([
            'project_id' => $project->id,
            'user_id' => $villageAdmin->id,
            'title' => 'Penyelesaian Desain Tata Letak Kios & Musyawarah Pengelola BUMDes',
            'notes' => 'Telah disepakati skema sewa terjangkau bagi 15 pelaku UMKM binaan RT/RW Sukomulyo.',
            'progress_percentage' => 45,
        ]);

        // ==========================================
        // 2. ADDITIONAL AUTHENTIC GRESIK ASSETS
        // ==========================================
        $assetsData = [
            [
                'village' => $peganden,
                'category' => $catTanah,
                'name' => 'Lahan Kas Desa Peganden Manyar',
                'slug' => 'lahan-kas-desa-peganden-manyar',
                'description' => 'Tanah lapang seluas 1.200 m² di sisi timur desa, dekat akses tol KLBM. Saat ini ditumbuhi ilalang dan tidak termanfaatkan.',
                'condition' => 'terbengkalai',
                'status' => 'ai_analyzed',
                'area' => 1200,
                'latitude' => -7.1280,
                'longitude' => 112.5850,
                'address' => 'Jl. KH Syafii, Peganden, Manyar',
                'potential_score' => 92,
                'location_score' => 94,
                'accessibility_score' => 90,
                'condition_score' => 65,
                'infrastructure_score' => 88,
                'community_demand_score' => 91,
                'economic_score' => 95,
                'target_activation_use' => 'Logistik Mini & Pergudangan UMKM',
                'estimated_economic_value' => 850000000,
                'supporters_count' => 142,
                'image' => 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?auto=format&fit=crop&w=1000&q=80',
            ],
            [
                'village' => $suci,
                'category' => $catWisata,
                'name' => 'Kawasan Bekas Tambang Suci (Sendang Suci)',
                'slug' => 'kawasan-bekas-tambang-suci',
                'description' => 'Area tebing kapur dan sendang alami seluas 3.500 m² yang memiliki keindahan lanskap unik namun belum dikelola profesional.',
                'condition' => 'kurang_produktif',
                'status' => 'community_discussion',
                'area' => 3500,
                'latitude' => -7.1420,
                'longitude' => 112.5920,
                'address' => 'Desa Suci, Manyar, Gresik',
                'potential_score' => 88,
                'location_score' => 85,
                'accessibility_score' => 82,
                'condition_score' => 75,
                'infrastructure_score' => 70,
                'community_demand_score' => 94,
                'economic_score' => 90,
                'target_activation_use' => 'Ekowisata Tebing Kapur & Kafe Alam',
                'estimated_economic_value' => 600000000,
                'supporters_count' => 189,
                'image' => 'https://images.unsplash.com/photo-1470071459604-3b5ec3a7fe05?auto=format&fit=crop&w=1000&q=80',
            ],
            [
                'village' => $kembangan,
                'category' => $catPasar,
                'name' => 'Kios Pasar Tradisional Kembangan',
                'slug' => 'kios-pasar-tradisional-kembangan',
                'description' => 'Deretan 18 unit kios pasar desa yang kosong sejak renovasi tahun 2021. Berada di jalur penghubung Kebomas - Manyar.',
                'condition' => 'tidak_digunakan',
                'status' => 'prioritized',
                'area' => 600,
                'latitude' => -7.1620,
                'longitude' => 112.6210,
                'address' => 'Jl. Sunan Giri, Kembangan, Kebomas',
                'potential_score' => 90,
                'location_score' => 93,
                'accessibility_score' => 89,
                'condition_score' => 78,
                'infrastructure_score' => 92,
                'community_demand_score' => 88,
                'economic_score' => 92,
                'target_activation_use' => 'Pasar Kuliner Tradisional & Suvenir Giri',
                'estimated_economic_value' => 520000000,
                'supporters_count' => 176,
                'image' => 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?auto=format&fit=crop&w=1000&q=80',
            ],
            [
                'village' => $dahanrejo,
                'category' => $catOlahraga,
                'name' => 'Lapangan Olahraga Terbuka Dahanrejo',
                'slug' => 'lapangan-olahraga-terbuka-dahanrejo',
                'description' => 'Fasilitas lapangan sepak bola desa seluas 4.000 m² yang minim fasilitas pendukung dan hanya terpakai di akhir pekan.',
                'condition' => 'kurang_produktif',
                'status' => 'verified',
                'area' => 4000,
                'latitude' => -7.1750,
                'longitude' => 112.6150,
                'address' => 'Dahanrejo Kulon, Kebomas, Gresik',
                'potential_score' => 84,
                'location_score' => 88,
                'accessibility_score' => 84,
                'condition_score' => 72,
                'infrastructure_score' => 68,
                'community_demand_score' => 89,
                'economic_score' => 85,
                'target_activation_use' => 'Sport Center Desa & Mini Soccer BUMDes',
                'estimated_economic_value' => 380000000,
                'supporters_count' => 98,
                'image' => 'https://images.unsplash.com/photo-1529900748604-07564a03e7a6?auto=format&fit=crop&w=1000&q=80',
            ],
            [
                'village' => $bedilan,
                'category' => $catPendidikan,
                'name' => 'Eks Gedung Sekolah Inpres Bedilan',
                'slug' => 'eks-gedung-sekolah-inpres-bedilan',
                'description' => 'Bekas gedung SD Inpres yang di-merger, berlokasi di kawasan cagar budaya Gresik Kota Lama seluas 400 m².',
                'condition' => 'jarang_digunakan',
                'status' => 'ai_analyzed',
                'area' => 400,
                'latitude' => -7.1580,
                'longitude' => 112.6530,
                'address' => 'Jl. Raden Santri, Bedilan, Gresik Kota',
                'potential_score' => 86,
                'location_score' => 90,
                'accessibility_score' => 86,
                'condition_score' => 74,
                'infrastructure_score' => 80,
                'community_demand_score' => 85,
                'economic_score' => 88,
                'target_activation_use' => 'Galeri Seni & Creative Workshop Heritage',
                'estimated_economic_value' => 310000000,
                'supporters_count' => 112,
                'image' => 'https://images.unsplash.com/photo-1541829070764-84a7d30dd3f3?auto=format&fit=crop&w=1000&q=80',
            ],
            [
                'village' => $hulaan,
                'category' => $catPertanian,
                'name' => 'Lahan Produktif Tidur Hulaan Menganti',
                'slug' => 'lahan-produktif-tidur-hulaan-menganti',
                'description' => 'Tanah bengkok desa seluas 5.000 m² yang belum ditanami tanaman bernilai ekonomi tinggi pasca panen padi musim lalu.',
                'condition' => 'jarang_digunakan',
                'status' => 'productive', // Example of fully activated asset
                'area' => 5000,
                'latitude' => -7.2650,
                'longitude' => 112.5750,
                'address' => 'Jl. Raya Menganti - Hulaan, Gresik',
                'potential_score' => 91,
                'location_score' => 89,
                'accessibility_score' => 87,
                'condition_score' => 85,
                'infrastructure_score' => 82,
                'community_demand_score' => 92,
                'economic_score' => 94,
                'target_activation_use' => 'Greenhouse Melon Hidroponik BUMDes',
                'estimated_economic_value' => 720000000,
                'supporters_count' => 165,
                'image' => 'https://images.unsplash.com/photo-1585320806297-9794b3e4eeae?auto=format&fit=crop&w=1000&q=80',
            ],
            [
                'village' => $bunderan,
                'category' => $catPasar,
                'name' => 'Pelataran Sentra Kuliner Bunderan Sidayu',
                'slug' => 'pelataran-sentra-kuliner-bunderan-sidayu',
                'description' => 'Lahan aset desa dekat Alun-alun Sidayu seluas 800 m² yang sangat ramai saat pagi tapi mati di malam hari.',
                'condition' => 'kurang_produktif',
                'status' => 'verified',
                'area' => 800,
                'latitude' => -6.9850,
                'longitude' => 112.5550,
                'address' => 'Kawasan Alun-alun Sidayu, Gresik Utara',
                'potential_score' => 87,
                'location_score' => 91,
                'accessibility_score' => 85,
                'condition_score' => 76,
                'infrastructure_score' => 84,
                'community_demand_score' => 90,
                'economic_score' => 88,
                'target_activation_use' => 'Sentra Kuliner Khas Sidayu & Kue Tradisional',
                'estimated_economic_value' => 290000000,
                'supporters_count' => 84,
                'image' => 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=1000&q=80',
            ],
            [
                'village' => $bungah,
                'category' => $catWisata,
                'name' => 'Dermaga Tambak & Wisata Susur Bengawan Bungah',
                'slug' => 'dermaga-tambak-bengawan-bungah',
                'description' => 'Dermaga perahu tradisional di bantaran Bengawan Solo desa Bungah seluas 1.500 m² yang terbengkalai pasca banjir.',
                'condition' => 'terbengkalai',
                'status' => 'reported',
                'area' => 1500,
                'latitude' => -7.0450,
                'longitude' => 112.5680,
                'address' => 'Bantaran Bengawan Solo, Bungah, Gresik',
                'potential_score' => 79,
                'location_score' => 80,
                'accessibility_score' => 74,
                'condition_score' => 58,
                'infrastructure_score' => 65,
                'community_demand_score' => 82,
                'economic_score' => 81,
                'target_activation_use' => 'Wisata Air Bengawan Solo & Resto Apung',
                'estimated_economic_value' => 340000000,
                'supporters_count' => 61,
                'image' => 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?auto=format&fit=crop&w=1000&q=80',
            ],
            [
                'village' => $pangkahkulon,
                'category' => $catPertanian,
                'name' => 'Tambak Bandeng Organik Pangkahkulon',
                'slug' => 'tambak-bandeng-organik-pangkahkulon',
                'description' => 'Tambak pesisir seluas 15.000 m² di muara Bengawan Solo yang siap diintegrasikan dengan ekowisata mangrove.',
                'condition' => 'kurang_produktif',
                'status' => 'ai_analyzed',
                'area' => 15000,
                'latitude' => -6.9050,
                'longitude' => 112.5450,
                'address' => 'Kawasan Muara Pangkahkulon, Ujungpangkah',
                'potential_score' => 89,
                'location_score' => 84,
                'accessibility_score' => 78,
                'condition_score' => 80,
                'infrastructure_score' => 75,
                'community_demand_score' => 93,
                'economic_score' => 96,
                'target_activation_use' => 'Integrated Mangrove & Silvofishery Bandeng',
                'estimated_economic_value' => 1200000000,
                'supporters_count' => 134,
                'image' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1000&q=80',
            ],
            [
                'village' => Village::where('name', 'Gedongkedoan')->first() ?? $bungah,
                'category' => $catTanah,
                'name' => 'Tanah Bengkok & Pertanian Gedongkedoan Dukun',
                'slug' => 'tanah-bengkok-gedongkedoan-dukun',
                'description' => 'Tanah kas desa seluas 13.637 m² (SIPADES KIB A) dengan potensi agribisnis dan sentra lumbung pangan modern.',
                'condition' => 'kurang_produktif',
                'status' => 'verified',
                'area' => 13637,
                'latitude' => -7.0120,
                'longitude' => 112.4920,
                'address' => 'Jl. Poros Desa Gedongkedoan, Dukun, Gresik',
                'potential_score' => 85,
                'location_score' => 82,
                'accessibility_score' => 80,
                'condition_score' => 88,
                'infrastructure_score' => 76,
                'community_demand_score' => 89,
                'economic_score' => 91,
                'target_activation_use' => 'Sentra Agribisnis & Pengeringan Gabah Modern',
                'estimated_economic_value' => 3409250000,
                'supporters_count' => 89,
                'image' => 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?auto=format&fit=crop&w=1000&q=80',
            ],
            [
                'village' => Village::where('name', 'Dalegan')->first() ?? $bungah,
                'category' => $catWisata,
                'name' => 'Kawasan Pesisir Pasir Putih Dalegan Panceng',
                'slug' => 'kawasan-pesisir-pasir-putih-dalegan',
                'description' => 'Kawasan pesisir pantai utara seluas 8.000 m² dengan ombak tenang, sangat potensial untuk pusat kuliner seafood dan watersport.',
                'condition' => 'kurang_produktif',
                'status' => 'planning',
                'area' => 8000,
                'latitude' => -6.9150,
                'longitude' => 112.4450,
                'address' => 'Pesisir Pantai Dalegan, Panceng, Gresik',
                'potential_score' => 93,
                'location_score' => 95,
                'accessibility_score' => 90,
                'condition_score' => 80,
                'infrastructure_score' => 82,
                'community_demand_score' => 96,
                'economic_score' => 95,
                'target_activation_use' => 'Sentra Kuliner Seafood & Wisata Bahari Edukatif',
                'estimated_economic_value' => 1850000000,
                'supporters_count' => 245,
                'image' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1000&q=80',
            ],
            [
                'village' => Village::where('name', 'Kotakusuma')->first() ?? $bungah,
                'category' => $catBangunan,
                'name' => 'Sentra Pengolahan Ikan Bawean Kotakusuma',
                'slug' => 'sentra-pengolahan-ikan-bawean-kotakusuma',
                'description' => 'Fasilitas gedung seluas 750 m² dekat pelabuhan Sangkapura Pulau Bawean untuk sentra olahan abon tongkol dan kerupuk poso.',
                'condition' => 'tidak_digunakan',
                'status' => 'ai_analyzed',
                'area' => 750,
                'latitude' => -5.8550,
                'longitude' => 112.6450,
                'address' => 'Kawasan Pelabuhan Sangkapura, Pulau Bawean',
                'potential_score' => 91,
                'location_score' => 92,
                'accessibility_score' => 85,
                'condition_score' => 74,
                'infrastructure_score' => 82,
                'community_demand_score' => 94,
                'economic_score' => 93,
                'target_activation_use' => 'Pusat Produksi & Ekspor Olahan Hasil Laut Bawean',
                'estimated_economic_value' => 680000000,
                'supporters_count' => 152,
                'image' => 'https://images.unsplash.com/photo-1513694203232-719a280e022f?auto=format&fit=crop&w=1000&q=80',
            ],
        ];

        foreach ($assetsData as $data) {
            $village = $data['village'];
            if (!$village) continue;

            $image = $data['image'];
            $cat = $data['category'];
            unset($data['village'], $data['image'], $data['category']);

            $asset = Asset::create(array_merge($data, [
                'village_id' => $village->id,
                'category_id' => $cat->id,
                'created_by' => $communityUser->id,
                'verified_by' => $villageAdmin->id,
                'verified_at' => now()->subDays(rand(5, 30)),
            ]));

            AssetImage::create([
                'asset_id' => $asset->id,
                'image_path' => $image,
                'caption' => 'Foto Aset ' . $asset->name,
                'is_primary' => true,
            ]);

            // Add sample AI analysis
            AiAnalysis::create([
                'asset_id' => $asset->id,
                'provider' => 'local_heuristic',
                'model' => 'gresik-asset-v1',
                'analysis_type' => 'comprehensive',
                'location_score' => $asset->location_score,
                'accessibility_score' => $asset->accessibility_score,
                'condition_score' => $asset->condition_score,
                'infrastructure_score' => $asset->infrastructure_score,
                'community_score' => $asset->community_demand_score,
                'economic_score' => $asset->economic_score,
                'potential_score' => $asset->potential_score,
                'confidence_score' => rand(82, 94),
                'summary' => "Aset {$asset->name} memiliki potensi ekonomi yang sangat menjanjikan dengan skor kelayakan {$asset->potential_score}/100. Disarankan untuk difokuskan pada pemanfaatan {$asset->target_activation_use}.",
                'recommendations' => [
                    ['rank' => 1, 'title' => $asset->target_activation_use, 'match_percentage' => rand(88, 95), 'category' => $asset->category->name, 'description' => 'Aktivasi utama berdasarkan kesesuaian ruang dan permintaan lokal.'],
                    ['rank' => 2, 'title' => 'Pusat Usaha & Jasa Komunitas', 'match_percentage' => rand(78, 86), 'category' => 'UMKM', 'description' => 'Alternatif pemanfaatan sekunder terpadu.'],
                ],
                'economic_scenarios' => [
                    ['code' => 'A', 'title' => $asset->target_activation_use, 'potential_score' => $asset->potential_score, 'estimated_tenants' => rand(5, 20), 'estimated_jobs' => rand(10, 35) . ' Orang', 'estimated_renovation' => 'Sedang', 'economic_impact' => 'Tinggi', 'rationale' => 'Paling sesuai dengan keunggulan komparatif wilayah.', 'confidence' => 90],
                ],
                'raw_response' => json_encode(['status' => 'success']),
            ]);

            // Add sample ideas
            AssetIdea::create([
                'asset_id' => $asset->id,
                'user_id' => $communityUser->id,
                'title' => 'Optimalisasi untuk ' . $asset->target_activation_use,
                'category' => $asset->category->name,
                'description' => 'Aspirasi warga agar aset ini dapat dikelola oleh BUMDes bersama Karang Taruna.',
                'votes_count' => rand(10, 50),
                'is_ai_recommended' => true,
            ]);

            // Add sample consensus
            CommunityConsensus::create([
                'asset_id' => $asset->id,
                'total_suggestions' => rand(5, 15),
                'dominant_category' => $asset->category->name,
                'consensus_summary' => "Masyarakat mengarahkan agar aset ini dihidupkan sebagai {$asset->target_activation_use}.",
                'clusters' => [
                    ['category' => $asset->category->name, 'percentage' => 60, 'votes' => 25],
                    ['category' => 'Lainnya', 'percentage' => 40, 'votes' => 15],
                ],
                'confidence_percentage' => rand(80, 92),
            ]);
        }

        // Create sample citizen reports
        AssetReport::create([
            'user_id' => $communityUser->id,
            'village_id' => $sukomulyo?->id,
            'category_id' => $catBangunan->id,
            'title' => 'Laporan Bangunan Bekas Koperasi Desa yang Kosong',
            'description' => 'Bangunan koperasi di dekat lapangan desa sudah 3 tahun tidak beroperasi. Kondisi atap masih bagus, halaman luas.',
            'condition' => 'tidak_digunakan',
            'suggested_use' => 'UMKM',
            'latitude' => -7.1360,
            'longitude' => 112.6040,
            'address' => 'Dusun Sukomulyo Barat, RT 02 RW 03',
            'status' => 'pending',
            'photos' => ['https://images.unsplash.com/photo-1513694203232-719a280e022f?auto=format&fit=crop&w=800&q=80'],
        ]);

        AssetReport::create([
            'user_id' => $communityUser->id,
            'village_id' => $suci?->id,
            'category_id' => $catTanah->id,
            'title' => 'Lahan Terbuka Dekat Perumahan Suci',
            'description' => 'Lahan kosong milik desa sering dijadikan tempat pembuangan sampah liar. Lebih baik ditata jadi taman kuliner UMKM.',
            'condition' => 'terbengkalai',
            'suggested_use' => 'Kuliner',
            'latitude' => -7.1430,
            'longitude' => 112.5950,
            'address' => 'Kawasan Perum PPS Blok C, Suci',
            'status' => 'pending',
            'photos' => ['https://images.unsplash.com/photo-1500382017468-9049fed747ef?auto=format&fit=crop&w=800&q=80'],
        ]);
    }
}
