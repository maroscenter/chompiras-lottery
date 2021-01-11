<?php

use App\SalesLimit;
use Illuminate\Database\Seeder;

class SalesLimitTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //global
        SalesLimit::create([
            'quiniela' => 2000,
            'pale' => 100,
            'super_pale' => 100,
            'tripleta' => 10,
        ]);

        //individual
        SalesLimit::create([
            'quiniela' => 100,
            'pale' => 10,
            'super_pale' => 10,
            'tripleta' => 10,
        ]);
    }
}
