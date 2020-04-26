<?php

namespace App\Http\Controllers;

use App\Lib\Constants\ConstantsResourceBuilder;

class ConstantsController extends Controller
{
    public function getConstants()
    {
        return response()->json(app()->make(ConstantsResourceBuilder::class)->getConstants());
    }
}
