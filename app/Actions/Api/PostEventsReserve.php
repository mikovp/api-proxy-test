<?php

namespace App\Actions\Api;

use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\ActionRequest;
use Illuminate\Support\Facades\Http;

/**
 * @OA\Post(
 *      path="/api/events/{eventId}/reserve",
 *      summary="Reserve places for an event",
 *      description="Reserve places for an event",
 *      operationId="postEventsReserve",
 *      tags={"API"},
 *      @OA\Parameter(
 *          name="eventId",
 *          in="path",
 *          description="ID of event to reserve places",
 *          required=true,
 *      @OA\Schema(
 *          type="integer",
 *          format="int64"
 *          )
 *      ),
 *      @OA\RequestBody(
 *          required=true,
 *      @OA\JsonContent(
 *          @OA\Property(
 *              property="name",
 *              type="string",
 *              example="John Doe"
 *          ),
 *      @OA\Property(
 *          property="places",
 *          type="array",
 *          @OA\Items(
 *              type="integer",
 *              example="1"
 *          )
 * )
 * )
 * ),
 * @OA\Response(
 *       response=200,
 *       description="Successful operation",
 *       @OA\JsonContent(
 *           @OA\Property(
 *              property="status",
 *              type="string",
 *              example="success"
 *           ),
 *          @OA\Property(
 *              property="message",
 *              type="string",
 *              example="Places reserved successfully"
 *          ),
 *          @OA\Property(
 *              property="response",
 *              type="object",
 *          @OA\Property(
 *              property="success",
 *              type="string",
 *              example="success"
 *          ),
 *          @OA\Property(
 *              property="reservation_id",
 *              type="string",
 *              example="66faf1ca12aaa"
 *          )
 * )
 * )
 * )
 * )
 * )
 */

class PostEventsReserve
{
    use AsAction;

    /**
     * Validate and sanitize input.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'places' => ['required', 'array'],
        ];
    }

    /**
     * Validation failed response.
     * 
     * @return void
     */
    public function getValidationFailure()
    {
        throw new \App\Exceptions\ValidationFailed();
    }

    /**
     * Handle the incoming request.
     *
     * @return mixed
     */
    public function handle(int $eventId, array $data)
    {
        $url = config('api.url') . '/events/' . $eventId . '/reserve';
        $bearerToken = config('api.bearer_token');

        $response = Http::withHeaders([
            'Authorization' => $bearerToken,
        ])->asForm()->post($url, $data);

        //check if response is not successful
        if (!$response->successful()) {
            return [
                'status' => 'error',
                'message' => 'Failed to reserve places',
            ];
        }

        return [
            'status' => 'success',
            'message' => 'Places reserved successfully',
            'response' => $response->json()['response'],
        ];
    }

    /**
     * Convert this action into an HTTP controller action.
     *
     * @param ActionRequest $request
     * @return mixed
     */
    public function asController(ActionRequest $request)
    {
        return $this->handle($request->eventId, $request->validated());
    }
}
