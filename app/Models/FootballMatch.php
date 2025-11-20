<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FootballMatch extends Model
{
    protected $table = 'matches';

    protected $fillable = [
        'match_date_time',
        'goal_home',
        'goal_away',
        'season',
    ];

    public function teamData()
    {
        return $this->hasOne(FootballMatchTeam::class, 'id_match');
    }

    public function homeTeam()
    {
        return $this->hasOneThrough(
            Team::class,
            FootballMatchTeam::class,
            'id_match',
            'id',
            'id',
            'id_home_team'
        );
    }

    public function awayTeam()
    {
        return $this->hasOneThrough(
            Team::class,
            FootballMatchTeam::class,
            'id_match',
            'id',
            'id',
            'id_away_team'
        );
    }

    public function goals()
    {
        return $this->hasMany(Goal::class, 'id_match');
    }

    public function homeGoals()
    {
        return $this->goals()->whereHas('player', function ($q) {
            $q->where('id_team', optional($this->teamData)->id_home_team);
        });
    }

    public function awayGoals()
    {
        return $this->goals()->whereHas('player', function ($q) {
            $q->where('id_team', optional($this->teamData)->id_away_team);
        });
    }
}
