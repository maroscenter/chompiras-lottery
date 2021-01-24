<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Winner extends Model
{
    protected $fillable = [
        'paid'
    ];

    protected $casts = [
        'paid' => 'boolean'
    ];

    protected $with = [
        'ticket_play', 'lottery', 'raffle'
    ];

    public function ticket_play()
    {
        return $this->belongsTo(TicketPlay::class);
    }

    public function lottery()
    {
        return $this->belongsTo(Lottery::class);
    }

    public function raffle()
    {
        return $this->belongsTo(Raffle::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
