<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Ticket;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->start_date;
        $endingDate = $request->ending_date;
        $user = auth()->user();

        $query = Ticket::query();

        if($user->is_role(2)) {
            $query = $query->where('user_id', $user->id);
        }

        if($startDate && $endingDate) {
            $carbonStartDate = Carbon::createFromFormat('Y-m-d', $startDate)->startOfDay();
            $carbonEndingDate = Carbon::createFromFormat('Y-m-d', $endingDate)->endOfDay();
            $query = $query->whereBetween('created_at', [$carbonStartDate, $carbonEndingDate]);
        }

        $totalPoints = $query->sum('total_points');

        $tickets = $query->orderBy('created_at', 'desc')->get();

        return view('report.sales',
            compact('tickets', 'startDate', 'endingDate', 'totalPoints'));
    }
}
