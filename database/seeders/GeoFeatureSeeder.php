<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GeoFeature;
use App\Models\User;

class GeoFeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get first user or create one
        $user = User::first() ?? User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
        ]);

        // Sample Markers (Points of Interest)
        $markers = [
            [
                'nama' => 'Tanah Lot Temple',
                'deskripsi' => 'Ancient Hindu shrine located on a rock formation',
                'kategori_id' => 1,
                'user_id' => $user->id,
                'geometry_type' => 'marker',
                'coordinates' => ['lat' => -8.621212, 'lng' => 115.086844],
                'properties' => ['icon' => 'temple', 'color' => 'red']
            ],
            [
                'nama' => 'Ubud Monkey Forest',
                'deskripsi' => 'Nature reserve and Hindu temple complex',
                'kategori_id' => 2,
                'user_id' => $user->id,
                'geometry_type' => 'marker',
                'coordinates' => ['lat' => -8.518611, 'lng' => 115.258889],
                'properties' => ['icon' => 'forest', 'color' => 'green']
            ],
            [
                'nama' => 'Kuta Beach',
                'deskripsi' => 'Popular beach destination',
                'kategori_id' => 3,
                'user_id' => $user->id,
                'geometry_type' => 'marker',
                'coordinates' => ['lat' => -8.718409, 'lng' => 115.169144],
                'properties' => ['icon' => 'beach', 'color' => 'blue']
            ],
            [
                'nama' => 'Mount Batur',
                'deskripsi' => 'Active volcano in Kintamani',
                'kategori_id' => 4,
                'user_id' => $user->id,
                'geometry_type' => 'marker',
                'coordinates' => ['lat' => -8.242222, 'lng' => 115.375278],
                'properties' => ['icon' => 'mountain', 'color' => 'orange']
            ],
            [
                'nama' => 'Seminyak Square',
                'deskripsi' => 'Shopping and dining area',
                'kategori_id' => 5,
                'user_id' => $user->id,
                'geometry_type' => 'marker',
                'coordinates' => ['lat' => -8.686111, 'lng' => 115.161111],
                'properties' => ['icon' => 'shopping', 'color' => 'purple']
            ]
        ];

        foreach ($markers as $marker) {
            GeoFeature::create($marker);
        }

        // Sample Polygons (Areas)
        $polygons = [
            [
                'nama' => 'Nusa Dua Resort Area',
                'deskripsi' => 'Luxury resort and hotel zone',
                'kategori_id' => 6,
                'user_id' => $user->id,
                'geometry_type' => 'polygon',
                'coordinates' => [
                    ['lat' => -8.792, 'lng' => 115.226],
                    ['lat' => -8.792, 'lng' => 115.236],
                    ['lat' => -8.802, 'lng' => 115.236],
                    ['lat' => -8.802, 'lng' => 115.226],
                    ['lat' => -8.792, 'lng' => 115.226]
                ],
                'properties' => ['fillColor' => '#3388ff', 'fillOpacity' => 0.3]
            ],
            [
                'nama' => 'Ubud Art District',
                'deskripsi' => 'Traditional art and craft center',
                'kategori_id' => 7,
                'user_id' => $user->id,
                'geometry_type' => 'polygon',
                'coordinates' => [
                    ['lat' => -8.505, 'lng' => 115.255],
                    ['lat' => -8.505, 'lng' => 115.265],
                    ['lat' => -8.515, 'lng' => 115.265],
                    ['lat' => -8.515, 'lng' => 115.255],
                    ['lat' => -8.505, 'lng' => 115.255]
                ],
                'properties' => ['fillColor' => '#ff7800', 'fillOpacity' => 0.3]
            ]
        ];

        foreach ($polygons as $polygon) {
            GeoFeature::create($polygon);
        }

        // Sample Polylines (Routes/Roads)
        $polylines = [
            [
                'nama' => 'Jalan Raya Ubud',
                'deskripsi' => 'Main road through Ubud',
                'kategori_id' => 8,
                'user_id' => $user->id,
                'geometry_type' => 'polyline',
                'coordinates' => [
                    ['lat' => -8.506, 'lng' => 115.256],
                    ['lat' => -8.510, 'lng' => 115.260],
                    ['lat' => -8.514, 'lng' => 115.264]
                ],
                'properties' => ['color' => '#FF0000', 'weight' => 5]
            ],
            [
                'nama' => 'Jalan Sunset Road',
                'deskripsi' => 'Major road in Seminyak',
                'kategori_id' => 8,
                'user_id' => $user->id,
                'geometry_type' => 'polyline',
                'coordinates' => [
                    ['lat' => -8.686, 'lng' => 115.161],
                    ['lat' => -8.690, 'lng' => 115.165],
                    ['lat' => -8.694, 'lng' => 115.169]
                ],
                'properties' => ['color' => '#0000FF', 'weight' => 5]
            ]
        ];

        foreach ($polylines as $polyline) {
            GeoFeature::create($polyline);
        }

        // Sample Circles (Coverage Areas)
        $circles = [
            [
                'nama' => 'Wifi Hotspot - Seminyak Beach',
                'deskripsi' => 'Free public wifi coverage',
                'kategori_id' => 9,
                'user_id' => $user->id,
                'geometry_type' => 'circle',
                'coordinates' => ['lat' => -8.686, 'lng' => 115.161],
                'properties' => [
                    'radius' => 500,
                    'color' => '#00FF00',
                    'fillColor' => '#00FF00',
                    'fillOpacity' => 0.2
                ]
            ],
            [
                'nama' => 'Emergency Response Zone',
                'deskripsi' => 'Fire station coverage area',
                'kategori_id' => 10,
                'user_id' => $user->id,
                'geometry_type' => 'circle',
                'coordinates' => ['lat' => -8.650, 'lng' => 115.220],
                'properties' => [
                    'radius' => 1000,
                    'color' => '#FF0000',
                    'fillColor' => '#FF0000',
                    'fillOpacity' => 0.15
                ]
            ]
        ];

        foreach ($circles as $circle) {
            GeoFeature::create($circle);
        }

        $this->command->info('Created ' . count($markers) . ' markers');
        $this->command->info('Created ' . count($polygons) . ' polygons');
        $this->command->info('Created ' . count($polylines) . ' polylines');
        $this->command->info('Created ' . count($circles) . ' circles');
    }
}
