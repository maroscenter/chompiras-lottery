<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketPlay extends Model
{
    const TYPE_QUINIELA = 'Quiniela';
    const TYPE_PALE = 'Pale';
    const TYPE_TRIPLETA = 'Tripleta';

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
