<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    protected $fillable = [
        'minute',
        'description',
        'id_player',
        'id_match',
    ];

    public function player()
    {
        return $this->belongsTo(Player::class, 'id_player');
    }

    public function match()
    {
        return $this->belongsTo(FootballMatch::class, 'id_match');
    }
}
