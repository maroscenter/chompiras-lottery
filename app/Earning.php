<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Earning extends Model
{

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
    ];
    
    protected $hidden = [
        'deleted_at', 'updated_at'
    ];
    
    
    
}
