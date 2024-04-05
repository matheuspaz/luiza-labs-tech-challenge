<?php

namespace App\Http\Controllers;

use App\Services\AuthenticationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthenticationController extends Controller
{
    private AuthenticationService $authenticationService;

    public function __construct(AuthenticationService $authenticationService)
    {
        $this->authenticationService = $authenticationService;
    }

    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        $credentials = $request->only('email', 'password');

        if (!$this->authenticationService->validateCredentials(credentials: $credentials)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $token = $this->authenticationService->generateToken($request->user());

        return response()->json([
            'accessToken' => $token,
            'tokenType' => 'Bearer'
        ]);
    }
}
