<?php

namespace App\Http\Controllers;

use App\Lottery;
use App\Ticket;
use App\TicketLottery;
use App\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $userId = $request->user_id;
        $lotteryId = $request->lottery_id;
        $date = $request->date;
        $user = auth()->user();

        $tokenResult = $user->createToken('Personal Access Token');

        $users = User::orderBy('name')->get();

        $queryLottery = Lottery::where('status', 1);
        $query = Ticket::query();

        if($user->is_role(2)) {
            $queryLottery = $queryLottery->where('user_id', $user->id);
            $query = $query->where('user_id', $user->id);
        }


        $lotteries = $queryLottery->orderBy('name')->get();

        if($userId)
            $query = $query->where('user_id', $userId);

        if ($lotteryId) {
            $ticketIds = TicketLottery::where('lottery_id', $lotteryId)->pluck('ticket_id');

            $query = $query->whereIn('id', $ticketIds);
        }

        if($date)
            $query = $query->where('created_at', $date);

        $tickets = $query->orderBy('created_at', 'desc')->get();

        return view('home',
            compact('tickets', 'users', 'lotteries', 'userId', 'lotteryId', 'date', 'tokenResult'));
    }
}
