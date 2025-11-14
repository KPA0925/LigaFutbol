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

    // Relación directa con la tabla pivote
    public function pivot()
    {
        return $this->hasOne(FootballMatchTeam::class, 'id_match');
    }

    // Equipo local
    public function homeTeam()
    {
        return $this->hasOneThrough(
            Team::class,
            FootballMatchTeam::class,
            'id_match',        // FK en teams_matches hacia matches
            'id',              // FK en teams hacia teams_matches
            'id',              // PK en matches
            'id_home_team'     // Clave en teams_matches hacia teams (home)
        );
    }

    // Equipo visitante
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
}
