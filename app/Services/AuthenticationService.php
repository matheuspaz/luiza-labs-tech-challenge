<?php

namespace App\Services;

use App\Models\User;

class AuthenticationService
{
    /**
     * Generate new access token.
     *
     * Using user model to generate new token.
     *
     * @param User $user Instace of user model.
     * @return string Plain text token
     */
    public function generateToken(User $user): string
    {
        return $user->createToken(time())->plainTextToken;
    }

    /**
     * Verify if credentials are valid.
     *
     * Using auth helper, verify if user credentials are valid using attemps helper chain.
     *
     * @param array credentials [email, password]
     * @return boolean
     */
    public function validateCredentials(array $credentials): bool
    {
        return auth()->attempt($credentials);
    }
}
