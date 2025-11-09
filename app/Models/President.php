<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class President extends Model
{
    protected $fillable = [
        'name',
        'lastname',
        'birth_date',
        'elected_date',
        'team_id',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
