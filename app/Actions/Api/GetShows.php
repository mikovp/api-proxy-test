<?php

namespace App\Actions\Api;

use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Support\Facades\Http;

/**
 * @OA\Get(
 *    path="/api/shows",
 *    summary="Get all shows",
 *    description="Get all shows",
 *    operationId="getShows",
 *    tags={"API"},
 *    @OA\Response(
 *        response=200,
 *        description="Successful operation",
 *        @OA\JsonContent(
 *          @OA\Property(
 *              property="status",
 *              type="string",
 *              example="success"
 *          ),
 *          @OA\Property(
 *              property="shows",
 *              type="array",
 *              @OA\Items(
 *                  @OA\Property(
 *                      property="id",
 *                      type="integer",
 *                      example="1"
 *                  ),
 *                  @OA\Property(
 *                      property="name",
 *                      type="string",
 *                      example="Show 1"
 *                  )
 *          )
 * )
 * )
 * )
 * )
 */
class GetShows
{
    use AsAction;

    /**
     * Handle the action.
     * 
     * @return array
     */
    public function handle(): array
    {
        $url = config('api.url') . '/shows';
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
                'name' => $item['name'],
            ];
        });

        return [
            'status' => 'success',
            'shows' => $response,
        ];
    }
}
