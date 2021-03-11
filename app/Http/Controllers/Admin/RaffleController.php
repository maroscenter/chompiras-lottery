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
        // parameters
        $n1 = $request->number_1;
        $n2 = $request->number_2;
        $n3 = $request->number_3;
        $lotteryId = $request->lottery_id;
        $datetime = $request->datetime;

        // register raffle
        $raffle = new Raffle();
        $raffle->number_1 = $n1;
        $raffle->number_2 = $n2;
        $raffle->number_3 = $n3;
        $raffle->lottery_id = $lotteryId;
        $raffle->datetime = $datetime;
        $raffle->save();

        $lottery = $raffle->lottery;
        $dateCarbon = Carbon::parse($raffle->datetime);
        $nameDay = Carbon::parse($raffle->datetime)->subDay()->format('l');
        $lotteryTime = $lottery->closing_times()->where('day', $nameDay)->first();
        $startDate = Carbon::parse($lotteryTime->time)->subDay()->addMinutes(15);

        //get ticket_ids
        $ticketIds = TicketLottery::activeTicket()
            ->where('lottery_id', $lotteryId)
            ->whereBetween('created_at',[$startDate, $dateCarbon])
            ->pluck('ticket_id');

        if($ticketIds) {
            // winners Quiniela
            $prize = $lottery->prizes()->where('name', TicketPlay::TYPE_QUINIELA)->first();
            $this->registerWinners($ticketIds, TicketPlay::TYPE_QUINIELA, $n1, $prize->first, $lotteryId, $raffle->id);
            $this->registerWinners($ticketIds, TicketPlay::TYPE_QUINIELA, $n2, $prize->second, $lotteryId, $raffle->id);
            $this->registerWinners($ticketIds, TicketPlay::TYPE_QUINIELA, $n3, $prize->third, $lotteryId, $raffle->id);

            // winners Palé
            $prize = $lottery->prizes()->where('name', TicketPlay::TYPE_PALE)->first();
            $firstNumbers = [$n1.$n2,$n1.$n3,$n2.$n1,$n3.$n1];
            $secondNumbers = [$n2.$n3,$n3.$n2];
            $this->registerWinners($ticketIds, TicketPlay::TYPE_PALE, $firstNumbers, $prize->first, $lotteryId, $raffle->id);
            $this->registerWinners($ticketIds, TicketPlay::TYPE_PALE, $secondNumbers, $prize->second, $lotteryId, $raffle->id);

            // winners Tripleta
            $prize = $lottery->prizes()->where('name', TicketPlay::TYPE_TRIPLETA)->first();
            $numbers = [$n1,$n2,$n3];
            $this->registerTripletaWinners($ticketIds, $numbers, $prize->first, $prize->second, $lotteryId, $raffle->id);
        }

        return redirect('raffles');
    }

    public function show($id)
    {
        $raffle = Raffle::findOrFail($id);

        return view('admin.raffles.show', compact('raffle'));
    }

    function registerTripletaWinners($ticketIds, $numbers, $prizeFirst, $prizeSecond, $lotteryId, $raffleId)
    {
        $ticketPlays = TicketPlay::whereIn('ticket_id', $ticketIds)
            ->where('type', TicketPlay::TYPE_TRIPLETA)->get();

        if ($ticketPlays) {
            foreach ($ticketPlays as $ticketPlay) {
                $count = 0;
                $ticketNumbers = str_split($ticketPlay->number, 2);

                if (in_array($ticketNumbers[0], $numbers))
                    $count += 1;

                if (in_array($ticketNumbers[1], $numbers))
                    $count += 1;

                if (in_array($ticketNumbers[2], $numbers))
                    $count += 1;

                if ($count > 1) {
                    $winner = new Winner();
                    if($count == 3)
                        $winner->reward = $prizeFirst*$ticketPlay->points;
                    else
                        $winner->reward = $prizeSecond*$ticketPlay->points;

                    $winner->ticket_play_id = $ticketPlay->id;
                    $winner->lottery_id = $lotteryId;
                    $winner->user_id = $ticketPlay->ticket->user_id;
                    $winner->raffle_id = $raffleId;
                    $winner->save();
                }
            }
        }
    }

    function registerWinners($ticketIds, $type, $number, $prize, $lotteryId, $raffleId)
    {
        $query = TicketPlay::whereIn('ticket_id', $ticketIds)
            ->where('type', $type);

        if ($type == TicketPlay::TYPE_QUINIELA)
            $query = $query->where('number', $number);
        else
            $query = $query->whereIn('number', $number);

        $winnerPlays = $query->get();

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

