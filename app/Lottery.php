<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lottery extends Model
{
    public function closing_times()
    {
        return $this->hasMany(ClosingTime::class);
    }

    public function inactive_dates()
    {
        return $this->hasMany(InactiveDate::class);
    }

    public function prizes()
    {
        return $this->hasMany(Prize::class);
    }
}
