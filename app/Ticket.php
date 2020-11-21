<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use softDeletes;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
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
        return $this->belongsToMany(Lottery::class, 'ticket_lottery', 'ticket_id', 'lottery_id');
    }

    public function getSumPointsAttribute()
    {
        return number_format($this->plays()->select('point')->sum('point'), 2, ',', ' ');
    }

}
