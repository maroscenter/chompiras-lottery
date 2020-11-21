<?php

use App\Ticket;

$factory->define(Ticket::class, function (Faker\Generator $faker) {
        
    return [
        // 'sent_list_id' => 1,
        'ticket_number' => rand(0, 99), // available range
        'quantity' => rand(0, 49) // times sold
    ];
});
