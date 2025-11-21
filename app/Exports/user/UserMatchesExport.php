<?php

namespace App\Exports\User;

use App\Models\FootballMatch;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class UserMatchesExport implements FromCollection, WithHeadings, WithEvents
{
    protected array $filters;

    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = FootballMatch::with([
            'teamData',
            'homeTeam',
            'awayTeam',
            'goals.player'
        ]);

        if (!empty($this->filters['season'])) {
            $query->where('season', $this->filters['season']);
        }

        if (!empty($this->filters['team'])) {
            $team = strtolower($this->filters['team']);

            $query->where(function ($q) use ($team) {
                $q->whereHas('homeTeam', function ($sub) use ($team) {
                    $sub->whereRaw('LOWER(name) LIKE ?', ["%{$team}%"]);
                })
                    ->orWhereHas('awayTeam', function ($sub) use ($team) {
                        $sub->whereRaw('LOWER(name) LIKE ?', ["%{$team}%"]);
                    });
            });
        }

        if (!empty($this->filters['date'])) {
            $query->whereDate('match_date_time', $this->filters['date']);
        }

        return $query->get()->map(function ($match) {

            if (!$match->teamData) {
                return [
                    'ID'          => $match->id,
                    'Temporada'   => $match->season,
                    'Fecha'       => $match->match_date_time,
                    'Home'        => 'SIN EQUIPO',
                    'Away'        => 'SIN EQUIPO',
                    'Goles Home'  => 0,
                    'Goles Away'  => 0,
                ];
            }

            $homeTeamId = $match->teamData->id_home_team;
            $awayTeamId = $match->teamData->id_away_team;

            $golesHome = $match->goals()
                ->whereHas('player', function ($q) use ($homeTeamId) {
                    $q->where('id_team', $homeTeamId);
                })
                ->count();

            $golesAway = $match->goals()
                ->whereHas('player', function ($q) use ($awayTeamId) {
                    $q->where('id_team', $awayTeamId);
                })
                ->count();

            return [
                'ID'          => $match->id,
                'Temporada'   => $match->season,
                'Fecha'       => $match->match_date_time,
                'Home'        => $match->homeTeam->name ?? '',
                'Away'        => $match->awayTeam->name ?? '',
                'Goles Home'  => $golesHome,
                'Goles Away'  => $golesAway,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Temporada',
            'Fecha',
            'Home',
            'Away',
            'Goles Home',
            'Goles Away',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet   = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow();

                $sheet->getStyle('A1:G1')->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'color' => ['rgb' => 'D62027'],
                    ],
                ]);

                for ($row = 2; $row <= $lastRow; $row++) {
                    $sheet->getStyle("A{$row}:G{$row}")->applyFromArray([
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'color' => ['rgb' => ($row % 2 == 0) ? 'F2F2F2' : 'FFFFFF'],
                        ],
                    ]);
                }

                $sheet->getStyle("A1:G{$lastRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);

                $sheet->getStyle("A1:G{$lastRow}")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                foreach (range('A', 'G') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
            },
        ];
    }
}
