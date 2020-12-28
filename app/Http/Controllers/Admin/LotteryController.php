<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\ClosingTime;
use App\InactiveDate;
use App\Lottery;
use App\Prize;
use Illuminate\Http\Request;

class LotteryController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $lotteries = $user->lotteries;

        return view('admin.lottery.index', compact('lotteries'));
    }

    public function create()
    {
        return view('admin.lottery.create');
    }

    public function store(Request $request)
    {
        $lottery = new Lottery();
        $lottery->name = $request->name;
        $lottery->abbreviated = $request->abbreviated;
        $lottery->code = $request->code;
        $lottery->status = $request->status;
        $lottery->user_id = auth()->id();
        $lottery->save();

        $times = $request->times;
        $timeDays = $request->time_days;
        $titles = $request->titles;

        if ($timeDays) {
            foreach ($timeDays as $key => $timeDay) {
                $closingTime = new ClosingTime();
                $closingTime->time = $times[$key];
                $closingTime->title = $titles[$key];
                $closingTime->day = $timeDay;
                $closingTime->lottery_id = $lottery->id;
                $closingTime->save();
            }
        }

        $dates = $request->inactive_dates;
        if ($dates) {
            foreach ($dates as $date) {
                $inactiveDate = new InactiveDate();
                $inactiveDate->date = $date;
                $inactiveDate->lottery_id = $lottery->id;
                $inactiveDate->save();
            }
        }

        $prizeNames = $request->prize_names;
        $prizeFirst = $request->prize_first;
        $prizeSecond = $request->prize_second;
        $prizeThird = $request->prize_third;

        if ($prizeNames) {
            foreach ($prizeNames as $key => $prizeName) {
                $prize = new Prize();
                $prize->name = $prizeName;
                $prize->first = $prizeFirst[$key];
                if (isset($prizeSecond[$key]))
                    $prize->second = $prizeSecond[$key];
                if(isset($prizeThird[$key]))
                    $prize->third = $prizeThird[$key];
                $prize->lottery_id = $lottery->id;
                $prize->save();
            }
        }

        return redirect('lotteries');
    }

    public function edit($id)
    {
        $lottery = Lottery::findOrFail($id);

        return view('admin.lottery.edit', compact('lottery'));
    }

    public function update(Request $request, $id)
    {
        $lottery = Lottery::findOrFail($id);
        $lottery->name = $request->name;
        $lottery->abbreviated = $request->abbreviated;
        $lottery->code = $request->code;
        $lottery->status = $request->status;
        $lottery->save();

        $timeIds = $request->closing_time_ids;
        $times = $request->times;

        if ($timeIds) {
            foreach ($timeIds as $key => $timeId) {
                $closingTime = ClosingTime::findOrFail($timeId);
                $closingTime->time = $times[$key];
                $closingTime->save();
            }
        }

        $dates = $request->inactive_dates;
        $lottery->inactive_dates()->delete();
        if ($dates) {
            foreach ($dates as $date) {
                $inactiveDate = new InactiveDate();
                $inactiveDate->date = $date;
                $inactiveDate->lottery_id = $lottery->id;
                $inactiveDate->save();
            }
        }

        $prizeIds = $request->prize_ids;
        $prizeFirst = $request->prize_first;
        $prizeSecond = $request->prize_second;
        $prizeThird = $request->prize_third;

        if ($prizeIds) {
            foreach ($prizeIds as $key => $prizeId) {
                $prize = Prize::findOrFail($prizeId);
                $prize->first = $prizeFirst[$key];
                if (isset($prizeSecond[$key]))
                    $prize->second = $prizeSecond[$key];
                if(isset($prizeThird[$key]))
                    $prize->third = $prizeThird[$key];
                $prize->save();
            }
        }

        return redirect('lotteries');
    }
}
