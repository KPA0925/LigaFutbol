<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Models\Team;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PlayerController extends Controller
{
    public function index()
    {
        $players = Player::with('team')->latest()->get();

        return Inertia::render('admin/Players/Index', [
            'players' => $players,
        ]);
    }

    public function create()
    {
        $teams = Team::select('id', 'name')->get();

        return Inertia::render('admin/Players/Create', [
            'teams' => $teams,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'fullname' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'position' => 'required|string|max:255',
            'id_team' => 'required|exists:teams,id',
        ]);

        Player::create($validated);

        return redirect()->route('admin.players.index')->with('success', 'Jugador creado correctamente.');
    }

    public function edit(Player $player)
    {
        $teams = Team::select('id', 'name')->get();

        return Inertia::render('admin/Players/Edit', [
            'player' => $player,
            'teams' => $teams,
        ]);
    }

    public function update(Request $request, Player $player)
    {
        $validated = $request->validate([
            'fullname' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'position' => 'required|string|max:255',
            'id_team' => 'required|exists:teams,id',
        ]);

        $player->update($validated);

        return redirect()->route('admin.players.index')->with('success', 'Jugador actualizado correctamente.');
    }

    public function destroy(Player $player)
    {
        $player->delete();

        return redirect()->route('admin.players.index')->with('success', 'Jugador eliminado correctamente.');
    }
}
