<?php

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
        // Creates a 'john doe' user
        factory(App\User::class)->states('john-doe')->create();
        
        // Creates 20 other random users
        factory(App\User::class, 20)->create();
    }
}
