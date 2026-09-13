<?php

namespace Database\Seeders;

use App\Models\District;
use App\Models\Village;
use Illuminate\Database\Seeder;

class GresikRegionSeeder extends Seeder
{
    public function run(): void
    {
        $districts = [
            [
                'name' => 'Manyar',
                'code' => 'MANYAR',
                'latitude' => -7.1189,
                'longitude' => 112.5989,
                'area_sqkm' => 97.42,
                'total_villages' => 23,
                'villages' => [
                    ['name' => 'Sukomulyo', 'code' => '3525010001', 'postal_code' => '61151', 'latitude' => -7.1350, 'longitude' => 112.6020, 'area_sqkm' => 4.5, 'population' => 12450],
                    ['name' => 'Peganden', 'code' => '3525010002', 'postal_code' => '61151', 'latitude' => -7.1280, 'longitude' => 112.5850, 'area_sqkm' => 3.8, 'population' => 8900],
                    ['name' => 'Suci', 'code' => '3525010003', 'postal_code' => '61151', 'latitude' => -7.1420, 'longitude' => 112.5920, 'area_sqkm' => 5.2, 'population' => 15200],
                    ['name' => 'Yosowilangun', 'code' => '3525010004', 'postal_code' => '61151', 'latitude' => -7.1390, 'longitude' => 112.6100, 'area_sqkm' => 4.1, 'population' => 18400],
                    ['name' => 'Manyarejo', 'code' => '3525010005', 'postal_code' => '61151', 'latitude' => -7.1150, 'longitude' => 112.6050, 'area_sqkm' => 3.5, 'population' => 7800],
                ],
            ],
            [
                'name' => 'Kebomas',
                'code' => 'KEBOMAS',
                'latitude' => -7.1697,
                'longitude' => 112.6289,
                'area_sqkm' => 30.16,
                'total_villages' => 21,
                'villages' => [
                    ['name' => 'Kembangan', 'code' => '3525020001', 'postal_code' => '61161', 'latitude' => -7.1620, 'longitude' => 112.6210, 'area_sqkm' => 3.2, 'population' => 14300],
                    ['name' => 'Dahanrejo', 'code' => '3525020002', 'postal_code' => '61161', 'latitude' => -7.1750, 'longitude' => 112.6150, 'area_sqkm' => 2.9, 'population' => 9100],
                    ['name' => 'Kedanyang', 'code' => '3525020003', 'postal_code' => '61161', 'latitude' => -7.1820, 'longitude' => 112.6320, 'area_sqkm' => 3.7, 'population' => 11200],
                    ['name' => 'Giri', 'code' => '3525020004', 'postal_code' => '61161', 'latitude' => -7.1680, 'longitude' => 112.6390, 'area_sqkm' => 2.1, 'population' => 8400],
                ],
            ],
            [
                'name' => 'Gresik',
                'code' => 'GRESIK',
                'latitude' => -7.1566,
                'longitude' => 112.6555,
                'area_sqkm' => 5.37,
                'total_villages' => 21,
                'villages' => [
                    ['name' => 'Bedilan', 'code' => '3525030001', 'postal_code' => '61114', 'latitude' => -7.1580, 'longitude' => 112.6530, 'area_sqkm' => 0.8, 'population' => 6500],
                    ['name' => 'Gapurosukolilo', 'code' => '3525030002', 'postal_code' => '61119', 'latitude' => -7.1540, 'longitude' => 112.6580, 'area_sqkm' => 0.9, 'population' => 7200],
                    ['name' => 'Tlogopojok', 'code' => '3525030003', 'postal_code' => '61118', 'latitude' => -7.1510, 'longitude' => 112.6610, 'area_sqkm' => 1.2, 'population' => 9800],
                ],
            ],
            [
                'name' => 'Menganti',
                'code' => 'MENGANTI',
                'latitude' => -7.2600,
                'longitude' => 112.5800,
                'area_sqkm' => 68.73,
                'total_villages' => 22,
                'villages' => [
                    ['name' => 'Hulaan', 'code' => '3525040001', 'postal_code' => '61174', 'latitude' => -7.2650, 'longitude' => 112.5750, 'area_sqkm' => 3.9, 'population' => 8700],
                    ['name' => 'Drancang', 'code' => '3525040002', 'postal_code' => '61174', 'latitude' => -7.2580, 'longitude' => 112.5890, 'area_sqkm' => 3.4, 'population' => 6900],
                    ['name' => 'Menganti', 'code' => '3525040003', 'postal_code' => '61174', 'latitude' => -7.2510, 'longitude' => 112.5820, 'area_sqkm' => 4.2, 'population' => 13500],
                ],
            ],
            [
                'name' => 'Driyorejo',
                'code' => 'DRIYOREJO',
                'latitude' => -7.3400,
                'longitude' => 112.6200,
                'area_sqkm' => 51.29,
                'total_villages' => 16,
                'villages' => [
                    ['name' => 'Krikilan', 'code' => '3525050001', 'postal_code' => '61177', 'latitude' => -7.3450, 'longitude' => 112.6150, 'area_sqkm' => 4.1, 'population' => 11200],
                    ['name' => 'Petiken', 'code' => '3525050002', 'postal_code' => '61177', 'latitude' => -7.3380, 'longitude' => 112.6280, 'area_sqkm' => 3.8, 'population' => 14800],
                ],
            ],
            [
                'name' => 'Cerme',
                'code' => 'CERME',
                'latitude' => -7.2100,
                'longitude' => 112.5600,
                'area_sqkm' => 71.73,
                'total_villages' => 25,
                'villages' => [
                    ['name' => 'Cerme Lor', 'code' => '3525060001', 'postal_code' => '61171', 'latitude' => -7.2050, 'longitude' => 112.5580, 'area_sqkm' => 3.5, 'population' => 7600],
                    ['name' => 'Betiting', 'code' => '3525060002', 'postal_code' => '61171', 'latitude' => -7.2180, 'longitude' => 112.5640, 'area_sqkm' => 2.8, 'population' => 6400],
                ],
            ],
            [
                'name' => 'Sidayu',
                'code' => 'SIDAYU',
                'latitude' => -6.9900,
                'longitude' => 112.5600,
                'area_sqkm' => 47.78,
                'total_villages' => 21,
                'villages' => [
                    ['name' => 'Bunderan', 'code' => '3525070001', 'postal_code' => '61153', 'latitude' => -6.9850, 'longitude' => 112.5550, 'area_sqkm' => 2.7, 'population' => 5400],
                    ['name' => 'Kauman', 'code' => '3525070002', 'postal_code' => '61153', 'latitude' => -6.9920, 'longitude' => 112.5620, 'area_sqkm' => 1.8, 'population' => 4200],
                ],
            ],
            [
                'name' => 'Bungah',
                'code' => 'BUNGAH',
                'latitude' => -7.0500,
                'longitude' => 112.5700,
                'area_sqkm' => 79.80,
                'total_villages' => 22,
                'villages' => [
                    ['name' => 'Bungah', 'code' => '3525080001', 'postal_code' => '61152', 'latitude' => -7.0450, 'longitude' => 112.5680, 'area_sqkm' => 3.1, 'population' => 6800],
                    ['name' => 'Sungonlegowo', 'code' => '3525080002', 'postal_code' => '61152', 'latitude' => -7.0580, 'longitude' => 112.5750, 'area_sqkm' => 4.2, 'population' => 5900],
                ],
            ],
            [
                'name' => 'Dukun',
                'code' => 'DUKUN',
                'latitude' => -7.0100,
                'longitude' => 112.4900,
                'area_sqkm' => 59.08,
                'total_villages' => 26,
                'villages' => [
                    ['name' => 'Mentaras', 'code' => '3525090001', 'postal_code' => '61155', 'latitude' => -7.0080, 'longitude' => 112.4850, 'area_sqkm' => 2.9, 'population' => 4800],
                    ['name' => 'Gedongkedoan', 'code' => '3525090002', 'postal_code' => '61155', 'latitude' => -7.0120, 'longitude' => 112.4920, 'area_sqkm' => 3.1, 'population' => 5200],
                ],
            ],
            [
                'name' => 'Ujungpangkah',
                'code' => 'UJUNGPANGKAH',
                'latitude' => -6.9100,
                'longitude' => 112.5500,
                'area_sqkm' => 94.82,
                'total_villages' => 13,
                'villages' => [
                    ['name' => 'Pangkahkulon', 'code' => '3525100001', 'postal_code' => '61154', 'latitude' => -6.9050, 'longitude' => 112.5450, 'area_sqkm' => 6.2, 'population' => 7300],
                ],
            ],
            [
                'name' => 'Panceng',
                'code' => 'PANCENG',
                'latitude' => -6.9200,
                'longitude' => 112.4500,
                'area_sqkm' => 62.79,
                'total_villages' => 14,
                'villages' => [
                    ['name' => 'Dalegan', 'code' => '3525110001', 'postal_code' => '61156', 'latitude' => -6.9150, 'longitude' => 112.4450, 'area_sqkm' => 5.4, 'population' => 6100],
                ],
            ],
            [
                'name' => 'Balongpanggang',
                'code' => 'BALONGPANGGANG',
                'latitude' => -7.2700,
                'longitude' => 112.4600,
                'area_sqkm' => 63.88,
                'total_villages' => 25,
                'villages' => [
                    ['name' => 'Kedungsumber', 'code' => '3525120001', 'postal_code' => '61173', 'latitude' => -7.2650, 'longitude' => 112.4550, 'area_sqkm' => 4.1, 'population' => 5200],
                ],
            ],
            [
                'name' => 'Benjeng',
                'code' => 'BENJENG',
                'latitude' => -7.2400,
                'longitude' => 112.5000,
                'area_sqkm' => 66.33,
                'total_villages' => 23,
                'villages' => [
                    ['name' => 'Bulurejo', 'code' => '3525130001', 'postal_code' => '61172', 'latitude' => -7.2350, 'longitude' => 112.4950, 'area_sqkm' => 3.7, 'population' => 5800],
                ],
            ],
            [
                'name' => 'Wringinanom',
                'code' => 'WRINGINANOM',
                'latitude' => -7.3800,
                'longitude' => 112.5300,
                'area_sqkm' => 62.62,
                'total_villages' => 16,
                'villages' => [
                    ['name' => 'Wringinanom', 'code' => '3525140001', 'postal_code' => '61176', 'latitude' => -7.3750, 'longitude' => 112.5250, 'area_sqkm' => 4.8, 'population' => 8400],
                ],
            ],
            [
                'name' => 'Kedamean',
                'code' => 'KEDAMEAN',
                'latitude' => -7.3100,
                'longitude' => 112.5600,
                'area_sqkm' => 65.95,
                'total_villages' => 15,
                'villages' => [
                    ['name' => 'Slempit', 'code' => '3525150001', 'postal_code' => '61175', 'latitude' => -7.3050, 'longitude' => 112.5550, 'area_sqkm' => 4.3, 'population' => 7100],
                ],
            ],
            [
                'name' => 'Duduksampeyan',
                'code' => 'DUDUKSAMPEYAN',
                'latitude' => -7.1600,
                'longitude' => 112.5300,
                'area_sqkm' => 74.29,
                'total_villages' => 23,
                'villages' => [
                    ['name' => 'Duduksampeyan', 'code' => '3525160001', 'postal_code' => '61162', 'latitude' => -7.1550, 'longitude' => 112.5250, 'area_sqkm' => 3.6, 'population' => 6300],
                ],
            ],
            [
                'name' => 'Sangkapura',
                'code' => 'SANGKAPURA',
                'latitude' => -5.8500,
                'longitude' => 112.6500,
                'area_sqkm' => 118.72,
                'total_villages' => 17,
                'villages' => [
                    ['name' => 'Kotakusuma', 'code' => '3525170001', 'postal_code' => '61181', 'latitude' => -5.8550, 'longitude' => 112.6450, 'area_sqkm' => 4.2, 'population' => 6200],
                    ['name' => 'Sungairujing', 'code' => '3525170002', 'postal_code' => '61181', 'latitude' => -5.8620, 'longitude' => 112.6580, 'area_sqkm' => 5.1, 'population' => 5400],
                ],
            ],
            [
                'name' => 'Tambak',
                'code' => 'TAMBAK',
                'latitude' => -5.7500,
                'longitude' => 112.6800,
                'area_sqkm' => 77.55,
                'total_villages' => 13,
                'villages' => [
                    ['name' => 'Tambak', 'code' => '3525180001', 'postal_code' => '61182', 'latitude' => -5.7480, 'longitude' => 112.6780, 'area_sqkm' => 4.8, 'population' => 5100],
                    ['name' => 'Diponggo', 'code' => '3525180002', 'postal_code' => '61182', 'latitude' => -5.7350, 'longitude' => 112.6650, 'area_sqkm' => 3.9, 'population' => 4300],
                ],
            ],
        ];

        foreach ($districts as $dData) {
            $villages = $dData['villages'];
            unset($dData['villages']);

            $district = District::updateOrCreate(['code' => $dData['code']], $dData);

            foreach ($villages as $vData) {
                $vData['district_id'] = $district->id;
                Village::updateOrCreate(['code' => $vData['code']], $vData);
            }
        }
    }
}
