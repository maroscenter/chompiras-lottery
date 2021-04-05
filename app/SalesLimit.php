<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalesLimit extends Model
{
    protected $fillable = [
        'quiniela', 'pale', 'super_pale', 'tripleta', 'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
