<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\President;
use App\Models\Team;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PresidentController extends Controller
{
    public function index()
    {
        $presidents = President::with('team')->latest()->get();

        return Inertia::render('admin/Presidents/Index', [
            'presidents' => $presidents,
        ]);
    }

    public function create()
    {
        $teams = Team::select('id', 'name')->get();

        return Inertia::render('admin/Presidents/Create', [
            'teams' => $teams,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'DNI' => 'required|string|max:50',
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'elected_date' => 'required|date',
            'id_team' => 'required|exists:teams,id',
        ]);

        President::create($validated);

        return redirect()->route('admin.presidents.index')
            ->with('success', 'Presidente creado correctamente.');
    }

    public function edit(President $president)
    {
        $president->load('team');

        $teams = Team::select('id', 'name')->get();

        return Inertia::render('admin/Presidents/Edit', [
            'president' => $president,
            'teams' => $teams,
        ]);
    }

    public function update(Request $request, President $president)
    {
        $validated = $request->validate([
            'DNI' => 'required|string|max:50',
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'elected_date' => 'required|date',
            'id_team' => 'required|exists:teams,id',
        ]);

        $president->update($validated);

        return redirect()->route('admin.presidents.index')
            ->with('success', 'Presidente actualizado correctamente.');
    }

    public function destroy(President $president)
    {
        $president->delete();

        return redirect()->route('admin.presidents.index')
            ->with('success', 'Presidente eliminado correctamente.');
    }
}
