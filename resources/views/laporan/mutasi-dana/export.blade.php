<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Mutasi Dana</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            color: #333;
        }

        .judul {
            text-align: center;
            margin-bottom: 15px;
        }

        .info {
            margin-bottom: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }

        th,
        td {
            border: 1px solid #222;
            padding: 6px 8px;
            text-align: left;
        }

        th {
            background: #f2f2f2;
            text-align: center;
        }

        .rupiah {
            text-align: right;
            white-space: nowrap;
        }

        .bank-title {
            font-size: 16px;
            font-weight: bold;
            margin-top: 30px;
            margin-bottom: 5px;
        }

        .text-red {
            color: #d32f2f !important;
        }

        .saldo-awal-row {
            /* Bold for saldo awal row */
            font-weight: bold;
        }

        .total-row {
            background: #eaeaea;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="judul">
        <h2>LAPORAN MUTASI DANA PT.TANAH EMAS INDONESIA KANTOR CABANG PEKANBARU</h2>
        <h2>PERIODE:
            {{ date('d', strtotime($tanggal_awal)) }}
            {{ \Illuminate\Support\Carbon::parse($tanggal_awal)->translatedFormat('F') }}
            {{ date('Y', strtotime($tanggal_awal)) }}
            s/d
            {{ date('d', strtotime($tanggal_akhir)) }}
            {{ \Illuminate\Support\Carbon::parse($tanggal_akhir)->translatedFormat('F') }}
            {{ date('Y', strtotime($tanggal_akhir)) }}
        </h2>
        <div class="info">
            <br>
            <span>
                Bank:
                @php
                    // Inisialisasi nama bank & nama rekening
                    $bankNama = '-';
                    $Rekening = '-';

                    if (isset($nama_bank) && $nama_bank !== 'semua') {
                        if (isset($data) && count($data) > 0) {
                            // Cek jika $data adalah koleksi transaksi satu bank
                            if (isset($data[0]->getNamaBank)) {
                                $bankObj = $data[0]->getNamaBank;
                                $bankNama = $bankObj->Nama ?? '-';
                                $Rekening = $bankObj->Rekening ?? '-';
                            }
                            // Cek jika $data[0] adalah array dari struktur per-bank (format multiple bank)
                            elseif (isset($data[0]['bank'])) {
                                $bankNama = $data[0]['bank']->Nama ?? '-';
                                $Rekening = $data[0]['bank']->Rekening ?? '-';
                            }
                        }
                        echo $bankNama;
                        if ($Rekening !== '-' && $Rekening !== '') {
                            echo ' | Rekening: ' . $Rekening;
                        }
                    } else {
                        echo 'Semua Bank';
                    }
                @endphp
            </span>
        </div>
    </div>

    @php
        function rupiah($angka)
        {
            return 'Rp ' . number_format($angka, 0, ',', '.');
        }

        // Untuk label jenis.
        function jenisLabel($jenis)
        {
            if (strtoupper($jenis) == 'IN') {
                return 'Masuk';
            } elseif (strtoupper($jenis) == 'OUT') {
                return 'Keluar';
            }
            return ucfirst($jenis);
        }
    @endphp

    @if (isset($nama_bank) && $nama_bank !== 'semua')
        {{-- Satu bank, gunakan susunan kolom seperti file export-semua --}}
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Deskripsi</th>
                    <th>Jenis</th>
                    <th class="rupiah">Nominal</th>
                    <th class="rupiah">Saldo Setelah</th>
                </tr>
            </thead>
            <tbody>
                {{-- Baris Saldo Awal --}}
                <tr class="saldo-awal-row">
                    <td colspan="5">Saldo Awal</td>
                    <td class="rupiah">{{ rupiah($saldo_awal ?? 0) }}</td>
                </tr>
                @php
                    $no = 1;
                    $total_in = 0;
                    $total_out = 0;
                    $saldo_akhir = $saldo_awal ?? 0;
                @endphp
                @foreach ($data as $row)
                    @php
                        $isOut = strtoupper($row->Jenis) == 'OUT';
                        $isIn = strtoupper($row->Jenis) == 'IN';
                        if ($isIn) {
                            $total_in += $row->Nominal;
                            $saldo_akhir += $row->Nominal;
                        } elseif ($isOut) {
                            $total_out += $row->Nominal;
                            $saldo_akhir -= $row->Nominal;
                        }
                    @endphp
                    <tr>
                        <td align="center">{{ $no++ }}</td>
                        <td>{{ date('d/m/Y', strtotime($row->Tanggal)) }}</td>
                        <td>{{ $row->Deskripsi }}</td>
                        <td>{{ jenisLabel($row->Jenis) }}</td>
                        <td class="rupiah @if ($isOut) text-red @endif">
                            {{ rupiah($row->Nominal) }}
                        </td>
                        <td class="rupiah">{{ rupiah($row->SaldoSetelah) }}</td>
                    </tr>
                @endforeach
                <tr class="total-row">
                    <td colspan="5">Total Masuk</td>
                    <td class="rupiah">{{ rupiah($total_in) }}</td>
                </tr>
                <tr class="total-row">
                    <td colspan="5">Total Keluar</td>
                    <td class="rupiah text-red">{{ rupiah($total_out) }}</td>
                </tr>
                <tr class="total-row">
                    <td colspan="5">Saldo Akhir</td>
                    <td class="rupiah">{{ rupiah($saldo_akhir) }}</td>
                </tr>
            </tbody>
        </table>
    @else
        {{-- Semua bank --}}
        @foreach ($data as $item)
            <div class="bank-title">
                {{ $item['bank']->Nama ?? '-' }}
            </div>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Deskripsi</th>
                        <th>Jenis</th>
                        <th class="rupiah">Nominal</th>
                        <th class="rupiah">Saldo Setelah</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Baris Saldo Awal --}}
                    <tr class="saldo-awal-row">
                        <td colspan="5">Saldo Awal</td>
                        <td class="rupiah">
                            {{ rupiah($saldo_awal_per_bank[$item['bank']->id] ?? 0) }}
                        </td>
                    </tr>
                    @php
                        $no = 1;
                        $total_in = 0;
                        $total_out = 0;
                        $saldo_awal = $saldo_awal_per_bank[$item['bank']->id] ?? 0;
                        $saldo_akhir = $saldo_awal;
                    @endphp
                    @foreach ($item['transaksi'] as $row)
                        @php
                            $isOut = strtoupper($row->Jenis) == 'OUT';
                            $isIn = strtoupper($row->Jenis) == 'IN';
                            if ($isIn) {
                                $total_in += $row->Nominal;
                                $saldo_akhir += $row->Nominal;
                            } elseif ($isOut) {
                                $total_out += $row->Nominal;
                                $saldo_akhir -= $row->Nominal;
                            }
                        @endphp
                        <tr>
                            <td align="center">{{ $no++ }}</td>
                            <td>{{ date('d/m/Y', strtotime($row->Tanggal)) }}</td>
                            <td>{{ $row->Deskripsi }}</td>
                            <td>{{ jenisLabel($row->Jenis) }}</td>
                            <td class="rupiah @if ($isOut) text-red @endif">
                                {{ rupiah($row->Nominal) }}
                            </td>
                            <td class="rupiah">{{ rupiah($row->SaldoSetelah) }}</td>
                        </tr>
                    @endforeach
                    {{-- Total Baris --}}
                    <tr class="total-row">
                        <td colspan="5">Total Masuk</td>
                        <td class="rupiah">{{ rupiah($total_in) }}</td>
                    </tr>
                    <tr class="total-row">
                        <td colspan="5">Total Keluar</td>
                        <td class="rupiah text-red">{{ rupiah($total_out) }}</td>
                    </tr>
                    <tr class="total-row">
                        <td colspan="5">Saldo Akhir</td>
                        <td class="rupiah">{{ rupiah($saldo_akhir) }}</td>
                    </tr>
                </tbody>
            </table>
        @endforeach
    @endif
</body>

</html>
