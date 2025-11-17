<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Player;
use Inertia\Inertia;

class UserPlayerController extends Controller
{
    public function index()
    {
        $players = Player::with('team')
            ->withCount('goals')
            ->orderBy('fullname')
            ->get();

        return Inertia::render('user/Player', [
            'players' => $players,
        ]);
    }
}
