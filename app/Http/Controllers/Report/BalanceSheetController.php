<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BalanceSheetController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        return view('report.balance_sheets', compact('user'));
    }
}
