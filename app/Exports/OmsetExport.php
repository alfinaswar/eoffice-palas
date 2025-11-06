<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class OmsetExport implements FromArray, WithHeadings, WithTitle, WithStyles, ShouldAutoSize
{
    protected $rows;
    protected $tahunDari;
    protected $tahunSampai;
    protected $withTotalRows;

    public function __construct(array $rows, $tahunDari, $tahunSampai)
    {
        $this->rows = $rows;
        $this->tahunDari = $tahunDari;
        $this->tahunSampai = $tahunSampai;
        $this->withTotalRows = $this->generateWithTotalRows($rows);
    }

    /**
     * Generate data + baris total omset per tahun
     */
    protected function generateWithTotalRows($rows)
    {
        $total = [];
        foreach (range($this->tahunDari, $this->tahunSampai) as $tahun) {
            $total[$tahun] = 0;
        }
        foreach ($rows as $row) {
            foreach (range($this->tahunDari, $this->tahunSampai) as $tahun) {
                $val = isset($row[$tahun]) ? (float) $row[$tahun] : 0;
                $total[$tahun] += $val;
            }
        }
        // Baris untuk total
        $totalRow = ['Total'];
        foreach (range($this->tahunDari, $this->tahunSampai) as $tahun) {
            $totalRow[] = $total[$tahun];
        }
        // Ubah rows ke array numeric (tanpa kunci string)
        $numericData = array_map(function ($item) {
            return array_values((array) $item);
        }, $rows);
        $numericData[] = $totalRow;
        return $numericData;
    }

    /**
     * Data utama untuk di-export ke Excel, termasuk total baris terakhir
     */
    public function array(): array
    {
        return $this->withTotalRows;
    }

    /**
     * Header kolom
     */
    public function headings(): array
    {
        $head = ['Bulan'];
        foreach (range($this->tahunDari, $this->tahunSampai) as $tahun) {
            $head[] = "Omset {$tahun}";
        }
        return $head;
    }

    /**
     * Nama sheet
     */
    public function title(): string
    {
        return "Omset {$this->tahunDari}-{$this->tahunSampai}";
    }

    /**
     * Styling header, border, dan total
     */
    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        // STYLE HEADER
        $headerRange = 'A1:' . $highestColumn . '1';
        $sheet->getStyle($headerRange)->getFont()->setBold(true);
        $sheet->getStyle($headerRange)
            ->getAlignment()->setHorizontal('center');
        $sheet->getStyle($headerRange)
            ->getFill()->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB('E8F5E9'); // Soft green

        // BORDER for all cells
        $bodyRange = 'A1:' . $highestColumn . $highestRow;
        $sheet->getStyle($bodyRange)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        // TOTAL row formatting (baris terakhir)
        $totalRow = $highestRow;
        $totalRange = 'A' . $totalRow . ':' . $highestColumn . $totalRow;
        $sheet->getStyle($totalRange)->getFont()->setBold(true);
        $sheet->getStyle($totalRange)
            ->getFill()->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB('8BC34A');
        $sheet->getStyle($totalRange)
            ->getFont()->getColor()->setRGB('FFFFFF');
        // Center align kolom "Total"
        $sheet->getStyle('A' . $totalRow)->getAlignment()->setHorizontal('center');

        // Format number kolom omset (mulai dari B2 sampai baris terakhir kecuali baris header dan kolom bulan)
        $colCount = count($this->headings());
        for ($i = 2; $i <= $colCount; $i++) {
            $column = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i);
            $sheet->getStyle($column . '2:' . $column . $highestRow)
                ->getNumberFormat()
                ->setFormatCode('"Rp" #,##0');
            // Right align numeric columns
            $sheet->getStyle($column . '1:' . $column . $highestRow)
                ->getAlignment()->setHorizontal('right');
        }
        // Left align kolom 'Bulan'
        $sheet->getStyle('A2:A' . $highestRow)->getAlignment()->setHorizontal('left');

        return [];
    }
}
