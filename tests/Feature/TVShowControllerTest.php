<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class TVShowControllerTest extends TestCase
{
    public function testSearchValidQuery()
    {
        Http::fake([
            'api.tvmaze.com/*' => Http::response([
                ['show' => ['name' => 'Deadwood']],
                ['show' => ['name' => 'Deadpool']],
                ['show' => ['name' => 'Redwood Kings']]
            ], 200)
        ]);

        $response = $this->get('/?q=deadwood');

        $response->assertStatus(200);
        $response->assertJsonCount(1);
        $response->assertJsonFragment(['name' => 'Deadwood']);
    }

    public function testSearchInvalidQuery()
    {
        $response = $this->get('/?q=');

        $response->assertStatus(302);
    }

    public function testCacheFunctionality()
    {
        Cache::shouldReceive('has')
            ->once()
            ->with('tv_shows_' . md5('Deadwood'))
            ->andReturn(false);

        Cache::shouldReceive('put')
            ->once()
            ->with('tv_shows_' . md5('Deadwood'), \Mockery::type('array'), 3600);

        Http::fake([
            'api.tvmaze.com/*' => Http::response([
                ['show' => ['name' => 'Deadwood']]
            ], 200)
        ]);

        $response = $this->get('/?q=Deadwood');

        $response->assertStatus(200);
        $response->assertJsonFragment(['name' => 'Deadwood']);
    }
}
