<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\User::create([
            'name' => 'Christopher',
            'email' => 'cb@itea.no',
            'password' => bcrypt('testpass'),
            'activated' => 1
        ]);
    }
}
