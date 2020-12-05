<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Raffle;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function earning(Request $request)
    {
        $user = $request->user();

        return response()->json($user->earning);
    }

    public function winners(Request $request)
    {
        $user = $request->user();

        return response()->json($user->winners);
    }
}
