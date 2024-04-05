<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\AuthenticationService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationServiceTest extends TestCase
{
    use DatabaseTransactions;

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
     * Validate credentials test
     */
    public function testValidateCredentials(): void
    {
        /**
         * @var AuthenticationService $authenticationService
         */
        $authenticationService = app(AuthenticationService::class);

        $user = [
            'email' => 'user@test.com',
            'password' => 123456,
        ];

        User::create([
            'name' => 'User Test',
            'email' => $user['email'],
            'password' => Hash::make($user['password']),
            'remember_token' => Hash::make('remember-token')
        ]);

        $isValidCredentials = $authenticationService->validateCredentials($user);
        $this->assertTrue($isValidCredentials, 'Assert true if credentials are valid.');

        $user['password'] = 123123;
        $isValidCredentials = $authenticationService->validateCredentials($user);
        $this->assertFalse($isValidCredentials, 'Assert false if credentials are invalid.');
    }

    /**
     * Generate token test
     */
    public function testGenerateToken(): void
    {
        /**
         * @var AuthenticationService $authenticationService
         */
        $authenticationService = app(AuthenticationService::class);

        $user = [
            'email' => 'user@test.com',
            'password' => 123456,
        ];

        $user = new User([
            'name' => 'User Test',
            'email' => $user['email'],
            'password' => Hash::make($user['password']),
            'remember_token' => Hash::make('remember-token')
        ]);

        $user->save();

        $token = $authenticationService->generateToken($user);
        $this->assertIsString($token, 'Assert if token is string.');
    }
}
