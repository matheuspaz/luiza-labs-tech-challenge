<?php

namespace App\Http\Controllers;

use OpenApi\Annotations as OA;
use App\Services\AuthenticationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthenticationController extends Controller
{
    /**
     * Instance of AuthenticationService
     */
    private AuthenticationService $authenticationService;

    /**
     * @param AuthenticationService $authenticationService DI service object
     */
    public function __construct(AuthenticationService $authenticationService)
    {
        $this->authenticationService = $authenticationService;
    }

    /**
     * Controller method to do login in API
     *
     * This method expect two parameters in body of request:
     * - email: string
     * - password: string
     *
     * @param Request $request Illuminate Http Request Object
     * @return JsonResponse
     *
     * @OA\Post(
     *     path="/api/auth/login",
     *     summary="API login",
     *     description="Authenticate user and generate access token",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="User credentials",
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email", example="admin@digitalmaps.com"),
     *             @OA\Property(property="password", type="string", example="digital-maps")
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Successful login",
     *         @OA\JsonContent(
     *             @OA\Property(property="accessToken", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."),
     *             @OA\Property(property="tokenType", type="string", example="Bearer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Entity",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The password field is required."),
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="password", type="array",
     *                     @OA\Items(type="string", example="The password field is required.")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthorized")
     *         )
     *     )
     * )
     */
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
