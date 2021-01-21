<?php

namespace App\Http\Controllers\Api;

use App\Earning;
use App\Lottery;
use App\SalesLimit;
use App\Ticket;
use App\TicketLottery;
use App\TicketPlay;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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

        $tickets = $query->orderBy('created_at', 'desc')->get();

        $data['tickets'] = $tickets;
        $data['totalPoints'] = $totalPoints;

        return $data;
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

        if (!$request->has('lotteries')) {
            $data['success'] = false;
            $data['error_message'] = "Es necesario seleccionar al menos una lotería";
            return $data;
        }

        if (!$request->has('plays')) {
            $data['success'] = false;
            $data['error_message'] = "Es necesario ingresar una jugada";
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
                $data['error_message'] = "Se aceptan números de 2 digitos para Quiniela";
                return $data;
            }

            if ($type == TicketPlay::TYPE_PALE && strlen($number) != 4) {
                $data['success'] = false;
                $data['error_message'] = "Se aceptan números de 4 digitos para Pale";
                return $data;
            }

            if ($type == TicketPlay::TYPE_TRIPLETA && strlen($number) != 6) {
                $data['success'] = false;
                $data['error_message'] = "Se aceptan números de 6 digitos para Tripleta";
                return $data;
            }
        }

        $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY);
        $endOfWeek = Carbon::now()->endOfWeek(Carbon::SUNDAY);

        $limit = SalesLimit::where('user_id', $user->id)->first();
        $global = SalesLimit::find(1);
        $countLotteryIds = count($lotteryIds);

        if (!$limit)
            $limit = SalesLimit::find(2);

        foreach ($plays as $play) {
            $type = $play['type'];
            $point = $play['points'];

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

            if(!$lottery) {
                $data['success'] = false;
                $data['error_message'] = "No existe ninguna lotería con id $lotteryId.";
                return $data;
            }

            $existsInactive = $lottery->inactive_dates()->where('date', $now)->exists();
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
        $ticket = new Ticket();
        $ticket->user_id = $user->id;
        $ticket->save();

        if ($lotteryIds) {
            foreach ($lotteryIds as $lotteryId) {
                $ticketLottery = new TicketLottery();
                $ticketLottery->ticket_id = $ticket->id;
                $ticketLottery->lottery_id = $lotteryId;
                $ticketLottery->save();
            }
        }

        if ($plays) {
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
        }

        $points = $ticket->plays()->select('points')->sum('points');
        $countLotteries = $ticket->lotteries()->count();

        $ticket->total_points = $points*$countLotteries;
        $ticket->commission_earned = $points*$countLotteries*0.15;
        $ticket->save();

        //earnings
        $earning = $user->earning;

        if (!$earning) {
            $earning = new Earning();
            $earning->user_id = $user->id;
        }
        
        $earning->quantity_tickets += 1;
        $earning->quantity_points += $ticket->total_points;
        $earning->income += $ticket->total_points;
        $earning->commission_earned += $ticket->commission_earned;
        $earning->save();

        $data['success'] = true;
        return $data;
    }

    public function delete($id, Request $request)
    {
        $ticket = Ticket::find($id);
        $user = $request->user();
        
        // earnings
        $earning = $user->earning;

        if (!$earning) {
            $earning = new Earning();
            $earning->user_id = $user->id;
        }
        $earning->quantity_tickets =- 1;
        $earning->quantity_points =- $ticket->total_points;
        $earning->income =- $ticket->total_points;
        $earning->commission_earned =- $ticket->commission_earned;
        $earning->save();

        $ticket->delete();

        $data['success'] = true;
        return $data;
    }
}
