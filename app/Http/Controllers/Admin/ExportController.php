<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TeamsExport;
use App\Exports\PlayersExport;
use App\Exports\GoalsExport;
use App\Exports\PresidentsExport;
use App\Exports\MatchesExport;
use App\Exports\CommentsExport;
use App\Exports\StatisticsExport;

class ExportController extends Controller
{
    public function export(Request $request)
    {
        $module = $request->module;

        $filters = $request->except('module');

        $exports = [
            'teams'      => [TeamsExport::class, 'equipos.xlsx'],
            'players'    => [PlayersExport::class, 'jugadores.xlsx'],
            'goals'      => [GoalsExport::class, 'goles.xlsx'],
            'presidents' => [PresidentsExport::class, 'presidentes.xlsx'],
            'matches'    => [MatchesExport::class, 'partidos.xlsx'],
            'comments'   => [CommentsExport::class, 'comentarios.xlsx'],
            'statistics' => [StatisticsExport::class, 'estadisticas.xlsx']
        ];

        if (!isset($exports[$module])) {
            abort(404, "Módulo no válido");
        }

        [$exportClass, $file] = $exports[$module];

        return Excel::download(new $exportClass($filters), $file);
    }
}
