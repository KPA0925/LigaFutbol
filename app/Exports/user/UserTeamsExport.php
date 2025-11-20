<?php

namespace App\Exports\User;

use App\Models\Team;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class UserTeamsExport implements FromCollection, WithHeadings, WithEvents
{
    protected $f;

    public function __construct($filters)
    {
        $this->f = $filters;
    }

    public function collection()
    {
        // Normaliza filtro
        $normalize = function ($str) {
            $str = strtolower($str ?? '');
            $str = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
            return trim($str);
        };

        // Función SQL para normalizar acentos dentro de PostgreSQL
        $sqlNormalize = "
            LOWER(
                TRANSLATE(%s,
                    'ÁÉÍÓÚÀÈÌÒÙÄËÏÖÜáéíóúàèìòùäëïöüÑñ',
                    'AEIOUAEIOUAEIOUaeiouaeiouNn'
                )
            )
        ";

        $q = Team::query();

        // FILTRO NOMBRE
        if (!empty($this->f['name'])) {
            $value = $normalize($this->f['name']);
            $q->whereRaw(sprintf($sqlNormalize, 'name') . " LIKE ?", ["%{$value}%"]);
        }

        // FILTRO CIUDAD
        if (!empty($this->f['city'])) {
            $value = $normalize($this->f['city']);
            $q->whereRaw(sprintf($sqlNormalize, 'city') . " LIKE ?", ["%{$value}%"]);
        }

        // FILTRO ESTADIO
        if (!empty($this->f['stadium'])) {
            $value = $normalize($this->f['stadium']);
            $q->whereRaw(sprintf($sqlNormalize, 'stadium') . " LIKE ?", ["%{$value}%"]);
        }

        return $q->get()->map(function ($t) {
            return [
                'ID'        => $t->id,
                'Nombre'    => $t->name,
                'Ciudad'    => $t->city,
                'Estadio'   => $t->stadium,
                'Capacidad' => $t->capacity,
                'Fundado'   => $t->founded_date,
                'Jugadores' => $t->players()->count(),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nombre',
            'Ciudad',
            'Estadio',
            'Capacidad',
            'Fundado',
            'Jugadores',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet   = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow();

                // Encabezado con color rojo del tema
                $sheet->getStyle('A1:G1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF']
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'color' => ['rgb' => 'D62027'], // Color rojo
                    ],
                ]);

                // Filas alternadas
                for ($row = 2; $row <= $lastRow; $row++) {
                    $sheet->getStyle("A{$row}:G{$row}")->applyFromArray([
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'color' => ['rgb' => ($row % 2 == 0) ? 'F2F2F2' : 'FFFFFF'],
                        ],
                    ]);
                }

                // Bordes
                $sheet->getStyle("A1:G{$lastRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);

                // Alinear centrado
                $sheet->getStyle("A1:G{$lastRow}")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Auto tamaño
                foreach (range('A', 'G') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
            },
        ];
    }
}