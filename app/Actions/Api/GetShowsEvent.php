<?php

namespace App\Actions\Api;

use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Support\Facades\Http;

/**
 * @OA\Get(
 *      path="/api/shows/{showId}/events",
 *      summary="Get all events for a show",
 *      description="Get all events for a show",
 *      operationId="getShowsEvent",
 *      tags={"API"},
 *      @OA\Parameter(
 *          name="showId",
 *          in="path",
 *          description="ID of show to return events",
 *          required=true,
 *          @OA\Schema(
 *              type="integer",
 *              format="int64"
 *          )
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
 *              property="events",
 *              type="array",
 *          @OA\Items(
 *              @OA\Property(
 *                  property="id",
 *                  type="integer",
 *                  example="1"
 *              ),
 *              @OA\Property(
 *                  property="showId",
 *                  type="integer",
 *                  example="1"
 *              ),
 *              @OA\Property(
 *                  property="date",
 *                  type="string",
 *                  example="2022-01-01"
 *              )
 * )
 * )
 * )
 * )
 * )
 */

class GetShowsEvent
{
    use AsAction;

    /**
     * Handle the action.
     * @param int $showId
     * @return array
     */
    public function handle(int $showId): array
    {
        $url = config('api.url') . '/shows/' . $showId . '/events';
        $bearerToken = config('api.bearer_token');

        $response = Http::withHeaders([
            'Authorization' => $bearerToken,
        ])->get($url);

        //check if response is not successful
        if (!$response->successful()) {
            return [
                'status' => 'error',
                'message' => 'Failed to get shows',
            ];
        }

        $response = $response->json()['response'];
        $response = collect($response)->map(function ($item) {
            return [
                'id' => $item['id'],
                'showId' => $item['showId'],
                'date' => $item['date'],
            ];
        });

        return [
            'status' => 'success',
            'events' => $response,
        ];
    }
}
