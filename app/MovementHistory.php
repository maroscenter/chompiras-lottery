<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MovementHistory extends Model
{
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
    ];

    protected $fillable = [
        'amount', 'description', 'user_id'
    ];
}
