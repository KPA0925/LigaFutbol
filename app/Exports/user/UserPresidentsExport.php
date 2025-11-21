<?php

namespace App\Exports\User;

use App\Models\President;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class UserPresidentsExport implements FromCollection, WithHeadings, WithEvents
{
    protected $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $q = President::with('team');

        if (!empty($this->filters['dni'])) {
            $q->whereRaw("CAST(dni AS TEXT) LIKE ?", ["%{$this->filters['dni']}%"]);
        }

        if (!empty($this->filters['name'])) {
            $text = strtolower($this->filters['name']);
            $q->whereRaw("LOWER(name) LIKE ?", ["%{$text}%"]);
        }

        if (!empty($this->filters['lastname'])) {
            $text = strtolower($this->filters['lastname']);
            $q->whereRaw("LOWER(lastname) LIKE ?", ["%{$text}%"]);
        }

        if (!empty($this->filters['birth'])) {
            $q->whereDate('birth_date', $this->filters['birth']);
        }

        if (!empty($this->filters['elected'])) {
            $q->whereDate('elected_date', $this->filters['elected']);
        }

        if (!empty($this->filters['team'])) {
            $text = strtolower($this->filters['team']);

            $q->whereHas('team', function ($qr) use ($text) {
                $qr->whereRaw("LOWER(name) LIKE ?", ["%{$text}%"]);
            });
        }

        return $q->get()->map(function ($p) {
            return [
                'DNI'        => $p->dni,
                'Nombre'     => $p->name,
                'Apellido'   => $p->lastname,
                'Nacimiento' => $p->birth_date,
                'Elegido'    => $p->elected_date,
                'Equipo'     => $p->team->name ?? 'Sin equipo',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'DNI',
            'Nombre',
            'Apellido',
            'Nacimiento',
            'Elegido',
            'Equipo',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet   = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow();

                $sheet->getStyle('A1:F1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF']
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'color' => ['rgb' => 'D62027']
                    ],
                ]);

                for ($row = 2; $row <= $lastRow; $row++) {
                    $sheet->getStyle("A{$row}:F{$row}")->applyFromArray([
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'color' => ['rgb' => ($row % 2 == 0) ? 'F2F2F2' : 'FFFFFF'],
                        ],
                    ]);
                }

                $sheet->getStyle("A1:F{$lastRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);

                $sheet->getStyle("A1:F{$lastRow}")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                foreach (range('A', 'F') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
            },
        ];
    }
}