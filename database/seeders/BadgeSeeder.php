<?php

namespace Database\Seeders;

use App\Models\Badge;
use Illuminate\Database\Seeder;

class BadgeSeeder extends Seeder
{
    public function run(): void
    {
        $badges = [
            [
                'name' => 'Pengamat Desa',
                'slug' => 'pengamat-desa',
                'icon' => 'eye',
                'description' => 'Telah berkontribusi melaporkan setidaknya 1 aset tidur di desanya.',
                'min_points' => 20,
            ],
            [
                'name' => 'Kontributor Desa',
                'slug' => 'kontributor-desa',
                'icon' => 'sparkles',
                'description' => 'Aktif memberikan ide dan aspirasi pemanfaatan produktif untuk aset desa.',
                'min_points' => 50,
            ],
            [
                'name' => 'Penggerak Ekonomi',
                'slug' => 'penggerak-ekonomi',
                'icon' => 'trending-up',
                'description' => 'Mendapatkan dukungan luas dari komunitas dan laporan berhasil divalidasi.',
                'min_points' => 150,
            ],
            [
                'name' => 'Local Innovator',
                'slug' => 'local-innovator',
                'icon' => 'award',
                'description' => 'Inovator tingkat kabupaten yang gagasannya diangkat menjadi proyek aktivasi nyata.',
                'min_points' => 300,
            ],
        ];

        foreach ($badges as $badge) {
            Badge::updateOrCreate(['slug' => $badge['slug']], $badge);
        }
    }
}
