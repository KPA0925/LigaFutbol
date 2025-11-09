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
        return $this->hasMany(Player::class);
    }

    public function presidents()
    {
        return $this->hasOne(President::class);
    }

    public function matches()
    {
        return $this->belongsToMany(FootballMatch::class, 'teamMatches', 'id_home_team', 'id_match')
                    ->withPivot('id_teamMatch');
    }
}
