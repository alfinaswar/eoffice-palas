<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>Laporan Omset</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            background-color: #fff;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            background: #fff;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 6px 8px;
            text-align: right;
        }

        th {
            font-weight: bold;
        }

        th:first-child,
        td:first-child {
            text-align: left;
            font-weight: bold;
        }

        h2 {
            margin-bottom: 0.5em;
            color: #222;
        }

        .text-center {
            text-align: center;
        }

        tr:last-child th,
        tr:last-child td {
            font-weight: bold;
            border-top: 2px solid #222;
        }
    </style>
</head>

<body>
    <h2 class="text-center">TABEL LAPORAN OMSET PERIODE {{ $tahun_dari }} - {{ $tahun_sampai }}</h2>
    <table>
        <thead>
            <tr>
                <th>Bulan</th>
                @for ($tahun = $tahun_dari; $tahun <= $tahun_sampai; $tahun++)
                    <th>{{ $tahun }}</th>
                @endfor
            </tr>
        </thead>
        <tbody>
            @php
                // Inisialisasi total per tahun
                $grandTotal = [];
                for ($tahun = $tahun_dari; $tahun <= $tahun_sampai; $tahun++) {
                    $grandTotal[$tahun] = 0;
                }
            @endphp
            @foreach ($data as $row)
                <tr>
                    <td>{{ $row['bulan'] }}</td>
                    @for ($tahun = $tahun_dari; $tahun <= $tahun_sampai; $tahun++)
                        @php
                            $value = isset($row[$tahun]) ? $row[$tahun] : 0;
                            $grandTotal[$tahun] += $value;
                        @endphp
                        <td>{{ 'Rp ' . number_format($value, 0, ',', '.') }}</td>
                    @endfor
                </tr>
            @endforeach
            <tr>
                <th>Total</th>
                @for ($tahun = $tahun_dari; $tahun <= $tahun_sampai; $tahun++)
                    <th>{{ 'Rp ' . number_format($grandTotal[$tahun], 0, ',', '.') }}</th>
                @endfor
            </tr>
        </tbody>
    </table>
</body>

</html>
