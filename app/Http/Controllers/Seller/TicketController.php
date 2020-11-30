<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function create()
    {
        $user = auth()->user();
        $lotteries = $user->lotteries;
        $tokenResult = $user->createToken('Personal Access Token');

        return view('seller.tickets.create', compact('lotteries', 'tokenResult'));
    }
}
