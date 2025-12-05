<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>Laporan Penjualan</title>
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
            box-shadow: none;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px 8px;
            text-align: left;
        }

        th {
            background-color: #fff;
            color: #000;
            font-weight: bold;
        }

        h2 {
            margin-bottom: 0.5em;
            color: #000;
        }

        .text-center {
            text-align: center;
        }

        tr:hover td {
            background-color: #fff;
        }
    </style>
</head>

<body>
    <h2 class="text-center text-uppercase">LAPORAN PENJUALAN {{ strtoupper($nama_proyek) }}</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Transaksi</th>
                <th>Produk & Grade</th>
                <th>Luas</th>
                <th>Nama Pelanggan</th>
                <th>Booking Fee</th>
                <th>DP</th>
                <th>Total Harga</th>
                <th>Sisa Pembayaran</th>
                <th>Total Uang Masuk</th>
                <th>Tanggal Booking</th>
                <th>No HP</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalHarga = 0;
                $totalBookingFee = 0;
                $totalDp = 0;
                $totalSisaPembayaran = 0;
                $totalUangMasuk = 0;
            @endphp
            @foreach ($data as $key => $row)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $row->KodeTransaksi ?? '-' }}</td>
                    <td>{{ $row->ProdukGrade ?? '-' }}</td>
                    <td style="text-align:right;">{{ $row->Luas !== '-' ? $row->Luas . ' m²' : '-' }}</td>
                    <td>{{ $row->NamaPelanggan ?? '-' }}</td>
                    <td style="text-align:right;">
                        @php $totalBookingFee += $row->BookingFee; @endphp
                        {{ 'Rp ' . number_format($row->BookingFee, 0, ',', '.') }}
                    </td>
                    <td style="text-align:right;">
                        @php $totalDp += $row->Dp; @endphp
                        {{ 'Rp ' . number_format($row->Dp, 0, ',', '.') }}
                    </td>
                    <td style="text-align:right;">
                        @php $totalHarga += $row->TotalHarga; @endphp
                        {{ 'Rp ' . number_format($row->TotalHarga, 0, ',', '.') }}
                    </td>
                    <td style="text-align:right;">
                        @php $totalSisaPembayaran += $row->SisaPembayaran; @endphp
                        {{ 'Rp ' . number_format($row->SisaPembayaran, 0, ',', '.') }}
                    </td>
                    <td style="text-align:right;">
                        @php $totalUangMasuk += $row->TotalUangMasuk; @endphp
                        {{ 'Rp ' . number_format($row->TotalUangMasuk, 0, ',', '.') }}
                    </td>
                    <td>{{ $row->TanggalBooking ?? '-' }}</td>
                    <td>{{ $row->NoHP ?? '-' }}</td>
                </tr>
            @endforeach
            <tr>
                <th colspan="5" style="text-align:right;">Total</th>
                <th style="text-align:right;">{{ 'Rp ' . number_format($totalBookingFee, 0, ',', '.') }}</th>
                <th style="text-align:right;">{{ 'Rp ' . number_format($totalDp, 0, ',', '.') }}</th>
                <th style="text-align:right;">{{ 'Rp ' . number_format($totalHarga, 0, ',', '.') }}</th>
                <th style="text-align:right;">{{ 'Rp ' . number_format($totalSisaPembayaran, 0, ',', '.') }}</th>
                <th style="text-align:right;">{{ 'Rp ' . number_format($totalUangMasuk, 0, ',', '.') }}</th>
                <th></th>
                <th></th>
            </tr>
        </tbody>
    </table>
</body>

</html>
