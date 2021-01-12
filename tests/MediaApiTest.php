<?php

namespace Akkurate\LaravelMedia\Tests;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class MediaApiTest extends TestCase
{
    use WithFaker;
    use WithoutMiddleware;

    /** @test **/
    public function it_should_return_all_medias()
    {
        $response = $this->get(route('api.media.medias.index', [
            'uuid' => $this->user->account->slug
        ]));
        $response->assertStatus(200);
    }
}
