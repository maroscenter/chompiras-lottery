<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Prize extends Model
{
    const TYPE_QUINIELA = 'Quiniela';
    const TYPE_PALE = 'Pale';
    const TYPE_TRIPLETA = 'Tripleta';
    const TYPE_SUPER_PALE = 'Super_Pale';
}
