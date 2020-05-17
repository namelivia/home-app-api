<?php

namespace Tests;

use Symfony\Component\HttpFoundation\Response;

class AboutControllerTest extends TestCase
{
    public function testGetVersion()
    {
        $response = $this->json('GET', '/');
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson(['version' => '0.0.1']);
    }
}
