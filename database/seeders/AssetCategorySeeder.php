<?php

namespace Database\Seeders;

use App\Models\AssetCategory;
use Illuminate\Database\Seeder;

class AssetCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Bangunan & Gedung Serbaguna',
                'slug' => 'bangunan-gedung',
                'icon' => 'building-2',
                'color' => '#0d9488', // teal
                'description' => 'Gedung serbaguna, balai desa lama, bekas kantor, gudang kosong, dan ruko desa.',
            ],
            [
                'name' => 'Tanah Kas Desa & Pekarangan',
                'slug' => 'tanah-kas-desa',
                'icon' => 'map-pin',
                'color' => '#0284c7', // blue
                'description' => 'Lahan tidur, pekarangan kosong, tanah bengkok, dan ruang terbuka belum produktif.',
            ],
            [
                'name' => 'Pasar Desa & Kios Rakyat',
                'slug' => 'pasar-desa',
                'icon' => 'store',
                'color' => '#f59e0b', // amber
                'description' => 'Pasar tradisional desa, sentra kios kosong, deretan lapak terbengkalai.',
            ],
            [
                'name' => 'Fasilitas Olahraga & Ruang Terbuka',
                'slug' => 'fasilitas-olahraga',
                'icon' => 'trophy',
                'color' => '#10b981', // green
                'description' => 'Lapangan desa, GOR mangkrak, sirkuit mini, dan taman bermain terbuka.',
            ],
            [
                'name' => 'Lahan Pertanian & Tambak',
                'slug' => 'pertanian-tambak',
                'icon' => 'sprout',
                'color' => '#84cc16', // lime
                'description' => 'Lahan pertanian non-produktif, tambak bandeng terlantar, dan perkebunan mangkrak.',
            ],
            [
                'name' => 'Fasilitas Wisata & Budaya',
                'slug' => 'wisata-budaya',
                'icon' => 'landmark',
                'color' => '#8b5cf6', // purple
                'description' => 'Rintisan desa wisata, spot cagar budaya, pesisir mangkrak, dan dermaga perahu.',
            ],
            [
                'name' => 'Infrastruktur & Sarana Publik',
                'slug' => 'infrastruktur-sarana',
                'icon' => 'network',
                'color' => '#64748b', // slate
                'description' => 'Jembatan timbang lama, bak penampungan air nonaktif, pos ronda terbengkalai.',
            ],
            [
                'name' => 'Fasilitas Pendidikan & Pelatihan',
                'slug' => 'pendidikan-pelatihan',
                'icon' => 'graduation-cap',
                'color' => '#06b6d4', // cyan
                'description' => 'Bekas gedung SD inpres merger, perpustakaan desa tutup, gedung PKBM kosong.',
            ],
        ];

        foreach ($categories as $cat) {
            AssetCategory::updateOrCreate(['slug' => $cat['slug']], $cat);
        }
    }
}
