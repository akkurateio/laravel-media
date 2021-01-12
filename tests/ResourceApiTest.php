<?php

namespace Akkurate\LaravelMedia\Tests;

use Akkurate\LaravelMedia\Models\Resource;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class ResourceApiTest extends TestCase
{
    use WithFaker;
    use WithoutMiddleware;

    /** @test **/
    public function it_should_return_all_resources()
    {
        Resource::factory()->count(2)->create();
        $response = $this->get(route('api.media.resources.index', [
            'uuid' => $this->user->account->slug
        ]));
        $response->assertStatus(200);
    }
}
