<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>Laporan Penjualan</title>
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
            box-shadow: 0 2px 6px rgba(31, 120, 204, 0.03);
        }

        th,
        td {
            border: 1px solid #b0d6b3;
            padding: 6px 8px;
            text-align: left;
        }

        th {
            background-color: #e8f5fe;
            color: #17568c;
            font-weight: bold;
        }

        h2 {
            margin-bottom: 0.5em;
            color: #1463a1;
        }

        .text-center {
            text-align: center;
        }

        tr:hover td {
            background-color: #f1f7fd;
        }
    </style>
</head>

<body>
    <h2 class="text-center">TABEL LAPORAN PENJUALAN</h2>
    <table>
        <thead>
            <tr>
                <th>Nomor</th>
                <th>Kode Transaksi</th>
                <th>Produk</th>
                <th>Tanggal Transaksi</th>
                <th>Pelanggan</th>
                <th>Petugas</th>
                <th>Jenis Transaksi</th>
                <th>Total Harga</th>
                <th>Tipe Pembayaran</th>
                <th>Durasi Pembayaran</th>
                <th>Uang Muka</th>
                <th>Sisa Bayar</th>
                <th>Status Pembayaran</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalHarga = 0;
                $totalUangMuka = 0;
                $totalSisaBayar = 0;
            @endphp
            @foreach ($data as $key => $row)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $row->KodeTransaksi }}</td>
                    <td>
                        {{ $row->getProduk ? $row->getProduk->Nama : '-' }}
                    </td>
                    <td>{{ $row->TanggalTransaksi }}</td>
                    <td>
                        {{ $row->getCustomer ? $row->getCustomer->name : '-' }}
                    </td>
                    <td>
                        {{ $row->getPetugas ? $row->getPetugas->name : '-' }}
                    </td>
                    <td>{{ $row->JenisTransaksi ?? '-' }}</td>
                    <td style="text-align:right;">
                        @php $totalHarga += $row->TotalHarga; @endphp
                        {{ 'Rp ' . number_format($row->TotalHarga, 0, ',', '.') }}
                    </td>
                    <td>{{ $row->TipePembayaran ?? '-' }}</td>
                    <td>{{ $row->DurasiPembayaran ?? '-' }}</td>
                    <td style="text-align:right;">
                        @php $totalUangMuka += $row->UangMuka; @endphp
                        {{ 'Rp ' . number_format($row->UangMuka, 0, ',', '.') }}
                    </td>
                    <td style="text-align:right;">
                        @php $totalSisaBayar += $row->SisaBayar; @endphp
                        {{ 'Rp ' . number_format($row->SisaBayar, 0, ',', '.') }}
                    </td>
                    <td>{{ $row->StatusPembayaran ?? '-' }}</td>
                </tr>
            @endforeach
            <tr>
                <th colspan="7" style="text-align:right;">Total</th>
                <th style="text-align:right;">{{ 'Rp ' . number_format($totalHarga, 0, ',', '.') }}</th>
                <th></th>
                <th></th>
                <th style="text-align:right;">{{ 'Rp ' . number_format($totalUangMuka, 0, ',', '.') }}</th>
                <th style="text-align:right;">{{ 'Rp ' . number_format($totalSisaBayar, 0, ',', '.') }}</th>
                <th></th>
            </tr>
        </tbody>
    </table>
</body>

</html>
