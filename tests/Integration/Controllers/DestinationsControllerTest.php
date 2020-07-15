<?php

namespace Tests;

use App\Models\Destination;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Symfony\Component\HttpFoundation\Response;

class DestinationsControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        Passport::actingAs(
            factory(User::class)->create()
        );
    }

    public function testPostDestination()
    {
        $this->assertEquals(0, Destination::count());
        $params = factory(Destination::class)->make(['id' => 1]);
        $response = $this->json('POST', '/destinations', $params->toArray());
        $response->assertStatus(Response::HTTP_CREATED);
        $this->assertEquals(1, Destination::count());
        $this->assertEquals(1, Destination::first()->id);
    }

    public function testPutDestination()
    {
        $params = factory(Destination::class)->create(['name' => 'name'])->toArray();
        $params['name'] = 'new_name';
        $response = $this->json('PUT', '/destinations/' . $params['id'], $params);
        $response->assertStatus(Response::HTTP_OK);
        $this->assertEquals('new_name', Destination::first()->name);
    }

    public function testGetDestination()
    {
        $destination = factory(Destination::class)->create();
        $response = $this->json('GET', '/destinations/' . $destination->id);
        $response->assertStatus(Response::HTTP_OK);
        $this->assertEquals($destination->id, $response['id']);
    }

    public function testListDestinations()
    {
        $destinations = factory(Destination::class, 3)->create();
        $response = $this->json('GET', '/destinations/');
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(3);
    }
}
