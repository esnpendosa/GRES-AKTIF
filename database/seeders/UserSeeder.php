<?php

namespace Database\Seeders;

use App\Models\Badge;
use App\Models\District;
use App\Models\User;
use App\Models\Village;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $manyarDistrict = District::where('code', 'MANYAR')->first();
        $sukomulyoVillage = Village::where('code', '3525010001')->first();

        // 1. Community User (Masyarakat)
        $communityUser = User::updateOrCreate(
            ['email' => 'masyarakat@gresaktif.id'],
            [
                'name' => 'Ahmad Fauzi (Warga)',
                'password' => Hash::make('password'),
                'role' => 'community',
                'phone' => '081234567890',
                'district_id' => $manyarDistrict?->id,
                'village_id' => $sukomulyoVillage?->id,
                'points' => 170,
                'reputation_level' => 'Penggerak Ekonomi',
            ]
        );

        // 2. Village Admin (Pemerintah Desa Sukomulyo)
        $villageAdmin = User::updateOrCreate(
            ['email' => 'desa@gresaktif.id'],
            [
                'name' => 'Bpk. Subagio (Kepala Desa Sukomulyo)',
                'password' => Hash::make('password'),
                'role' => 'village_admin',
                'phone' => '081234567891',
                'district_id' => $manyarDistrict?->id,
                'village_id' => $sukomulyoVillage?->id,
                'points' => 320,
                'reputation_level' => 'Inovator Gresik',
            ]
        );

        // 3. District Admin (Pemerintah Kecamatan Manyar)
        $districtAdmin = User::updateOrCreate(
            ['email' => 'kecamatan@gresaktif.id'],
            [
                'name' => 'Drs. H. Zainal Arifin (Camat Manyar)',
                'password' => Hash::make('password'),
                'role' => 'district_admin',
                'phone' => '081234567892',
                'district_id' => $manyarDistrict?->id,
                'points' => 250,
                'reputation_level' => 'Inovator Gresik',
            ]
        );

        // 4. Regency Admin (Pemerintah Kab. Gresik - Bappeda / BPKAD)
        $regencyAdmin = User::updateOrCreate(
            ['email' => 'kabupaten@gresaktif.id'],
            [
                'name' => 'Ir. Hendro Wicaksono (Bappedalitbang Kab. Gresik)',
                'password' => Hash::make('password'),
                'role' => 'regency_admin',
                'phone' => '081234567893',
                'points' => 500,
                'reputation_level' => 'Inovator Gresik',
            ]
        );

        // 5. Super Admin
        $superAdmin = User::updateOrCreate(
            ['email' => 'admin@gresaktif.id'],
            [
                'name' => 'Administrator GRES-AKTIF',
                'password' => Hash::make('password'),
                'role' => 'super_admin',
                'phone' => '081234567899',
                'points' => 999,
                'reputation_level' => 'Inovator Gresik',
            ]
        );

        // Attach badges
        $badges = Badge::all();
        if ($badges->isNotEmpty()) {
            $communityUser->badges()->sync($badges->take(3)->pluck('id'));
            $villageAdmin->badges()->sync($badges->pluck('id'));
        }
    }
}
