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
     *         response="200",
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

        $hasRegister = InterestPoint::where('x', $request->get('x'))->where('y', $request->get('y'))->exists();

        if ($hasRegister) {
            return response()->json(['message' => 'Register already exists!'], 400);
        }

        $attributes = $request->only(['name', 'x', 'y', 'opened', 'closed', 'alwaysOpen']);

        $interestPoint = new InterestPoint($attributes);

        $saved = $this->interestPointService->create(interestPoint: $interestPoint);

        if (!$saved) {
            return response()->json(['message' => 'Interest Point not saved. Validate passed data and try again.'], 400);
        }

        return response()->json($interestPoint);
    }
}
