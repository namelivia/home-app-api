<?php

namespace App\Http\Controllers;

use App\Models\Heater;
use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\Response;

class HeaterController extends BaseController
{
    /**
     * Corresponding model name.
     *
     * @var App\Models\Heater
     */
    protected $modelName = Heater::class;

    public function __construct()
    {
        parent::__construct();
        $this->client = new Client(['base_uri' => config('heater.url')]);
    }

    public function turnOn()
    {
        $response = $this->client->get('turn_on');
        if ($response->getStatusCode() === Response::HTTP_OK) {
            Heater::create(['status' => 1]);
        }

        return response()->json($response);
    }

    public function turnOff()
    {
        $response = $this->client->get('turn_off');
        if ($response->getStatusCode() === Response::HTTP_OK) {
            Heater::create(['status' => 0]);
        }

        return response()->json($response);
    }

    public function viewLogs()
    {
        $response = $this->client->get('view_logs');
        if ($response->getStatusCode() === Response::HTTP_OK) {
            return response()->json(['logs' => $response->getBody()->getContents()]);
        }

        return response()->json($response);
    }
}
