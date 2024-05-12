<?php

namespace App\Http\Controllers;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *   title="PP Simplificado",
 *   version="1.0",
 *   description="Available endpoints from PP Simplificado."
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Authentication token (JWT)"
 * )
 */
abstract class Controller
{
    //
}
