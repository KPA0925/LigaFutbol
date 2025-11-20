<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\User\UserMatchesExport;
use App\Exports\User\UserTeamsExport;
use App\Exports\User\UserPlayersExport;
use App\Exports\User\UserGoalsExport;
use App\Exports\User\UserPresidentsExport;
use App\Exports\User\UserStatisticsExport;

class UserExportController extends Controller
{
    public function export(Request $request)
    {
        $module = $request->module;

        $filters = $request->except('module');

        $exports = [
            'matches' => [UserMatchesExport::class, 'partidos.xlsx'],
            'teams' => [UserTeamsExport::class, 'equipos.xlsx'],
            'players'    => [UserPlayersExport::class, 'jugadores.xlsx'],
            'goals'      => [UserGoalsExport::class, 'goles.xlsx'],
            'presidents' => [UserPresidentsExport::class, 'presidentes.xlsx'],
            'statistics' => [UserStatisticsExport::class, 'estadisticas.xlsx'],
        ];

        if (!isset($exports[$module])) {
            abort(404, "Módulo no válido");
        }

        [$exportClass, $file] = $exports[$module];

        return Excel::download(new $exportClass($filters), $file);
    }
}