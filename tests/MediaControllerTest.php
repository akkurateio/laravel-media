<?php

namespace Akkurate\LaravelMedia\Tests;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class MediaControllerTest extends TestCase
{
    use WithFaker;
    use WithoutMiddleware;

    /** @test **/
    public function it_should_return_the_medias_view()
    {
        $response = $this->get(route('brain.media.medias.index', [
            'uuid' => $this->user->account->slug
        ]));
        $response->assertStatus(200);
    }
}
