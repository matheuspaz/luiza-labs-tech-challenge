<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Cliente PF 1',
            'email' => 'cliente1@cliente1.com',
            'password' => Hash::make('123456')
        ]);

        User::factory()->create([
            'name' => 'Cliente PF 2',
            'email' => 'cliente2@cliente2.com',
            'password' => Hash::make('654321')
        ]);

        User::factory()->create([
            'name' => 'Cliente PJ 1',
            'email' => 'pj1@pj1.com',
            'password' => Hash::make('999888')
        ]);

        User::factory()->create([
            'name' => 'Cliente PJ 2',
            'email' => 'pj2@pj1.com',
            'password' => Hash::make('777666')
        ]);
    }
}
