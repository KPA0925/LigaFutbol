<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = [
        'name',
        'founded_date',
        'city',
        'stadium',
        'capacity',
    ];

    public function players()
    {
        return $this->hasMany(Player::class, 'id_team');
    }

    public function presidents()
    {
        return $this->hasOne(President::class, 'id_team');
    }

    public function matches()
    {
        return $this->belongsToMany(FootballMatch::class, 'teams_matches', 'id_home_team', 'id_match')
            ->withPivot('id_away_team');
    }
}
