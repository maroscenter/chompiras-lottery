<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Lottery;
use App\Raffle;
use App\TicketLottery;
use App\TicketPlay;
use App\Winner;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RaffleController extends Controller
{
    public function index()
    {
        $raffles = Raffle::orderByDesc('datetime')->get();

        return view('admin.raffles.index', compact('raffles'));
    }

    public function create()
    {
        $lotteries = Lottery::all();

        return view('admin.raffles.create', compact('lotteries'));
    }

    public function store(Request $request)
    {
        //parameters
        $number1 = $request->number_1;
        $number2 = $request->number_2;
        $number3 = $request->number_3;
        $lotteryId = $request->lottery_id;
        $datetime = $request->datetime;
        //register raffle
        $raffle = new Raffle();
        $raffle->number_1 = $number1;
        $raffle->number_2 = $number2;
        $raffle->number_3 = $number3;
        $raffle->lottery_id = $lotteryId;
        $raffle->datetime = $datetime;
        $raffle->save();

        $lottery = $raffle->lottery;
        $dateCarbon = Carbon::parse($raffle->datetime);
        $nameDay = Carbon::parse($raffle->datetime)->subDay()->format('l');
        $lotteryTime = $lottery->closing_times()->where('day', $nameDay)->first();
        $startDate = Carbon::parse($lotteryTime->time)->subDay()->addMinutes(15);

        //get ticket_ids
        $ticketIds = TicketLottery::where('lottery_id', $lotteryId)
            ->whereBetween('created_at',[$startDate, $dateCarbon])
            ->pluck('ticket_id');

        if($ticketIds) {
            //winners Quiniela
            $prize = $lottery->prizes()->where('name', 'Quiniela')->first();
            $this->registerWinners($ticketIds, 'Quiniela', $number1, $prize->first, $lotteryId, $raffle->id);
            $this->registerWinners($ticketIds, 'Quiniela', $number2, $prize->second, $lotteryId, $raffle->id);
            $this->registerWinners($ticketIds, 'Quiniela', $number3, $prize->third, $lotteryId, $raffle->id);
        }

        return redirect('raffles');
    }

    function registerWinners($ticketIds, $type, $number, $prize, $lotteryId, $raffleId)
    {
        $winnerPlays = TicketPlay::whereIn('ticket_id', $ticketIds)
            ->where('type', $type)
            ->where('number', $number)
            ->get();

        if ($winnerPlays) {
            foreach ($winnerPlays as $winnerPlay) {
                $winner = new Winner();
                $winner->reward = $prize*$winnerPlay->points;
                $winner->ticket_play_id = $winnerPlay->id;
                $winner->lottery_id = $lotteryId;
                $winner->user_id = $winnerPlay->ticket->user_id;
                $winner->raffle_id = $raffleId;
                $winner->save();
            }
        }
    }
}

