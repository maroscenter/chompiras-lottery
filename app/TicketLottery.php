<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketLottery extends Model
{
    protected $table = 'ticket_lottery';

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function lottery()
    {
        return $this->belongsTo(Lottery::class);
    }

    public function scopeActiveTicket($query)
    {
        return $query->with('ticket')
        ->whereHas('ticket', function($query) {
            $query->whereNull('deleted_at');
        });
    }
}
