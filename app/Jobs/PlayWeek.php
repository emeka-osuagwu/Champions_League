<?php

namespace App\Jobs;

use App\Match;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class PlayWeek implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $playedMatches;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->playedMatches = collect();
    }

    /**
     * Execute the job.
     *
     * @return \Illuminate\Support\Collection|null
     */
    public function handle()
    {
        $soonerMatch = Match::notPlayed()->orderBy('week')->first();
        if (! $soonerMatch) {
            return null;
        }
        $week = $soonerMatch->week;
        $weekMatches = Match::whereWeek($week)->with('home', 'away')->get();

        $weekMatches->each(function ($match) {

            $this->playedMatches->push($match->guessAndSetResult());
        });
        return $this->playedMatches;
    }
}
