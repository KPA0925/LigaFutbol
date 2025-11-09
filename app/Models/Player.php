<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    protected $fillable = [
        'full_name',
        'birth_date',
        'position',
        'team_id',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function goals()
    {
        return $this->hasMany(Goal::class);
    }
}
