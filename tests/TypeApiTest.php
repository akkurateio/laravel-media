<?php

namespace Akkurate\LaravelMedia\Tests;

use Akkurate\LaravelMedia\Models\Type;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Laravel\Passport\Passport;

class TypeApiTest extends TestCase
{
    use WithFaker;
    use WithoutMiddleware;

    /** @test **/
    public function it_should_return_all_types()
    {
        Passport::actingAs($this->user);
        Type::factory()->count(2)->make();

        $response = $this->get(route('api.media.types.index', [
            'uuid' => $this->user->account->slug
        ]));
        $response->assertStatus(200);
    }

    /** @test **/
    public function it_should_read_a_type()
    {
        Passport::actingAs($this->user);
        $response = $this->get(route('api.media.types.show', [
            'uuid' => $this->user->account->slug,
            'type' => Type::factory()->create(),
        ]));
        $response->assertStatus(200);
    }

    /** @test **/
    public function it_should_store_a_type()
    {
        Passport::actingAs($this->user);
        $response = $this->post(route('api.media.types.store', [
            'uuid' => $this->user->account->slug,
            'code' => 'DOC',
            'name' => 'Document',
            'description' => 'Documents: pdf, xls…',
            'priority' => 1,
        ]));
        $response->assertStatus(201);
    }

    /** @test **/
    public function it_should_update_a_type()
    {
        Passport::actingAs($this->user);
        $response = $this->put(route('api.media.types.update', [
            'uuid' => $this->user->account->slug,
            'type' => Type::factory()->create(),
            'code' => 'DOC',
            'name' => 'Document',
            'description' => 'Documents: pdf, xls…',
            'priority' => 1,
        ]));
        $response->assertStatus(200);
    }

    /** @test **/
    public function it_should_delete_a_type()
    {
        Passport::actingAs($this->user);
        $response = $this->delete(route('api.media.types.destroy', [
            'uuid' => $this->user->account->slug,
            'type' => Type::factory()->create(),
        ]));
        $response->assertStatus(204);
    }

}
