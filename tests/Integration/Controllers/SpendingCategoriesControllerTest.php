<?php

namespace Tests;

use App\Models\SpendingCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Symfony\Component\HttpFoundation\Response;

class SpendingCategoriesControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        Passport::actingAs(
            factory(User::class)->create()
        );
    }

    public function testPostSpendingCategory()
    {
        $this->assertEquals(0, SpendingCategory::count());
        $params = factory(SpendingCategory::class)->make(['id' => 1]);
        $response = $this->json('POST', '/spending_categories', $params->toArray());
        $response->assertStatus(Response::HTTP_CREATED);
        $this->assertEquals(1, SpendingCategory::count());
        $this->assertEquals(1, SpendingCategory::first()->id);
    }

    public function testPutSpendingCategory()
    {
        $params = factory(SpendingCategory::class)->create(['name' => 'name'])->toArray();
        $params['name'] = 'new_name';
        $response = $this->json('PUT', '/spending_categories/' . $params['id'], $params);
        $response->assertStatus(Response::HTTP_OK);
        $this->assertEquals('new_name', SpendingCategory::first()->name);
    }

    public function testGetSpendingCategory()
    {
        $spendingCategory = factory(SpendingCategory::class)->create();
        $response = $this->json('GET', '/spending_categories/' . $spendingCategory->id);
        $response->assertStatus(Response::HTTP_OK);
        $this->assertEquals($spendingCategory->id, $response['id']);
    }

    public function testListSpendingCategorys()
    {
        $spending_categories = factory(SpendingCategory::class, 3)->create();
        $response = $this->json('GET', '/spending_categories/');
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(3);
    }
}
