<?php

namespace App\Http\Controllers\Api;

use App\Earning;
use App\Lottery;
use App\MovementHistory;
use App\SalesLimit;
use App\SalesPlayLimit;
use App\Ticket;
use App\TicketLottery;
use App\TicketPlay;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->start_date;
        $endingDate = $request->ending_date;
        $user = $request->user();

        $query = Ticket::query();

        if ($user->is_role(2)) {
            $query = $query->where('user_id', $user->id);
        }

        if ($startDate && $endingDate) {
            $carbonStartDate = Carbon::createFromFormat('Y-m-d', $startDate)->startOfDay();
            $carbonEndingDate = Carbon::createFromFormat('Y-m-d', $endingDate)->endOfDay();
            $query = $query->whereBetween('created_at', [$carbonStartDate, $carbonEndingDate]);
        }

        $totalPoints = $query->sum('total_points');

        $tickets = $query
            ->with('lotteries')
            ->orderBy('created_at', 'desc')->get([
                'id', 'code', 'total_points', 'commission_earned', 'user_id', 'created_at'
            ]);

        $data['tickets'] = $tickets;
        $data['totalPoints'] = $totalPoints;

        return $data;
    }

    public function show(Ticket $ticket)
    {
        $plays = $ticket->plays()->get([
            'number', 'points', 'type'
        ]);

        unset($ticket->lotteries);
        unset($ticket->plays);
        unset($ticket->updated_at);
        unset($ticket->deleted_at);

        return compact('ticket', 'plays');
    }

    public function store(Request $request)
    {
        // Get params
        $user = $request->user();
        $lotteryIds = $request->input('lotteries');
        $plays = $request->input('plays');

        // New registrations are available by intervals, each day
        $now = Carbon::now();
        $nameDay = Carbon::now()->format('l');

        if (!$lotteryIds || sizeof($lotteryIds)===0) {
            $data['success'] = false;
            $data['error_message'] = "Es necesario seleccionar al menos una lotería";
            return $data;
        }

        if (!$plays || sizeof($plays)===0) {
            $data['success'] = false;
            $data['error_message'] = "Es necesario ingresar al menos una jugada";
            return $data;
        }

        foreach ($plays as $play) {
            $type = $play['type'];
            $number = $play['number'];
            $point = $play['points'];

            if ($point <= 0) {
                $data['success'] = false;
                $data['error_message'] = "Los puntos a registrar en la jugada no pueden ser negativos o cero";
                return $data;
            }

            if (!is_numeric($number)) {
                $data['success'] = false;
                $data['error_message'] = "Los números a registrar en la jugada tienen que ser numéricos";
                return $data;
            }

            if ($type == TicketPlay::TYPE_QUINIELA && strlen($number) != 2) {
                $data['success'] = false;
                $data['error_message'] = "Sólo se admiten númeross de 2 dígitos para Quiniela";
                return $data;
            }

            if ($type == TicketPlay::TYPE_PALE && strlen($number) != 4) {
                $data['success'] = false;
                $data['error_message'] = "Sólo se admiten númeross de 4 dígitos para Pale";
                return $data;
            }

            if ($type == TicketPlay::TYPE_TRIPLETA && strlen($number) != 6) {
                $data['success'] = false;
                $data['error_message'] = "Sólo se admiten númeross de 6 dígitos para Tripleta";
                return $data;
            }
        }

        $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY);
        $endOfWeek = Carbon::now()->endOfWeek(Carbon::SUNDAY);
        $startOfDay = Carbon::now()->startOfDay();
        $endOfDay = Carbon::now()->endOfDay();

        $limit = SalesLimit::where('user_id', $user->id)->first();
        $global = SalesLimit::find(1);
        $countLotteryIds = count($lotteryIds);

        if (!$limit)
            $limit = SalesLimit::find(2);

        foreach ($plays as $play) {
            $type = $play['type'];
            $point = $play['points'];
            $number = $play['number'];

            //limit play
            //ticket
            $ticketLimit = SalesPlayLimit::where('type', SalesPlayLimit::TICKET)
                ->where('number', $number)
                ->first(['points']);

            if ($ticketLimit && $ticketLimit->points < $point*$countLotteryIds) {
                $data['success'] = false;
                $data['error_message'] = "Lo sentimos, pero excedió el límite de ventas por ticket para el el N° $number ($ticketLimit->points puntos disponibles).";
                return $data;
            }
            //seller
            $sellerLimit = SalesPlayLimit::where('type', SalesPlayLimit::SELLER)
                ->where('number', $number)
                ->first(['points']);

            if ($sellerLimit) {
                $sellerTicketIds = $user->tickets()
                    ->whereBetween('created_at', [$startOfDay, $endOfDay])
                    ->pluck('id');

                $sellerSumPoints = TicketPlay::whereIn('ticket_id', $sellerTicketIds)
                    ->where('number', $number)
                    ->select('points')
                    ->sum('points');

                if ($sellerLimit->points < ($sellerSumPoints + $point*$countLotteryIds)) {
                    $data['success'] = false;
                    $available = $sellerLimit->points - $sellerSumPoints;
                    $data['error_message'] = "Lo sentimos, pero excedió su límite de ventas diarías para el N° $number ($available puntos disponibles).";
                    return $data;
                }
            }

            //global
            $globalLimit = SalesPlayLimit::where('type', SalesPlayLimit::GLOBAL)
                ->where('number', $number)
                ->first(['points']);

            if ($globalLimit) {
                $globalTicketIds = Ticket::whereBetween('created_at', [$startOfDay, $endOfDay])
                    ->pluck('id');

                $globalSumPoints = TicketPlay::whereIn('ticket_id', $globalTicketIds)
                    ->where('number', $number)
                    ->select('points')
                    ->sum('points');

                if ($globalLimit->points < ($globalSumPoints + $point*$countLotteryIds)) {
                    $data['success'] = false;
                    $available = $globalLimit->points - $globalSumPoints;
                    $data['error_message'] = "Lo sentimos, pero excedió el límite global de ventas diarías para el N° $number ($available puntos disponibles).";
                    return $data;
                }
            }

            // limit - individual
            $ticketIds = $user->tickets()->whereBetween('created_at', [$startOfWeek, $endOfWeek])->pluck('id');

            $sumType = TicketPlay::whereIn('ticket_id', $ticketIds)
                ->where('type', $type)
                ->select('points')
                ->sum('points');

            if ($type == TicketPlay::TYPE_QUINIELA && $limit->quiniela < ($sumType + $point*$countLotteryIds)) {
                $data['success'] = false;
                $available = $limit->quiniela - $sumType;
                $data['error_message'] = "Lo sentimos, pero excedió su límite de ventas semanales para Quiniela ($available puntos disponibles).";
                return $data;
            }

            if ($type == TicketPlay::TYPE_PALE && $limit->pale < ($sumType + $point*$countLotteryIds)) {
                $data['success'] = false;
                $available = $limit->pale - $sumType;
                $data['error_message'] = "Lo sentimos, pero excedió su límite de ventas semanales para Pale ($available puntos disponibles).";
                return $data;
            }

            if ($type == TicketPlay::TYPE_TRIPLETA && $limit->tripleta < ($sumType + $point*$countLotteryIds)) {
                $data['success'] = false;
                $available = $limit->tripleta - $sumType;
                $data['error_message'] = "Lo sentimos, pero excedió su límite de ventas semanales para Tripleta ($available puntos disponibles).";
                return $data;
            }

            // global
            $ticketGlobalIds = Ticket::whereBetween('created_at', [$startOfWeek, $endOfWeek])->pluck('id');
            $sumGlobalType = TicketPlay::whereIn('ticket_id', $ticketGlobalIds)
                ->where('type', $type)
                ->select('points')
                ->sum('points');

            if ($type == TicketPlay::TYPE_QUINIELA && $global->quiniela < ($sumGlobalType + $point*$countLotteryIds)) {
                $data['success'] = false;
                $available = $global->quiniela - $sumGlobalType;
                $data['error_message'] = "Lo sentimos, pero excedió el límite global de ventas semanales para Quiniela ($available puntos disponibles).";
                return $data;
            }

            if ($type == TicketPlay::TYPE_PALE && $global->pale < ($sumGlobalType + $point*$countLotteryIds)) {
                $data['success'] = false;
                $available = $global->pale - $sumGlobalType;
                $data['error_message'] = "Lo sentimos, pero excedió el límite global de ventas semanales para Pale ($available puntos disponibles).";
                return $data;
            }

            if ($type == TicketPlay::TYPE_TRIPLETA && $global->tripleta < ($sumGlobalType + $point*$countLotteryIds)) {
                $data['success'] = false;
                $available = $global->tripleta - $sumGlobalType;
                $data['error_message'] = "Lo sentimos, pero excedió el límite global de ventas semanales para Tripleta ($available puntos disponibles).";
                return $data;
            }
        }

        foreach ($lotteryIds as $lotteryId) {
            $lottery = Lottery::find($lotteryId);

            if (!$lottery) {
                $data['success'] = false;
                $data['error_message'] = "No existe ninguna lotería con id $lotteryId.";
                return $data;
            }

            $existsInactive = $lottery->inactive_dates()
                ->where('date', $now->format('Y-m-d'))
                ->exists();

            if ($existsInactive) {
                $data['success'] = false;
                $data['error_message'] = "La lotería $lottery->name ya no admite más jugadas en esta fecha. Vuelva a intentarlo el día de mañana.";
                return $data;
            }

            $lotteryTime = $lottery
                ->closing_times()
                ->where('day', $nameDay)
                ->first();

            $hCloseStart = Carbon::parse($lotteryTime->time)->subMinutes(15);
            $hCloseEnd = Carbon::parse($lotteryTime->time)->addMinutes(15);

            if ($hCloseStart < $now && $now < $hCloseEnd) {
                $diffInMinutes = $hCloseEnd->diffInMinutes($now);
                $data['success'] = false;
                $data['error_message'] = "No se admiten más jugadas en este horario. Vuelva a intentarlo después de $diffInMinutes minutos ($lottery->name).";
                return $data;
            }
        }

        // Validation passed
        $countTickets = $user->tickets()
            ->withTrashed()
            ->count();

        $code = $user->id.'_'.($countTickets+1);

        $ticket = new Ticket();
        $ticket->code = $code;
        $ticket->user_id = $user->id;
        $ticket->save();

        foreach ($lotteryIds as $lotteryId) {
            $ticketLottery = new TicketLottery();
            $ticketLottery->ticket_id = $ticket->id;
            $ticketLottery->lottery_id = $lotteryId;
            $ticketLottery->save();
        }

        foreach ($plays as $play) {
            $type = $play['type'];
            $number = $play['number'];
            $point = $play['points'];

            $play = new TicketPlay();
            $play->number = $number;
            $play->points = $point;
            $play->type = $type;
            $play->ticket_id = $ticket->id;
            $play->save();
        }

        $points = $ticket->plays()->select('points')->sum('points');
        $countLotteries = $ticket->lotteries()->count();

        $ticket->total_points = $points*$countLotteries;
        $ticket->commission_earned = $points*$countLotteries*0.15;
        $ticket->save();

        $amount = $points*$countLotteries*0.85;
        //movement
        MovementHistory::create([
            'description' => 'Ticket N° '.$ticket->code.' registrado',
            'amount' => -$amount,
            'user_id' => $user->id
        ]);
        //balance sheets
        $user->balance -= $amount;
        $user->save();

        //earnings
        Earning::updateOrCreate(
            ['user_id' => $user->id],
            [
                'quantity_tickets' => DB::raw("quantity_tickets + 1"),
                'quantity_points' => DB::raw("quantity_points + ". $ticket->total_points),
                'income' => DB::raw("income + ". $ticket->total_points),
                'commission_earned' => DB::raw("commission_earned + ". $ticket->commission_earned),
            ]
        );

        $data['success'] = true;
        return $data;
    }

    public function delete($id, Request $request)
    {
        $ticket = Ticket::find($id);

        if (!$ticket) {
            $data['success'] = false;
            $data['error_message'] = "No existe ningún ticket con id $id.";
            return $data;
        }

        if (!$ticket->available_delete) {
            $data['success'] = false;
            $data['error_message'] = "El ticket ya no puede ser eliminado.";
            return $data;
        }

        $user = $request->user();

        // earnings
        Earning::updateOrCreate(
            ['user_id' => $user->id],
            [
                'quantity_tickets' => DB::raw("quantity_tickets - 1"),
                'quantity_points' => DB::raw("quantity_points - ". $ticket->total_points),
                'income' => DB::raw("income - ". $ticket->total_points),
                'commission_earned' => DB::raw("commission_earned - ". $ticket->commission_earned),
            ]
        );

        $amount = $ticket->total_points - $ticket->commission_earned;
        //movement
        MovementHistory::create([
            'description' => 'Ticket N° '.$ticket->code.' anulado',
            'amount' => $amount,
            'user_id' => $user->id
        ]);
        //balance sheets
        $user->balance += $amount;
        $user->save();

        $ticket->delete();

        $data['success'] = true;
        return $data;
    }
}
