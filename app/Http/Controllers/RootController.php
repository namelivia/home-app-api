<?php

namespace App\Http\Controllers;

use App\Lib\Response\ResponseBuilder;
use Illuminate\Support\Facades\App;

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
