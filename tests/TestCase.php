<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Hash;

abstract class TestCase extends BaseTestCase
{
    protected function createAuthToken()
    {
        $userCredentials = [
            'email' => 'teste@teste.com',
            'password' => '123456'
        ];

        User::create([
            'name' => 'User Test',
            'email' => $userCredentials['email'],
            'password' => Hash::make($userCredentials['password']),
            'remember_token' => Hash::make('remember-token')
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => $userCredentials['email'],
            'password' => $userCredentials['password']
        ]);

        return $response->json('accessToken');
    }
}
