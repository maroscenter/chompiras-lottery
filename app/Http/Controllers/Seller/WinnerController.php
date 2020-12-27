<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WinnerController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $winners = $user->winners;
        $tokenResult = $user->createToken('Personal Access Token');

        return view('seller.winners.index', compact('winners', 'tokenResult'));
    }
}
