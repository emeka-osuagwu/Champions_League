<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Match extends Model
{
    protected $fillable = ['home_id', 'away_id', 'week'];

    const HOME_SCORE = 0.6;
    const AWAY_SCORE = 0.4;

    public function home()
    {
        return $this->belongsTo(Team::class, 'home_id');
    }

    public function away()
    {
        return $this->belongsTo(Team::class, 'away_id');
    }

    public function scopeWhereHome($query, Team $team)
    {
        return $query->where('home_id', $team->id);
    }

    public function scopeWhereAway($query, Team $team)
    {
        return $query->where('away_id', $team->id);
    }

    public function scopeWhereTeam($query, Team $team)
    {
        return $query->where(function ($query) use ($team) {
            return $query->whereHome($team);
        })->orWhere(function ($query) use ($team) {
            return $query->whereAway($team);
        });
    }

    public static function hasBeenPlayed()
    {
        return static::played()->count() > 1;
    }

    public function scopeWhereWeek($query, $week)
    {
        return $query->where('week', $week);
    }

    public static function setup($week, Team $home, Team $away)
    {
        return static::create([
            'week' => $week,
            'home_id' => $home->id,
            'away_id' => $away->id,
        ]);
    }

    public function scopePlayed($query)
    {
        return $query->whereNotNull('home_goals')->orWhereNotNull('away_goals');
    }

    public function scopeNotPlayed($query)
    {
        return $query->whereNull('home_goals')->whereNull('away_goals');
    }

    public function setFinalResult($homeGoals, $awayGoals)
    {
        $this->home_goals = $homeGoals;
        $this->away_goals = $awayGoals;
        $this->save();

        return $this;
    }

    public function guessAndSetResult()
    {
        $homeGoals = $this->guessHomeResult();
        $awayGoals = $this->guessAwayResult();

        return $this->setFinalResult($homeGoals, $awayGoals);
    }

    public function guessHomeResult()
    {
        $teamMood = rand(0, 1) * self::HOME_SCORE;
        return round(round($teamMood * $this->home->winning_prediction / 10) + $teamMood * $this->home->median_home_goals);
    }

    public function guessAwayResult()
    {
        $teamMood = rand(0, 1) * self::AWAY_SCORE;
        return round(round($teamMood * $this->away->winning_prediction / 10) + $teamMood * $this->away->median_away_goals);
    }
}
