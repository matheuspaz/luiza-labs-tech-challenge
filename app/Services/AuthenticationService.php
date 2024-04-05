<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\AuthenticationRepository;
use Error;
use Illuminate\Support\Facades\Hash;

class AuthenticationService
{
    private AuthenticationRepository $authRepository;

    public function __construct(AuthenticationRepository $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function generateToken(User $user): string
    {
        return $user->createToken('Digital Maps API Token')->plainTextToken;
    }

    public function validateCredentials(array $credentials): bool
    {
        return auth()->attempt($credentials);
    }
}
