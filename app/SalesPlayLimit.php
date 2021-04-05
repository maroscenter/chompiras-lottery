<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalesPlayLimit extends Model
{
    const GLOBAL = 0;
    const SELLER = 1;
    const TICKET = 2;

    protected $fillable = [
        'number', 'points', 'type'
    ];
}
