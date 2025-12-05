<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>Laporan Unit Belum Terjual</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 5px 8px;
            text-align: left;
        }

        th {
            font-weight: bold;
            background: #eee;
        }

        h2 {
            text-align: center;
            margin-bottom: 1em;
        }

        tfoot th,
        tfoot td {
            font-weight: bold;
            background: #ddd;
        }
    </style>
</head>

<body>
    <h2>LAPORAN UNIT BELUM TERJUAL</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Jenis</th>
                <th>Grade</th>
                <th>Proyek</th>
                <th>Harga Per Meter</th>
                <th>DP</th>
                <th>Besar Angsuran</th>
                <th>Harga Normal</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
                $totalHargaPerMeter = 0;
                $totalDp = 0;
                $totalBesarAngsuran = 0;
                $totalHargaNormal = 0;
            @endphp
            @foreach ($data as $item)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $item->getJenis ? $item->getJenis->Nama : '-' }}</td>
                    <td>{{ $item->getGrade ? $item->getGrade->Nama : '-' }}</td>
                    <td>{{ $item->getProyek ? $item->getProyek->NamaProyek : '-' }}</td>
                    <td style="text-align: right;">
                        {{ 'Rp ' . number_format($item->HargaPerMeter, 0, ',', '.') }}
                        @php $totalHargaPerMeter += $item->HargaPerMeter; @endphp
                    </td>
                    <td style="text-align: right;">
                        {{ 'Rp ' . number_format($item->Dp, 0, ',', '.') }}
                        @php $totalDp += $item->Dp; @endphp
                    </td>
                    <td style="text-align: right;">
                        {{ 'Rp ' . number_format($item->BesarAngsuran, 0, ',', '.') }}
                        @php $totalBesarAngsuran += $item->BesarAngsuran; @endphp
                    </td>
                    <td style="text-align: right;">
                        {{ 'Rp ' . number_format($item->HargaNormal, 0, ',', '.') }}
                        @php $totalHargaNormal += $item->HargaNormal; @endphp
                    </td>
                    <td>Tersedia</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4" style="text-align: center;">Total</th>
                <th style="text-align: right;">{{ 'Rp ' . number_format($totalHargaPerMeter, 0, ',', '.') }}</th>
                <th style="text-align: right;">{{ 'Rp ' . number_format($totalDp, 0, ',', '.') }}</th>
                <th style="text-align: right;">{{ 'Rp ' . number_format($totalBesarAngsuran, 0, ',', '.') }}</th>
                <th style="text-align: right;">{{ 'Rp ' . number_format($totalHargaNormal, 0, ',', '.') }}</th>
                <th></th>
            </tr>
        </tfoot>
    </table>
</body>

</html>
