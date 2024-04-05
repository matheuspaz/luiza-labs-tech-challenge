<?php

namespace App\Http\Controllers;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *   title="Digital Maps API by Luiza Labs ",
 *   version="1.0",
 *   description="Available endpoints from Digital Maps Service by Luiza Labs."
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
