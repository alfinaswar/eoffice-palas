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

        .text-right {
            text-align: right;
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
                <th>Proyek</th>
                <th>Sisa Kavling</th>
                <th>Harga Kredit</th>
                <th>Harga Cash</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalSisaKavling = 0;
            @endphp
            @foreach ($summary as $item)
                <tr>
                    <td>{{ $item['LokasiProyek'] }}</td>
                    <td class="text-right">
                        {{ $item['SisaKavling'] }}
                        @php $totalSisaKavling += $item['SisaKavling']; @endphp
                    </td>
                    <td class="text-right">{{ $item['HargaKredit'] }}</td>
                    <td class="text-right">{{ $item['HargaCash'] }}</td>
                    <td>{{ $item['Keterangan'] }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th>Total</th>
                <th class="text-right">{{ $totalSisaKavling }}</th>
                <th colspan="3"></th>
            </tr>
        </tfoot>
    </table>
</body>

</html>
