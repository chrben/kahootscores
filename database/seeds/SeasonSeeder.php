<?php

use Illuminate\Database\Seeder;

class SeasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Season::create([
            'start' => new \Carbon\Carbon('2016-08-01'),
            'end' => new \Carbon\Carbon('2017-01-01'),
        ]);
        \App\Season::create([
            'start' => new \Carbon\Carbon('2017-01-01'),
            'end' => new \Carbon\Carbon('2017-08-01'),
        ]);
        \App\Season::create([
            'start' => new \Carbon\Carbon('2017-08-01'),
            'end' => new \Carbon\Carbon('2018-01-01'),
        ]);
        \App\Season::create([
            'start' => new \Carbon\Carbon('2018-01-01'),
            'end' => null,
        ]);

    }
}
