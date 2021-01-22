<?php

namespace App;

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
        return $this
            ->belongsToMany(Lottery::class, 'ticket_lottery', 'ticket_id', 'lottery_id');
    }
    
    public function getLotteriesList() 
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

}
