<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $appends = ['PTS', 'P', 'W', 'D', 'L', 'GD', 'winning_prediction'];

    public function homeMatches()
    {
        return $this->hasMany(Match::class, 'home_id');
    }

    public function awayMatches()
    {
        return $this->hasMany(Match::class, 'away_id');
    }

    public function getPTSAttribute()
    {
        return ($this->W) * 3 + $this->D;
    }

    public function getPAttribute()
    {
        return $this->homeMatches()->played()->count() + $this->awayMatches()->played()->count();
    }

    public function getWAttribute()
    {
        $count = 0;
        $matches = Match::whereTeam($this)->played()->get();

        $matches->each(function ($match) use (&$count) {
            if ($this->isWinnerOf($match)) {
                $count++;
            }
        });
        return $count;
    }

    public function getDAttribute()
    {
        $count = 0;
        $matches = Match::whereTeam($this)->played()->get();

        $matches->each(function ($match) use (&$count) {
            if ($this->isDraw($match)) {
                $count++;
            }
        });
        return $count;
    }

    public function getLAttribute()
    {
        $count = 0;
        $matches = Match::whereTeam($this)->played()->get();

        $matches->each(function ($match) use (&$count) {
            if ($this->isLoserOf($match)) {
                $count++;
            }
        });
        return $count;
    }

    public function getGDAttribute()
    {
        return $this->goal_scored - $this->goal_conceded;
    }

    public function getGoalScoredAttribute()
    {
        return $this->homeMatches()->played()->sum('home_goals') + $this->awayMatches()->played()->sum('away_goals');
    }

    public function getGoalConcededAttribute()
    {
        return $this->homeMatches()->played()->sum('away_goals') + $this->awayMatches()->played()->sum('home_goals');
    }

    public function isWinnerOf(Match $match)
    {
        return ($match->home_id == $this->id && $match->home_goals > $match->away_goals)
            || ($match->away_id == $this->id && $match->away_goals > $match->home_goals);
    }

    public function isLoserOf(Match $match)
    {
        return ($match->home_id == $this->id && $match->home_goals < $match->away_goals)
            || ($match->away_id == $this->id && $match->away_goals < $match->home_goals);
    }

    public function isDraw(Match $match)
    {
        return $match->home_goals == $match->away_goals;
    }

    public function getWinningPredictionAttribute()
    {
        if (! Match::hasBeenPlayed()) {
            return $this->strength_winning_percentage;
        }

        return $this->PTS_winning_percentage;
    }

    public function getPTSWinningPercentageAttribute()
    {
        $sumPTS = static::get()->sum('PTS');

        return ($this->PTS * 100) / $sumPTS;
    }

    public function getGDWinningPercentage()
    {
        return ($this->GD * 100) / ($this->goal_scored + $this->goal_conceded);
    }

    public function getStrengthWinningPercentageAttribute()
    {
        $strengthSum = static::sum('strength');

        return ($this->strength * 100) / $strengthSum;
    }

    public function getMedianHomeGoalsAttribute()
    {
        return $this->homeMatches()->get()->pluck('home_goals')->median();
    }

    public function getMedianAwayGoalsAttribute()
    {
        return $this->awayMatches()->get()->pluck('away_goals')->median();
    }

    public function getAverageGoalsInMatchAttribute()
    {
        $matches = Match::whereTeam($this)->count();
        return $matches == 0 ? 0 : $this->goal_scored / $matches;
    }
}
