<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function index()
    {
        return [
            'status' => 'OK',
            'time' => now(),
            'documentation' => route('l5-swagger.default.api')
        ];
    }
}
