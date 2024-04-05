<?php

namespace Database\Seeders;

use App\Models\InterestPoint;
use Illuminate\Database\Seeder;

class InterestPointSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $interestPoints = [
            [
                'name' => 'Restaurante',
                'x' => 27,
                'y' => 12,
                'opened' => '12:00:00',
                'closed' => '18:00:00',
            ],
            [
                'name' => 'Posto de combustível',
                'x' => 31,
                'y' => 18,
                'opened' => '08:00:00',
                'closed' => '18:00:00',
            ],
            [
                'name' => 'Praça',
                'x' => 15,
                'y' => 12,
                'always_open' => true,
            ]
        ];

        foreach ($interestPoints as $interestPoint) {
            InterestPoint::create($interestPoint);
        }
    }
}
