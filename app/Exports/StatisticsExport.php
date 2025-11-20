<?php

namespace App\Exports;

use App\Models\Player;
use App\Models\Team;
use App\Models\Goal;
use App\Models\Comment;
use App\Models\FootballMatch;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class StatisticsExport implements FromCollection, WithHeadings, WithEvents
{
    protected $stats;
    protected $topScorers;
    protected $teamGoals;
    protected $stadiumMatches;

    public function __construct()
    {
        $this->stats = [
            'Jugadores'  => Player::count(),
            'Equipos'    => Team::count(),
            'Goles'      => Goal::count(),
            'Comentarios' => Comment::count(),
            'Partidos'   => FootballMatch::count(),
        ];

        $this->topScorers = Player::withCount('goals')
            ->orderByDesc('goals_count')
            ->limit(10)
            ->get()
            ->map(function ($p) {
                return [
                    'Jugador' => $p->fullname,
                    'Goles'   => $p->goals_count,
                ];
            });

        $this->teamGoals = Team::withCount(['players as goals' => function ($q) {
            $q->join('goals', 'goals.id_player', '=', 'players.id');
        }])
            ->orderByDesc('goals')
            ->get()
            ->map(function ($t) {
                return [
                    'Equipo' => $t->name,
                    'Goles'  => $t->goals,
                ];
            });

        $this->stadiumMatches = Team::select('stadium')
            ->selectRaw('COUNT(*) as matches')
            ->groupBy('stadium')
            ->orderByDesc('matches')
            ->get()
            ->map(function ($m) {
                return [
                    'Estadio'  => $m->stadium,
                    'Partidos' => $m->matches,
                ];
            });
    }

    public function collection()
    {
        $rows = new Collection();

        $rows->push(['ESTADÍSTICAS GENERALES']);
        $rows->push(['Tipo', 'Cantidad']);

        foreach ($this->stats as $key => $value) {
            $rows->push([$key, $value]);
        }

        $rows->push([]);
        $rows->push([]);

        $rows->push(['TOP 10 GOLEADORES']);
        $rows->push(['Jugador', 'Goles']);

        foreach ($this->topScorers as $row) {
            $rows->push(array_values($row));
        }

        $rows->push([]);
        $rows->push([]);

        $rows->push(['GOLES POR EQUIPO']);
        $rows->push(['Equipo', 'Goles']);

        foreach ($this->teamGoals as $row) {
            $rows->push(array_values($row));
        }

        $rows->push([]);
        $rows->push([]);

        $rows->push(['PARTIDOS POR ESTADIO']);
        $rows->push(['Estadio', 'Partidos']);

        foreach ($this->stadiumMatches as $row) {
            $rows->push(array_values($row));
        }

        return $rows;
    }

    public function headings(): array
    {
        return [];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow();

                for ($row = 1; $row <= $lastRow; $row++) {

                    $value = $sheet->getCell("A{$row}")->getValue();

                    if (in_array($value, [
                        'ESTADÍSTICAS GENERALES',
                        'TOP 10 GOLEADORES',
                        'GOLES POR EQUIPO',
                        'PARTIDOS POR ESTADIO'
                    ])) {

                        $sheet->getStyle("A{$row}:B{$row}")->applyFromArray([
                            'font' => [
                                'bold' => true,
                                'color' => ['rgb' => 'FFFFFF'],
                                'size' => 12
                            ],
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'color' => ['rgb' => 'D62027']
                            ],
                        ]);
                    }
                }

                foreach (range(1, $lastRow) as $row) {

                    $cell = $sheet->getCell("A{$row}")->getValue();

                    if (in_array($cell, [
                        'Tipo',
                        'Jugador',
                        'Equipo',
                        'Estadio'
                    ])) {

                        $sheet->getStyle("A{$row}:B{$row}")->applyFromArray([
                            'font' => ['bold' => true],
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'color' => ['rgb' => 'F2F2F2']
                            ],
                        ]);
                    }
                }

                $sheet->getStyle("A1:B{$lastRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000']
                        ],
                    ],
                ]);

                foreach (range('A', 'B') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
            }
        ];
    }
}
