<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SellerController extends Controller
{
    public function index(Request $request)
    {
        $queryText = $request->input('queryText');

        if ($queryText)
            return User::where('name', 'like', "%$queryText%")->get([
                'id', 'name as text'
            ]);
        else
            return User::orderBy('name')->take(20)->get([
                'id', 'name as text'
            ]);
    }

    public function seller($id)
    {
        return User::findOrFail($id);
    }
}
