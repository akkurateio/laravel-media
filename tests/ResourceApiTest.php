<?php

namespace Akkurate\LaravelMedia\Tests;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class ResourceApiTest extends TestCase
{
    use WithFaker;
    use WithoutMiddleware;

    /** @test **/
    public function it_should_return_all_resources()
    {
        $response = $this->get(route('api.media.resources.index', [
            'uuid' => $this->user->account->slug
        ]));
        $response->assertStatus(200);
    }
}
