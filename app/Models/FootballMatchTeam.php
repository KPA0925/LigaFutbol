<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FootballMatchTeam extends Model
{
    protected $table = 'teams_matches';

    protected $fillable = [
        'id_match',
        'id_home_team',
        'id_away_team',
    ];

    public function match()
    {
        return $this->belongsTo(FootballMatch::class, 'id_match');
    }

    public function homeTeam()
    {
        return $this->belongsTo(Team::class, 'id_home_team');
    }

    public function awayTeam()
    {
        return $this->belongsTo(Team::class, 'id_away_team');
    }
}
