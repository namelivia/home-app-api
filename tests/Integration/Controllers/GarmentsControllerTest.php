<?php
namespace Tests;

use Symfony\Component\HttpFoundation\Response;
use App\Models\Garment;
use App\Models\User;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GarmentsControllerTest extends TestCase
{

	use RefreshDatabase;

	public function setUp(): void
	{
		parent::setUp();
		Passport::actingAs(
			factory(User::class)->create()
		);
	}

	public function testPostGarment()
	{
		$this->assertEquals(0, Garment::count());
		$params = factory(Garment::class)->make(['id' => 1]);
		$response = $this->json('POST', "/garments", $params->toArray());
		$response->assertStatus(Response::HTTP_CREATED);
		$this->assertEquals(1, Garment::count());
		$this->assertEquals(1, Garment::first()->id);
	}

	public function testPutGarment()
	{
		$params = factory(Garment::class)->create(['name' => 'name'])->toArray();
		$params['name'] = 'new_name';
		$response = $this->json('PUT', "/garments/" . $params['id'], $params);
		$response->assertStatus(Response::HTTP_OK);
		$this->assertEquals('new_name', Garment::first()->name);
	}

	public function testGetGarment()
	{
		$garment = factory(Garment::class)->create();
		$response = $this->json('GET', "/garments/" . $garment->id);
		$response->assertStatus(Response::HTTP_OK);
		$this->assertEquals($garment->id, $response['id']);
	}

	public function testListGarments()
	{
		$garments = factory(Garment::class, 3)->create();
		$response = $this->json('GET', "/garments/");
		$response->assertStatus(Response::HTTP_OK);
		$response->assertJsonCount(3);
	}
}
