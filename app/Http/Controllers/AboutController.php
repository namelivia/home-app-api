<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;

class AboutController extends Controller
{
    public function about()
    {
        return response()->json([
            'version' => config('app.version'),
        ]);
    }
}
