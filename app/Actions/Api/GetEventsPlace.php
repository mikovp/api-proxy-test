<?php

namespace App\Actions\Api;

use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Support\Facades\Http;

/**
 * @OA\Get(
 *      path="/api/events/{eventId}/places",
 *      summary="Get all places for an event",
 *      description="Get all places for an event",
 *      operationId="getEventsPlace",
 *      tags={"API"},
 *      @OA\Parameter(
 *          name="eventId",
 *          in="path",
 *          description="ID of event to return places",
 *          required=true,
 *      @OA\Schema(
 *          type="integer",
 *          format="int64"
 *      )   
 * ),
 * @OA\Response(
 *      response=200,
 *      description="Successful operation",
 *      @OA\JsonContent(
 *          @OA\Property(
 *              property="status",
 *              type="string",
 *              example="success"
 *          ),
 *          @OA\Property(
 *              property="place",
 *              type="array",
 *              @OA\Items(
 *                  @OA\Property(
 *                      property="id",
 *                      type="integer",
 *                      example="1"
 *                  ),
 *                  @OA\Property(
 *                      property="x",
 *                      type="integer",
 *                      example="1"
 *                  ),
 *                  @OA\Property(
 *                      property="y",
 *                      type="integer",
 *                      example="1"
 *                  ),
 *                  @OA\Property(
 *                      property="height",
 *                      type="integer",
 *                      example="1"
 *                  ),
 *                  @OA\Property(
 *                      property="width",
 *                      type="integer",
 *                      example="1"
 *                  ),
 *                  @OA\Property(
 *                      property="is_available",
 *                      type="boolean",
 *                      example="true"
 *                  )
 * )
 * )
 * )
 * )
 * )
 */

class GetEventsPlace
{
    use AsAction;

    /**
     * Handle the action.
     * @param int $eventId
     * @return array
     */
    public function handle(int $eventId)
    {
        $url = config('api.url') . '/events/' . $eventId . '/places';
        $bearerToken = config('api.bearer_token');

        $response = Http::withHeaders([
            'Authorization' => $bearerToken,
        ])->get($url);

        //check if response is not successful
        if (!$response->successful()) {
            return [
                'status' => 'error',
                'message' => 'Failed to get place',
            ];
        }

        $response = $response->json()['response'];
        $response = collect($response)->map(function ($item) {
            return [
                'id' => $item['id'],
                'x' => $item['x'],
                'y' => $item['y'],
                'height' => $item['height'],
                'width' => $item['width'],
                'is_available' => $item['is_available'],
            ];
        });

        return [
            'status' => 'success',
            'place' => $response,
        ];
    }
}
