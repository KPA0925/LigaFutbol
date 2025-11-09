<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FootballMatch extends Model
{
    protected $fillable = [
        'match_date_time',
        'goal_home',
        'goal_away',
        'season',
    ];

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'teamMatches', 'id_match', 'id_home_team')
                    ->withPivot('id_teamMatch');
    }

    public function goals()
    {
        return $this->hasMany(Goal::class, 'match_id');
    }
}
