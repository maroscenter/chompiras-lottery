<?php

use App\SentList;
use App\User;

$factory->define(SentList::class, function (Faker\Generator $faker) {
    static $userIds = null;
    
    if (!$userIds)
        $userIds = User::pluck('id')->toArray();
        
    return [
        'user_id' => $faker->randomElement($userIds),
        'observation' => $faker->text,
        'total_tickets_sold' => 0,
        'created_at' => $faker->dateTimeBetween('-3 months', 'now')
    ];
});
