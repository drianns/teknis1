<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class AgentAuxExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithTitle, WithEvents
{
    protected $data;
    protected $headers;
    protected $title;

    public function __construct($data, $headers, $title = 'Report')
    {
        $this->data    = collect($data);
        $this->headers = $headers;
        $this->title   = $title;
    }

    public function collection()
    {
        return $this->data;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function headings(): array
    {
        return $this->headers;
    }

    public function styles(Worksheet $sheet)
    {
        $totalCols = count($this->headers);
        $totalRows = $this->data->count() + 1;
        $lastCol   = $this->colLetter($totalCols);

        // Header styling
        $sheet->getStyle("A1:{$lastCol}1")->applyFromArray([
            'font' => [
                'bold'  => true,
                'size'  => 11,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '2563EB'], // Blue-600 to match UI
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Zebra striping and row styling
        for ($row = 2; $row <= $totalRows; $row++) {
            $range = "A{$row}:{$lastCol}{$row}";
            $sheet->getStyle($range)->applyFromArray([
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => ($row % 2 === 0) ? 'F8FAFC' : 'FFFFFF'],
                ],
                'font'      => ['size' => 10, 'color' => ['rgb' => '334155']],
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
            ]);
        }

        // Table borders
        $sheet->getStyle("A1:{$lastCol}{$totalRows}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color'       => ['rgb' => 'E2E8F0'],
                ],
                'outline' => [
                    'borderStyle' => Border::BORDER_MEDIUM,
                    'color'       => ['rgb' => '2563EB'],
                ],
            ],
        ]);

        // Row heights
        $sheet->getRowDimension(1)->setRowHeight(30);
        for ($row = 2; $row <= $totalRows; $row++) {
            $sheet->getRowDimension($row)->setRowHeight(22);
        }

        return [];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $sheet->freezePane('A2');
                
                // Set column alignment for specific types if needed
                // e.g., center No column
                $sheet->getStyle('A')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            },
        ];
    }

    private function colLetter(int $index): string
    {
        $letters = '';
        while ($index > 0) {
            $mod     = ($index - 1) % 26;
            $letters = chr(65 + $mod) . $letters;
            $index   = (int)(($index - $mod) / 26);
        }
        return $letters;
    }
}
