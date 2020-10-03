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
        $userCount = max((int)$this->command->ask('How many users would you like?', 20), 1);

        // Creates a 'john doe' user
        factory(App\User::class)->states('john-doe')->create();
        
        // Creates 20 other random users
        factory(App\User::class, $userCount)->create();
    }
}
