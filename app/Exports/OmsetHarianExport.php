<?php

namespace App\Exports;

use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OmsetHarianExport implements FromCollection, WithHeadings
{
    protected $tanggalDari;
    protected $tanggalSampai;

    public function __construct($tanggalDari, $tanggalSampai)
    {
        $this->tanggalDari = $tanggalDari;
        $this->tanggalSampai = $tanggalSampai;
    }

    public function collection()
    {
        $from = Carbon::parse($this->tanggalDari)->startOfDay();
        $to = Carbon::parse($this->tanggalSampai)->endOfDay();

        // Query transaksi, filter by tanggal, dan grup per hari, sum total omset
        $query = Transaksi::whereBetween('created_at', [$from, $to])
            ->selectRaw('DATE(created_at) as tanggal, SUM(total_bayar) as total_omset')
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'asc')
            ->get();

        // Mapping ke format array sesuai kebutuhan Excel
        $rows = [];
        foreach ($query as $row) {
            $rows[] = [
                'tanggal' => $row->tanggal,
                'total_omset' => $row->total_omset,
            ];
        }

        return new Collection($rows);
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Total Omset',
        ];
    }
}
