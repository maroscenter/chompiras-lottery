<?php

namespace App\Http\Controllers\Api;

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
    public function index()
    {
        dd(Carbon::parse("13:14:00"));
    }
    public function store(Request $request)
    {
        // Get params
        $user = $request->user();
        $lotteryIds = $request->input('lotteries');
        $plays = $request->input('plays');
        $numbers = $request->input('numbers');
        $points = $request->input('points');
        $types = $request->input('types');

        // New registrations are available by intervals, each day
        // <start, xx:xx> OPEN
        // [xx:xx, 00:00> CLOSE
        $now = Carbon::now();
        $nameDay = Carbon::now()->format('l');
        $tomorrow = Carbon::tomorrow();

        $limit = SalesLimit::where('user_id', $user->id)->first();

        foreach ($plays as $play) {
            $type = $play['type'];
            $number = $play['number'];
            $point = $play['point'];

            if ($point <= 0) {
                $data['success'] = false;
                $data['error_message'] = "Los puntos a registrar en la jugada no pueden ser negativos o cero";
                return $data;
            }

            if(!is_numeric($number)) {
                $data['success'] = false;
                $data['error_message'] = "Los números a registrar en la jugada tienen que ser numéricos";
                return $data;
            }

            if($type == 'Quiniela' && strlen($number) != 2) {
                $data['success'] = false;
                $data['error_message'] = "Se acepta un número de 2 digitos para Quiniela";
                return $data;
            }

            if($type == 'Pale' && strlen($number) != 4) {
                $data['success'] = false;
                $data['error_message'] = "Se acepta un número de 4 digitos para Pale";
                return $data;
            }

            if($type == 'Tripleta' && strlen($number) != 6) {
                $data['success'] = false;
                $data['error_message'] = "Se acepta un número de 6 digitos para Tripleta";
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
            if($existsInactive) {
                $data['success'] = false;
                $data['error_message'] = "La lotería $lottery->name ya no admite más jugadas en esta fecha. Vuelva a intentarlo el día de mañana.";
                return $data;
            }

            $lotteryTime = $lottery->closing_times()->where('day', $nameDay)->first();

            $hClose = Carbon::parse($lotteryTime->time);

            // from hClose until the end of the day registration is closed
            if ($now >= $hClose && $now < $tomorrow) {
                $data['success'] = false;
                $data['error_message'] = "No se admiten más jugadas en este horario. Vuelva a intentarlo el día de mañana. ($lottery->name)";
                return $data;
            }

            if($limit) {
                foreach ($types as $type) {
                    $sumType = TicketPlay::where('lottery_id', $lotteryId)
                        ->where('type', $type)->select('point')->sum('point');

                    if($type == 'Quiniela' && $limit->quiniela <= $sumType) {
                        $data['success'] = false;
                        $data['error_message'] = "Lo sentimos, pero su límite de ventas para Quiniela es de $limit->quiniela puntos";
                        return $data;
                    }

                    if($type == 'Pale' && $limit->pale <= $sumType) {
                        $data['success'] = false;
                        $data['error_message'] = "Lo sentimos, pero su límite de ventas para Pale es de $limit->pale puntos";
                        return $data;
                    }

//                    if($type == 'Super Pale' && $limit->super_pale <= $sumType) {
//                        $data['success'] = false;
//                        $data['error_message'] = "Lo sentimos, pero su límite de ventas para Super Pale es de $limit->super_pale puntos";
//                        return $data;
//                    }

                    if($type == 'Tripleta' && $limit->tripleta <= $sumType) {
                        $data['success'] = false;
                        $data['error_message'] = "Lo sentimos, pero su límite de ventas para Tripleta es de $limit->tripleta puntos";
                        return $data;
                    }
                }
            }
        }

        // Right schedule. But did the user already send a list in this period?
//        $today = Carbon::today();
//
//        if ($now < $h12_20) {
//            $alreadySentCount = SentList::where('created_at', '>=', $today)
//                                    ->where('created_at', '<', $h12_20)
//                                    ->where('user_id', $user_id)
//                                    ->count();
//        } else /*if ($now > $h13_40 && $now < $h19_15)*/ {
//            $alreadySentCount = SentList::where('created_at', '>', $h13_40)
//                                        ->where('created_at', '<', $h19_15)
//                                        ->where('user_id', $user_id)
//                                        ->count();
//        }
//
//        if ($alreadySentCount >= 1) {
//            $data['error'] = true;
//            $data['message'] = 'Usted ya ha enviado una lista. Por favor espere al siguiente sorteo.';
//            return $data;
//        }


        // Validation passed
        $ticket = new Ticket();
        $ticket->user_id = $user->id;
        $ticket->save();

        if($lotteryIds) {
            foreach ($lotteryIds as $lotteryId) {
                $ticketLottery = new TicketLottery();
                $ticketLottery->ticket_id = $ticket->id;
                $ticketLottery->lottery_id = $lotteryId;
                $ticketLottery->save();
            }
        }

        if($plays) {
            foreach ($plays as $play) {
                $type = $play['type'];
                $number = $play['number'];
                $point = $play['point'];

                $play = new TicketPlay();
                $play->number = $number;
                $play->points = $point;
                $play->type = $type;
                $play->ticket_id = $ticket->id;
                $play->save();
            }
        }

        $data['success'] = true;
        return $data;
    }

    public function delete($id)
    {
        $ticket = Ticket::find($id);

        $ticket->delete();

        $data['success'] = true;
        return $data;
    }
}
