<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Raffle;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function earning(Request $request)
    {
        $user = $request->user();
        $start = $request->start;
        $end = $request->end;

        $query = $user->earning();

        if($start && $end) {
            $carbonStart = Carbon::createFromFormat('Y-m-d', $start)->startOfDay();
            $carbonEnd = Carbon::createFromFormat('Y-m-d', $end)->endOfDay();
            $query = $query->whereBetween('created_at', [$carbonStart, $carbonEnd]);
        }

        return response()->json($query->get());
    }

    public function winners(Request $request)
    {
        $user = $request->user();
        $start = $request->start;
        $end = $request->end;

        $query = $user->winners();

        if($start && $end) {
            $carbonStart = Carbon::createFromFormat('Y-m-d', $start)->startOfDay();
            $carbonEnd = Carbon::createFromFormat('Y-m-d', $end)->endOfDay();
            $query = $query->whereBetween('created_at', [$carbonStart, $carbonEnd]);
        }

        return response()->json($query->get());
    }
}
