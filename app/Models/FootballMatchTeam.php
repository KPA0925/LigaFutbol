<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FootballMatchTeam extends Model
{
    protected $table = 'teamMatches';

    protected $fillable = [
        'id_match',
        'id_home_team',
        'id_away_team',
    ];

    public function match()
    {
        return $this->belongsTo(FootballMatch::class, 'id_match');
    }

    public function team()
    {
        return $this->belongsTo(Team::class, 'id_home_team');
    }
}
