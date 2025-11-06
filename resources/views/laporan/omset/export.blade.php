<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>Laporan Omset</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            background-color: #fdfdfd;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            background: #fff;
            box-shadow: 0 2px 6px rgba(76, 204, 31, 0.03);
        }

        th,
        td {
            border: 1px solid #b0d6b3;
            padding: 6px 8px;
            text-align: right;
        }

        /* Header tahun (tahun columns) */
        th {
            background-color: #e8f5e9;
            /* lembut hijau muda */
            color: #36844d;
            font-weight: bold;
        }

        th:first-child,
        td:first-child {
            background-color: #b1dfbb;
            color: #226633;
            text-align: left;
            font-weight: bold;
        }

        h2 {
            margin-bottom: 0.5em;
            color: #298f45;
        }

        .text-center {
            text-align: center;
        }

        /* Highlight baris total */
        tr:last-child th,
        tr:last-child td {
            background-color: #8bc34a;
            color: #fff;
            font-weight: bold;
            border-top: 2px solid #388e3c;
        }

        tr:hover td {
            background-color: #f1f8e9;
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
