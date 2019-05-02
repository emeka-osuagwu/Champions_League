<?php

namespace App\Jobs;

use App\Match;
use App\Team;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;

class SetupMatches implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws \Exception
     */
    public function handle()
    {
        DB::table('matches')->delete();

        $homes = Team::get();
        $aways = Team::get();
        $types = collect(['home', 'away']);

        $matches = $homes->crossJoin($aways);
        $matches = $matches->crossJoin($types);
        $matches = $matches->filter(function ($match) {
            return $match[0][0]->id !== $match[0][1]->id;
        });
        $matches = $matches->shuffle();

        $matchPerDay = Team::count() - 1;
        $week = 1;
        while ($week <= $matchPerDay * 2) {
            $matches->each(function ($match) use ($week) {
                if (
                    Match::whereTeam($match[0][0])->whereWeek($week)->first() === null
                    && Match::whereTeam($match[0][1])->whereWeek($week)->first() == null
                ) {
                    if ($match[1] == 'home') {
                        if (! Match::whereHome($match[0][0])->whereAway($match[0][1])->first()) {
                            Match::setup($week, $match[0][0], $match[0][1]);
                        }
                    } else {
                        if (! Match::whereHome($match[0][1])->whereAway($match[0][0])->first()) {
                            Match::setup($week, $match[0][1], $match[0][0]);
                        }
                    }
                }
            });
            $week++;
        }
    }
}
