<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use App\Lib\Response\ResponseBuilder;

class RootController extends Controller
{
	/**
	 * The ResponseBuilder instance to
	 * be used when generating responses.
	 *
	 * @var App\Lib\Response
	 */
	protected $responseBuilder;

	public function __construct()
	{
		$this->responseBuilder = App::make(\App\Lib\Response\ResponseBuilder::class);
	}
}
