<?php

namespace App\Http\Controllers\Api;

use App\SalesLimit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SalesLimitController extends Controller
{
    public function show(Request $request)
    {
        return response()->json([
            'global' => SalesLimit::find(1),
            'individual' => SalesLimit::find(2),
            'user' => $request->user()->sales_limit
        ]);
    }
}
