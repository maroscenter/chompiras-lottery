<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use softDeletes;

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
    ];

    protected $appends = [
        'lotteries_list'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }

    public function plays()
    {
        return $this->hasMany(TicketPlay::class, 'ticket_id');
    }

    public function group_plays()
    {
        return $this->plays()
            ->select('lottery_id', 'type', 'point')
            ->groupBy('lottery_id', 'type', 'point');
    }

    public function lotteries()
    {
        return $this
            ->belongsToMany(Lottery::class, 'ticket_lottery', 'ticket_id', 'lottery_id');
    }

    public function getWinnerNumbersAttribute()
    {
        $ticketPlayIds = $this->plays()
            ->pluck('id')
            ->toArray();

        $winnerIds =  Winner::whereIn('ticket_play_id', $ticketPlayIds)
            ->distinct('ticket_play_id')
            ->pluck('ticket_play_id')
            ->toArray();

        $ticketPlayWinnerIds = $this->plays()
            ->whereIn('id', $winnerIds)
            ->pluck('number')
            ->toArray();

        return implode(', ', $ticketPlayWinnerIds);
    }

    public function getLotteriesListAttribute()
    {
        $list = "";

        foreach ($this->lotteries as $lottery) {
            $list .=  ($lottery->abbreviated .  ' ');
        }

        return $list;
    }

    public function getSumPointsAttribute()
    {
        return number_format($this->total_points, 2, ',', ' ');
    }

    public function getAvailableDeleteAttribute()
    {
        $max5Mins = $this->created_at > Carbon::now()->subMinutes(5);
        $isAdmin = auth()->user()->is_role(USER::ADMIN);
        $playIds = $this->plays()->pluck('id');

        $winner = Winner::whereIn('ticket_play_id', $playIds)
            ->where('user_id', $this->user_id)
            ->exists();

        return !$winner && ($isAdmin || $max5Mins);
    }

}
