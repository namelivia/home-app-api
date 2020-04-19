<?php
namespace App\Http\Controllers;

use GuzzleHttp\Client;

class MoodsController extends Controller
{

	private $apiURI = config('moodtracker.url')
	public function __construct()
	{
		$this->client = new Client(['base_uri' => $this->apiURI]);
	}

	public function index()
	{
		return $this->client->request('GET', 'moods');
	}

	public function today()
	{
		return $this->client->request('GET', 'moods/today');
	}

	public function store()
	{
		return $this->client->request(
			'POST',
			'moods',
			['json' => request()->all()],
		);
	}

	public function update($moodId)
	{
		return $this->client->request(
			'PUT',
			'moods/' . $moodId,
			['json' => request()->all()],
		);
	}

	public function destroy($moodId)
	{
		return $this->client->request(
			'DELETE',
			'moods/' . $moodId
		);
	}
}
