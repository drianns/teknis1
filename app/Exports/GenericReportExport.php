<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Color;

class GenericReportExport implements FromArray, WithHeadings, WithStyles, ShouldAutoSize, WithTitle, WithEvents
{
    protected array  $headings;
    protected array  $rows;
    protected string $title;
    protected string $sheetTitle;

    // Header background color (hex, no #)
    protected string $headerBgColor  = '1E3A5F';   // deep navy
    protected string $headerFontColor = 'FFFFFF';   // white
    protected string $altRowColor    = 'EBF0F8';   // light blue-grey for zebra rows

    public function __construct(array $headings, array $rows, string $title = 'Report', string $sheetTitle = 'Data')
    {
        $this->headings   = $headings;
        $this->rows       = $rows;
        $this->title      = $title;
        $this->sheetTitle = $sheetTitle;
    }

    public function array(): array
    {
        return $this->rows;
    }

    public function headings(): array
    {
        return $this->headings;
    }

    public function title(): string
    {
        return $this->sheetTitle;
    }

    public function styles(Worksheet $sheet)
    {
        $totalCols  = count($this->headings);
        $totalRows  = count($this->rows) + 1; // +1 for header row
        $lastColLtr = $this->colLetter($totalCols);

        // ── Header row styling ────────────────────────────────────────────────
        $sheet->getStyle("A1:{$lastColLtr}1")->applyFromArray([
            'font' => [
                'bold'  => true,
                'size'  => 11,
                'color' => ['rgb' => $this->headerFontColor],
            ],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['rgb' => $this->headerBgColor],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // ── Data rows: zebra striping + base style ────────────────────────────
        for ($row = 2; $row <= $totalRows; $row++) {
            $rangeKey = "A{$row}:{$lastColLtr}{$row}";
            $isAlt    = ($row % 2 === 0);

            $sheet->getStyle($rangeKey)->applyFromArray([
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => $isAlt ? $this->altRowColor : 'FFFFFF'],
                ],
                'font'      => ['size' => 10],
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
            ]);
        }

        // ── Border (entire table) ─────────────────────────────────────────────
        $tableRange = "A1:{$lastColLtr}{$totalRows}";
        $sheet->getStyle($tableRange)->applyFromArray([
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

        // ── Row height ────────────────────────────────────────────────────────
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
                $sheet      = $event->sheet->getDelegate();
                $totalCols  = count($this->headings);
                $lastColLtr = $this->colLetter($totalCols);

                // Freeze header row
                $sheet->freezePane('A2');

                // Auto-fit: ShouldAutoSize handles column widths,
                // but set a max-width cap to prevent overly wide columns
                for ($col = 1; $col <= $totalCols; $col++) {
                    $letter = $this->colLetter($col);
                    $dim    = $sheet->getColumnDimension($letter);
                    // Cap columns at 40 characters wide
                    if ($dim->getWidth() > 40) {
                        $sheet->getColumnDimension($letter)->setWidth(40);
                    }
                }

                // Add report title text above table (row 0 is not possible, so we shift)
                // Instead, set sheet tab color to match header
                $sheet->getTabColor()->setRGB('1E3A5F');
            },
        ];
    }

    /**
     * Convert 1-based column index to Excel letter(s). e.g. 1=A, 26=Z, 27=AA
     */
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
