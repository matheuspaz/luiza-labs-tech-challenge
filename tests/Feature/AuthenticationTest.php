<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    /**
     * Set up Sqlite with data
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate');
        $this->seed();
    }

    /**
     * Test login full feature
     */
    public function testLogin(): void
    {
        $userCredentials = [
            'email' => 'user@test.com',
            'password' => '123456',
        ];

        $user = new User([
            'name' => 'User Test',
            'email' => $userCredentials['email'],
            'password' => Hash::make($userCredentials['password']),
            'remember_token' => Hash::make('remember-token')
        ]);

        $user->save();

        $response = $this->postJson('/api/auth/login', [
            'email' => $userCredentials['email'],
            'password' => $userCredentials['password']
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'accessToken',
                'tokenType'
            ]);
    }
}
