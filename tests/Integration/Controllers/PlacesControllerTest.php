<?php

namespace Tests;

use App\Models\Place;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Symfony\Component\HttpFoundation\Response;

class PlacesControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        Passport::actingAs(
            factory(User::class)->create()
        );
    }

    public function testPostPlace()
    {
        $this->assertEquals(0, Place::count());
        $params = factory(Place::class)->make(['id' => 1]);
        $response = $this->json('POST', '/places', $params->toArray());
        $response->assertStatus(Response::HTTP_CREATED);
        $this->assertEquals(1, Place::count());
        $this->assertEquals(1, Place::first()->id);
    }

    public function testPutPlace()
    {
        $params = factory(Place::class)->create(['name' => 'name'])->toArray();
        $params['name'] = 'new_name';
        $response = $this->json('PUT', '/places/' . $params['id'], $params);
        $response->assertStatus(Response::HTTP_OK);
        $this->assertEquals('new_name', Place::first()->name);
    }

    public function testGetPlace()
    {
        $place = factory(Place::class)->create();
        $response = $this->json('GET', '/places/' . $place->id);
        $response->assertStatus(Response::HTTP_OK);
        $this->assertEquals($place->id, $response['id']);
    }

    public function testListPlaces()
    {
        $places = factory(Place::class, 3)->create();
        $response = $this->json('GET', '/places/');
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(3);
    }
}
