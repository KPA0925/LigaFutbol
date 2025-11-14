<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    protected $fillable = [
        'fullname',
        'birth_date',
        'position',
        'id_team',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class, 'id_team');
    }

    public function goals()
    {
        return $this->hasMany(Goal::class, 'id_player');
    }
}
