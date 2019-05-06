<?php

use Illuminate\Database\Seeder;
use App\Jobs\SetupMatches;

class TeamsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Illuminate\Support\Facades\DB::table('teams')->insert([
            ['name' => 'Chelsea', 'strength' => 8],
            ['name' => 'Arsenal', 'strength' => 7],
            ['name' => 'Manchester City', 'strength' => 5],
            ['name' => 'Liverpool', 'strength' => 3],
            ['name' => 'Manchester United', 'strength' => 8],
            ['name' => 'Everton', 'strength' => 4],
        ]);

        SetupMatches::dispatchNow();
    }
}
