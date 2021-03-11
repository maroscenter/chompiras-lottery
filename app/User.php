<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;
    use SoftDeletes;

    const ADMIN = 1;
    const SELLER = 2;

    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $with = ['active_lotteries', 'sales_limit'];

    public function lotteries()
    {
        return $this->hasMany(Lottery::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function active_lotteries()
    {
        return $this->lotteries()->where('status', '=', true);
    }

    public function sales_limit()
    {
        return $this->hasOne(SalesLimit::class);
    }

    public function earning()
    {
        return $this->hasOne(Earning::class);
    }

    public function winners()
    {
        return $this->hasMany(Winner::class)->orderByDesc('created_at');
    }

    public function movement_histories()
    {
        return $this->hasMany(MovementHistory::class);
    }

    public function is_role($role)
    {
        return $this->role == $role;
    }
}
