<?php

namespace App\Http\Controllers;

use OpenApi\Annotations as OA;
use App\Models\InterestPoint;
use App\Services\InterestPointService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InterestPointController extends Controller
{
    /**
     * Instance of InterestPointService.
     */
    private InterestPointService $interestPointService;

    /**
     * @param InterestPointService $interestPointService DI service object
     */
    public function __construct(InterestPointService $interestPointService)
    {
        $this->interestPointService = $interestPointService;
    }

    /**
     * Controller method to create an Interest Point
     *
     * This method expect two parameters in body of request:
     * - name: string
     * - x: string
     * - y: string
     * - opened: time (string)
     * - closed: time (string)
     * - alwaysOpend: boolean
     *
     * @param Request $request Illuminate Http Request Object
     * @return JsonResponse
     *
     * @OA\Post(
     *     path="/api/interest-points",
     *     summary="Create a new interest point",
     *     description="Create a new interest point with the provided data.",
     *     tags={"Interest Points"},
     *     security={{ "bearerAuth":{} }},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Data of the new interest point",
     *         @OA\JsonContent(
     *             required={"name", "x", "y", "opened", "closed", "alwaysOpen"},
     *             @OA\Property(property="name", type="string", example="Interest Point 1"),
     *             @OA\Property(property="x", type="integer", example=27),
     *             @OA\Property(property="y", type="integer", example=12),
     *             @OA\Property(property="opened", type="string", format="time", example="12:00:00"),
     *             @OA\Property(property="closed", type="string", format="time", example="18:00:00"),
     *             @OA\Property(property="alwaysOpen", type="boolean", example=false)
     *         )
     *     ),
     *     @OA\Response(
     *         response="201",
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Interest Point 1"),
     *             @OA\Property(property="x", type="integer", example=27),
     *             @OA\Property(property="y", type="integer", example=12),
     *             @OA\Property(property="opened", type="string", format="time", example="12:00:00"),
     *             @OA\Property(property="closed", type="string", format="time", example="18:00:00"),
     *             @OA\Property(property="alwaysOpen", type="boolean", example=false)
     *         )
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Bad Request",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Register already exists!"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The name field is required. (and 5 more errors)"),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(property="name", type="array", @OA\Items(type="string", example="The name field is required.")),
     *                 @OA\Property(property="x", type="array", @OA\Items(type="string", example="The x field is required.")),
     *                 @OA\Property(property="y", type="array", @OA\Items(type="string", example="The y field is required.")),
     *                 @OA\Property(property="opened", type="array", @OA\Items(type="string", example="The opened field is required when always open is not present.")),
     *                 @OA\Property(property="closed", type="array", @OA\Items(type="string", example="The closed field is required when always open is not present.")),
     *                 @OA\Property(property="alwaysOpen", type="array", @OA\Items(type="string", example="The always open field is required when closed is not present.")),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Server Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Interest Point not saved. Validate passed data and try again."),
     *         )
     *     )
     * )
     */
    public function create(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string',
            'x' => 'required|integer|min:0',
            'y' => 'required|integer|min:0',
            'opened' => 'required_without:alwaysOpen|date_format:H:i:s',
            'closed' => 'required_without:alwaysOpen|date_format:H:i:s',
            'alwaysOpen' => 'required_without:closed|required_without:opened|boolean'
        ]);

        $attributes = $request->only(['name', 'x', 'y', 'opened', 'closed', 'alwaysOpen']);

        $interestPoint = new InterestPoint($attributes);

        if (isset($attributes['alwaysOpen'])) {
            $interestPoint->always_open = $attributes['alwaysOpen'];
        }

        $hasRegister = $this->interestPointService->exists(interestPoint: $interestPoint);

        if ($hasRegister) {
            return response()->json(['message' => 'Register already exists!'], 400);
        }

        $saved = $this->interestPointService->create(interestPoint: $interestPoint);

        if (!$saved) {
            return response()->json(['message' => 'Interest Point not saved. Validate passed data and try again.'], 400);
        }

        return response()->json($interestPoint, 201);
    }


    /**
     * Controller method to list Interest Points
     *
     * This method has validations when filters are applyed, available filters are:
     * - x: string
     * - y: string
     * - hr: time (string)
     * - mts: integer
     *
     * @param Request $request Illuminate Http Request Object
     * @return JsonResponse
     *
     * @OA\Get(
     *     path="/api/interest-points",
     *     summary="List interest points",
     *     description="List interest points based on provided filters.",
     *     tags={"Interest Points"},
     *     security={{ "bearerAuth":{} }},
     *     @OA\Parameter(
     *         name="x",
     *         in="query",
     *         description="X coordinate",
     *         @OA\Schema(type="integer", example=27)
     *     ),
     *     @OA\Parameter(
     *         name="y",
     *         in="query",
     *         description="Y coordinate",
     *         @OA\Schema(type="integer", example=12)
     *     ),
     *     @OA\Parameter(
     *         name="hr",
     *         in="query",
     *         description="Hour",
     *         @OA\Schema(type="string", format="time", example="12:00:00")
     *     ),
     *     @OA\Parameter(
     *         name="mts",
     *         in="query",
     *         description="Distance in meters",
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Success",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="name", type="string", example="Restaurante"),
     *                 @OA\Property(property="status", type="string", example="Fechado")
     *             ),
     *              @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="name", type="string", example="Praça Central"),
     *                 @OA\Property(property="status", type="string", example="Aberto")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Unprocessable Entity",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object", example={
     *                 "x": {"The x field is required."},
     *                 "y": {"The y field is required."},
     *                 "hr": {"The hr field is required."},
     *                 "mts": {"The mts field is required."}
     *             })
     *         )
     *     )
     * )
     */
    public function list(Request $request): JsonResponse
    {
        $request->validate([
            'x' => 'required_with:y|required_with:hr|required_with:mts|integer|min:0',
            'y' => 'required_with:x|required_with:hr|required_with:mts|integer|min:0',
            'hr' => 'required_with:y|required_with:x|required_with:mts|date_format:H:i:s',
            'mts' => 'required_with:y|required_with:hr|required_with:x|integer|min:0'
        ]);

        $filters = $request->only(['x', 'y', 'hr', 'mts']);

        $interestPoints = $this->interestPointService->list(filters: $filters);

        return response()->json($interestPoints);
    }
}
