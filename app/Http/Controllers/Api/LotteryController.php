<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Lottery;
use Illuminate\Http\Request;

class LotteryController extends Controller
{
    public function index() 
    {
        return Lottery::all([
            'id',
            'name',
            'abbreviated',
            'code'
        ]);
    }
}
