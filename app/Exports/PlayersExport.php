<?php

namespace App\Exports;

use App\Models\Player;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class PlayersExport implements FromCollection, WithHeadings, WithEvents
{
    protected $f;

    public function __construct($filters)
    {
        $this->f = $filters;
    }

    public function collection()
    {
        // Normalizador PHP
        $normalize = function ($str) {
            $str = strtolower($str ?? '');
            $str = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
            return trim($str);
        };

        // Normalizador SQL
        $sqlNormalize = "
            LOWER(
                TRANSLATE(%s,
                    'ÁÉÍÓÚÀÈÌÒÙÄËÏÖÜáéíóúàèìòùäëïöüÑñ',
                    'AEIOUAEIOUAEIOUaeiouaeiouNn'
                )
            )
        ";

        $q = Player::with('team');

        // FILTRO NOMBRE
        if (!empty($this->f['name'])) {
            $value = $normalize($this->f['name']);
            $q->whereRaw(sprintf($sqlNormalize, 'fullname') . " LIKE ?", ["%$value%"]);
        }

        // FILTRO FECHA NACIMIENTO
        if (!empty($this->f['birth'])) {
            $q->where('birth_date', 'LIKE', $this->f['birth'] . '%');
        }

        // FILTRO POSICIÓN
        if (!empty($this->f['position'])) {
            $value = $normalize($this->f['position']);
            $q->whereRaw(sprintf($sqlNormalize, 'position') . " LIKE ?", ["%$value%"]);
        }

        // FILTRO EQUIPO
        if (!empty($this->f['team'])) {
            $value = $normalize($this->f['team']);
            $q->whereHas('team', function ($t) use ($value, $sqlNormalize) {
                $t->whereRaw(sprintf($sqlNormalize, 'name') . " LIKE ?", ["%$value%"]);
            });
        }

        return $q->get()->map(function ($p) {
            return [
                'ID'         => $p->id,
                'Nombre'     => $p->fullname,
                'Equipo'     => $p->team->name ?? 'Sin equipo',
                'Nacimiento' => $p->birth_date,
                'Posición'   => $p->position,
            ];
        });
    }

    public function headings(): array
    {
        return ['ID', 'Nombre', 'Equipo', 'Nacimiento', 'Posición'];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet   = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow();

                // Encabezado
                $sheet->getStyle('A1:E1')->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'color' => ['rgb' => '4F81BD'],
                    ],
                ]);

                // Filas alternadas
                for ($row = 2; $row <= $lastRow; $row++) {
                    $sheet->getStyle("A{$row}:E{$row}")->applyFromArray([
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'color' => ['rgb' => ($row % 2 === 0) ? 'F2F2F2' : 'FFFFFF'],
                        ],
                    ]);
                }

                // Bordes
                $sheet->getStyle("A1:E{$lastRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);

                // Centramos todo
                $sheet->getStyle("A1:E{$lastRow}")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // AutoSize
                foreach (range('A', 'E') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
            },
        ];
    }
}
