<?php

namespace App\Exports;

use App\Models\Goal;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class GoalsExport implements FromCollection, WithHeadings, WithEvents
{
    protected array $filters;

    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $q = Goal::with(['player.team', 'match']);

        if (!empty($this->filters['player'])) {
            $text = strtolower($this->filters['player']);

            $q->whereHas('player', function ($p) use ($text) {
                $p->whereRaw('LOWER(fullname) LIKE ?', ["%{$text}%"]);
            });
        }

        if (!empty($this->filters['team'])) {
            $text = strtolower($this->filters['team']);

            $q->whereHas('player.team', function ($t) use ($text) {
                $t->whereRaw('LOWER(name) LIKE ?', ["%{$text}%"]);
            });
        }

        if (!empty($this->filters['season'])) {
            $text = strtolower($this->filters['season']);

            $q->whereHas('match', function ($m) use ($text) {
                $m->whereRaw('LOWER(season) LIKE ?', ["%{$text}%"]);
            });
        }

        return $q->get()->map(function ($g) {
            return [
                'ID'          => $g->id,
                'Temporada'   => $g->match->season ?? '',
                'Fecha'       => $g->match->match_date_time ?? '',
                'Equipo'      => $g->player->team->name ?? '—',
                'Jugador'     => $g->player->fullname ?? '',
                'Minuto'      => $g->minute,
                'Descripción' => $g->description ?? '',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Temporada',
            'Fecha',
            'Equipo',
            'Jugador',
            'Minuto',
            'Descripción',
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
                        'color' => ['rgb' => '4F81BD'],
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
