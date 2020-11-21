<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketPlay extends Model
{
    public function lottery()
    {
        return $this->belongsTo(Lottery::class);
    }
}
