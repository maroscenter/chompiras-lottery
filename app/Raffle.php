<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Raffle extends Model
{
    public function lottery()
    {
        return $this->belongsTo(Lottery::class);
    }
}
