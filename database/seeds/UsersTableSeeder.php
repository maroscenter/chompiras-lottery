<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'email' => 'hola@programacionymas.com',
            'name' => 'BILL001',
            'password' => bcrypt('123123'),
            'role' => 1
        ]);

        User::create([
            'email' => 'test2@gmail.com',
            'name' => 'BILL002',
            'password' => bcrypt('123123')
        ]);

        User::create([
            'email' => 'test3@gmail.com',
            'name' => 'BILL003',
            'password' => bcrypt('123123')
        ]);
    }
}
