<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class President extends Model
{
    protected $fillable = [
        'dni',
        'name',
        'lastname',
        'birth_date',
        'elected_date',
        'id_team',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class, 'id_team');
    }
}
