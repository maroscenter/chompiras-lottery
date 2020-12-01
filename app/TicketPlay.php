<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketPlay extends Model
{
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
