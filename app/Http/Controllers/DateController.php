<?php

namespace App\Http\Controllers;

use App\SentList;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $specific_date = $request->input('lists');

        if ($specific_date) {
            $date = str_replace('/', '-', $specific_date);
            $format = date('Y-m-d', strtotime($date));
            $lists = SentList::whereDate('created_at', $format)->get();
            
            return view('date.show', compact('lists', 'specific_date'));
            
        } else {
            $dates = DB::table('sent_lists as SL')
                ->select([
                    DB::Raw('COUNT(1) as listsCount'),
                    DB::Raw('DATE_FORMAT(SL.created_at, "%d/%m/%Y") as day')
                ])
                ->groupBy('day')
                ->orderBy('day', 'desc')
                ->get();

            return view('date.index', compact('dates'));
        }

    }
}
