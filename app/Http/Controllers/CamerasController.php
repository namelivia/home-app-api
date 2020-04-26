<?php

namespace App\Http\Controllers;

use App\Models\Camera;

class CamerasController extends BaseController
{
    /**
     * Corresponding model name.
     *
     * @var App\Models\Camera
     */
    protected $modelName = Camera::class;
}
