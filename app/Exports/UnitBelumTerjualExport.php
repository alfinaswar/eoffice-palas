<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UnitBelumTerjualExport implements FromArray, WithHeadings, WithTitle, WithStyles
{
    protected $products;

    public function __construct($products)
    {
        // $products adalah collection dari Produk
        $this->products = $products;
    }

    public function array(): array
    {
        $rows = [];
        foreach ($this->products as $p) {
            $rows[] = [
                'nama_produk' => $p->Nama ?? '-',
                'grade' => $p->getGrade ? $p->getGrade->Nama : '-',
                'jenis' => $p->getJenis ? $p->getJenis->Nama : '-',
                'proyek' => $p->getProyek ? $p->getProyek->NamaProyek : '-',
                'luas' => $p->Luas ?? '-',
                'harga_per_meter' => $p->HargaPerMeter ?? 0,
                'dp' => $p->Dp ?? 0,
                'besar_angsuran' => $p->BesarAngsuran ?? 0,
                'harga_normal' => $p->HargaNormal ?? 0,
                'status' => 'Tersedia',
            ];
        }
        return $rows;
    }

    public function headings(): array
    {
        return [
            'Nama Produk',
            'Grade',
            'Jenis',
            'Proyek',
            'Luas',
            'Harga Per Meter',
            'DP',
            'Besar Angsuran',
            'Harga Normal',
            'Status',
        ];
    }

    public function title(): string
    {
        return 'Unit Belum Terjual';
    }

    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        // Header bold and center, light green fill
        $headerRange = 'A1:' . $highestColumn . '1';
        $sheet->getStyle($headerRange)->getFont()->setBold(true);
        $sheet->getStyle($headerRange)->getAlignment()->setHorizontal('center');
        $sheet
            ->getStyle($headerRange)
            ->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()
            ->setRGB('E0F7FA');

        // Border all
        $bodyRange = 'A1:' . $highestColumn . $highestRow;
        $sheet->getStyle($bodyRange)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        // Center kolom Status, Grade, Jenis, Proyek
        foreach (['B', 'C', 'D', 'J'] as $col) {
            $sheet->getStyle($col . '2:' . $col . $highestRow)->getAlignment()->setHorizontal('center');
        }
        // Format angka untuk harga/dp/angsuran/harga normal
        foreach (['F', 'G', 'H', 'I'] as $col) {
            $sheet
                ->getStyle($col . '2:' . $col . $highestRow)
                ->getNumberFormat()
                ->setFormatCode('#,##0');
        }
    }
}
