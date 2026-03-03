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

class UserExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithTitle, WithEvents
{
    protected $users;
    protected $groupedBy;

    public function __construct($users, $groupedBy = [])
    {
        $this->users     = $users;
        $this->groupedBy = $groupedBy;
    }

    public function collection()
    {
        return $this->users;
    }

    public function title(): string
    {
        return 'User Data';
    }

    public function headings(): array
    {
        return [
            'User Name',
            'Name',
            'Level User',
            'Email Address',
            'Department',
            'Group',
            'Status',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $totalCols  = count($this->headings());
        $totalRows  = $this->users->count() + 1;
        $lastCol    = $this->colLetter($totalCols);

        // Header styling
        $sheet->getStyle("A1:{$lastCol}1")->applyFromArray([
            'font' => [
                'bold'  => true,
                'size'  => 11,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1E3A5F'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Zebra striping on data rows
        for ($row = 2; $row <= $totalRows; $row++) {
            $range = "A{$row}:{$lastCol}{$row}";
            $sheet->getStyle($range)->applyFromArray([
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => ($row % 2 === 0) ? 'EBF0F8' : 'FFFFFF'],
                ],
                'font'      => ['size' => 10],
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
            ]);
        }

        // Table borders
        $sheet->getStyle("A1:{$lastCol}{$totalRows}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color'       => ['rgb' => 'C5D0E0'],
                ],
                'outline' => [
                    'borderStyle' => Border::BORDER_MEDIUM,
                    'color'       => ['rgb' => '1E3A5F'],
                ],
            ],
        ]);

        // Row heights
        $sheet->getRowDimension(1)->setRowHeight(24);
        for ($row = 2; $row <= $totalRows; $row++) {
            $sheet->getRowDimension($row)->setRowHeight(20);
        }

        return [];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $sheet->freezePane('A2');
                $sheet->getTabColor()->setRGB('1E3A5F');

                // Cap column widths
                for ($col = 1; $col <= count($this->headings()); $col++) {
                    $letter = $this->colLetter($col);
                    if ($sheet->getColumnDimension($letter)->getWidth() > 40) {
                        $sheet->getColumnDimension($letter)->setWidth(40);
                    }
                }
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
