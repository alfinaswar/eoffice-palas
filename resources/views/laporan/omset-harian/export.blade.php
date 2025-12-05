<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>Laporan Omset Harian</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            background-color: #fff;
            color: #000;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            background: #fff;
        }

        th,
        td {
            border: 1px solid #222;
            padding: 6px 8px;
            text-align: right;
        }

        th {
            background-color: #f5f5f5;
            color: #111;
            font-weight: bold;
        }

        th:first-child,
        td:first-child {
            background-color: #fff;
            color: #111;
            text-align: left;
            font-weight: bold;
        }

        h2 {
            margin-bottom: 0.5em;
            color: #111;
        }

        .text-center {
            text-align: center;
        }

        tr:last-child th,
        tr:last-child td {
            background-color: #f5f5f5;
            color: #000;
            font-weight: bold;
            border-top: 2px solid #111;
        }

        tr:hover td {
            background-color: #fafafa;
        }
    </style>
</head>

<body>
    <h2 class="text-center">LAPORAN OMSET HARIAN PERIODE {{ $tanggal_dari }} - {{ $tanggal_sampai }}</h2>
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Omset Masuk</th>
                <th>Omset Keluar</th>
                <th>Selisih (Masuk - Keluar)</th>
            </tr>
        </thead>
        <tbody>
            @php
                $total_masuk = 0;
                $total_keluar = 0;
                $total_selisih = 0;
            @endphp
            @foreach ($data as $row)
                @php
                    $masuk = isset($row['omset']) ? $row['omset'] : 0;
                    $keluar = isset($row['keluar']) ? $row['keluar'] : 0;
                    $selisih = $masuk - $keluar;
                    $total_masuk += $masuk;
                    $total_keluar += $keluar;
                    $total_selisih += $selisih;
                @endphp
                <tr>
                    <td>{{ $row['tanggal'] }}</td>
                    <td>{{ 'Rp ' . number_format($masuk, 0, ',', '.') }}</td>
                    <td>{{ 'Rp ' . number_format($keluar, 0, ',', '.') }}</td>
                    <td>{{ 'Rp ' . number_format($selisih, 0, ',', '.') }}</td>
                </tr>
            @endforeach
            <tr>
                <th>Total</th>
                <th>{{ 'Rp ' . number_format($total_masuk, 0, ',', '.') }}</th>
                <th>{{ 'Rp ' . number_format($total_keluar, 0, ',', '.') }}</th>
                <th>{{ 'Rp ' . number_format($total_selisih, 0, ',', '.') }}</th>
            </tr>
        </tbody>
    </table>
</body>

</html>
