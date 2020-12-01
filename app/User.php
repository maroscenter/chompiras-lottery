<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
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

    public function is_role($role)
    {
        return $this->role == $role;
    }
}
