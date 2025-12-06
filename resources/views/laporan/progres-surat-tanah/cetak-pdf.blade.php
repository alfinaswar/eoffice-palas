<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>Laporan Progres Surat Tanah</title>
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

        .text-center {
            text-align: center;
        }

        tfoot th,
        tfoot td {
            font-weight: bold;
            background: #ddd;
        }

        .footer {
            margin-top: 18px;
            font-size: 11px;
            color: #666;
            text-align: right;
        }
    </style>
</head>

<body>
    <h2>LAPORAN PROGRES SURAT TANAH</h2>
    <table>
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th>Kode Proyek</th>
                <th>Tanggal</th>
                <th>Deskripsi</th>
                <th>Legal</th>
                <th>Keterangan</th>
                <th>UserCreated</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
                $totalLegal = 0;
            @endphp
            @foreach ($progress as $item)
                @php
                    // Ubah nilai Legal ke format rupiah, tetap hitung total walaupun null
                    $legalValue = is_numeric($item->Legal) ? $item->Legal : 0;
                    $totalLegal += $legalValue;
                @endphp
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td>
                        {{ $item->getProyek->NamaProyek ?? '-' }}
                    </td>
                    <td>
                        {{ $item->created_at ? $item->created_at->format('d-m-Y H:i') : '-' }}
                    </td>
                    <td>
                        {{ $item->Deskripsi ?? '-' }}
                    </td>
                    <td class="text-right">
                        {{ is_numeric($item->Legal) ? 'Rp ' . number_format($item->Legal, 0, ',', '.') : '-' }}
                    </td>
                    <td>
                        {{ $item->Keterangan ?? '-' }}
                    </td>
                    <td>
                        {{ $item->UserCreated ?? '-' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4" class="text-right">Total Legal</th>
                <th class="text-right">{{ 'Rp ' . number_format($totalLegal, 0, ',', '.') }}</th>
                <th colspan="2"></th>
            </tr>
        </tfoot>
    </table>
    <div class="footer">
        Dicetak pada: {{ \Carbon\Carbon::now()->format('d-m-Y H:i') }}
    </div>
</body>

</html>
