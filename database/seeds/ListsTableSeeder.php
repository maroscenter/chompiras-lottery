<?php

use App\SentList;
use App\Ticket;
use Illuminate\Database\Seeder;

class ListsTableSeeder extends Seeder
{    
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(SentList::class, 30)->create()->each(function($list) {
            $tickets = collect();
            $totalSold = 0;
            
            for ($i=0; $i<=99; ++$i) {
                $ticket = factory(Ticket::class)->make(['ticket_number' => $i]);
                $tickets->push($ticket);
                
                $totalSold += $ticket->quantity;                
            }            

            $list->tickets()->saveMany($tickets);
            
            $list->total_tickets_sold = $totalSold;
            $list->save();
        });
    }
}
