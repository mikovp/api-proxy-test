<?php

namespace Tests\Unit\Actions\Api;

use App\Actions\Api\GetShows;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class GetShowsTest extends TestCase
{
    public function test_handle_returns_successful_response()
    {
        // Mock the HTTP response
        Http::fake([
            config('api.url') . '/shows' => Http::response([
                'response' => [
                    ['id' => 1, 'name' => 'Show 1'],
                    ['id' => 2, 'name' => 'Show 2'],
                ],
            ], 200),
        ]);

        $action = new GetShows();
        $result = $action->handle();

        $this->assertEquals('success', $result['status']);
        $this->assertCount(2, $result['shows']);
        $this->assertEquals(1, $result['shows'][0]['id']);
        $this->assertEquals('Show 1', $result['shows'][0]['name']);
    }

    public function test_handle_returns_error_response_on_failure()
    {
        // Mock the HTTP response
        Http::fake([
            config('api.url') . '/shows' => Http::response(null, 500),
        ]);

        $action = new GetShows();
        $result = $action->handle();

        $this->assertEquals('error', $result['status']);
        $this->assertEquals('Failed to get shows', $result['message']);
    }
}