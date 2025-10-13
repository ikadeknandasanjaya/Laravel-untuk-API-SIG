<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Wisata;

class WisataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $wisatas = [
            [
                'nama' => 'Pantai Kuta',
                'alamat' => 'Kuta, Badung, Bali',
                'lat' => -8.7183,
                'lng' => 115.1686,
                'kategori' => 'alam'
            ],
            [
                'nama' => 'Pura Tanah Lot',
                'alamat' => 'Beraban, Kediri, Tabanan, Bali',
                'lat' => -8.6211,
                'lng' => 115.0864,
                'kategori' => 'budaya'
            ],
            [
                'nama' => 'Gunung Batur',
                'alamat' => 'Kintamani, Bangli, Bali',
                'lat' => -8.2430,
                'lng' => 115.3760,
                'kategori' => 'alam'
            ],
            [
                'nama' => 'Taman Ujung Sukasada',
                'alamat' => 'Taman Ujung, Karangasem, Bali',
                'lat' => -8.4367,
                'lng' => 115.5894,
                'kategori' => 'budaya'
            ],
            [
                'nama' => 'Pantai Sanur',
                'alamat' => 'Sanur, Denpasar, Bali',
                'lat' => -8.6847,
                'lng' => 115.2611,
                'kategori' => 'alam'
            ],
            [
                'nama' => 'Pasar Sukawati',
                'alamat' => 'Sukawati, Gianyar, Bali',
                'lat' => -8.6144,
                'lng' => 115.2947,
                'kategori' => 'belanja'
            ],
            [
                'nama' => 'Air Terjun Tegenungan',
                'alamat' => 'Tegenungan, Gianyar, Bali',
                'lat' => -8.5697,
                'lng' => 115.2714,
                'kategori' => 'alam'
            ],
            [
                'nama' => 'Museum Puri Lukisan',
                'alamat' => 'Ubud, Gianyar, Bali',
                'lat' => -8.5069,
                'lng' => 115.2625,
                'kategori' => 'budaya'
            ]
        ];

        foreach ($wisatas as $wisata) {
            Wisata::create($wisata);
        }
    }
}
