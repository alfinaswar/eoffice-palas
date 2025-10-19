<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Penawaran Harga - Cetak PDF</title>
    <style>
        @page {
            size: A4;
            margin: 0;
        }

        html,
        body {
            width: 210mm;
            height: 297mm;
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            background-color: #fff;
        }

        body {
            position: relative;
            min-height: 297mm;
        }

        .pdf-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 210mm;
            height: 297mm;
            z-index: 0;
        }

        .pdf-content {
            position: relative;
            z-index: 1;
            padding: 30mm 20mm 20mm 20mm;
        }

        h2,
        h4,
        h5 {
            margin: 0 0 4mm 0;
        }

        .header-table {
            width: 100%;
            margin-bottom: 10mm;
        }

        .header-table td {
            padding: 2mm 0;
            vertical-align: top;
        }

        .info-label {
            font-weight: bold;
            width: 35mm;
        }

        table.detail-produk {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8mm;
            margin-bottom: 10mm;
            font-size: 12px;
        }

        table.detail-produk th,
        table.detail-produk td {
            border: 1px solid #aaa;
            padding: 5px 8px;
            text-align: left;
        }

        table.detail-produk th {
            background: #eee;
            text-align: center;
        }

        .text-end {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .fw-bold {
            font-weight: bold;
        }

        .total-section {
            width: 100%;
            margin-top: 10mm;
            margin-bottom: 4mm;
        }

        .total-label {
            font-weight: bold;
            font-size: 1.1em;
        }
    </style>
</head>

<body>
    <!-- Ganti src sesuai path gambar watermark/background -->
    <img class="pdf-background" src="{{ public_path('images/background-penawaran.png') }}" alt="Background" />

    <div class="pdf-content">
        <h2 style="text-align:center; margin-bottom:2mm;">PENAWARAN HARGA</h2>
        <hr style="margin:0 0 7mm 0;border:0;border-top:2px solid #bbb;">

        <table class="header-table">
            <tr>
                <td class="info-label">Nomor</td>
                <td>: {{ $penawaran->Nomor }}</td>
            </tr>
            <tr>
                <td class="info-label">Tanggal</td>
                <td>: {{ \Carbon\Carbon::parse($penawaran->Tanggal)->format('d-m-Y') }}</td>
            </tr>
            <tr>
                <td class="info-label">Nama Pelanggan</td>
                <td>: {{ $penawaran->NamaPelanggan }}</td>
            </tr>
            @if (!empty($penawaran->Keterangan))
                <tr>
                    <td class="info-label">Keterangan</td>
                    <td>: {{ $penawaran->Keterangan }}</td>
                </tr>
            @endif
        </table>

        <div style="margin-bottom:2mm;"><b>Detail Produk</b></div>
        <table class="detail-produk">
            <thead>
                <tr>
                    <th style="width: 26%">Produk</th>
                    <th style="width: 11%">Harga</th>
                    <th style="width: 14%">Harga Ditawarkan</th>
                    <th style="width: 10%">Diskon</th>
                    <th style="width: 9%">Jenis</th>
                    <th style="width: 10%">Jumlah</th>
                    <th style="width: 15%">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($penawaran->DetailPenawaran as $d)
                    @php
                        $produkItem = $produk->firstWhere('id', $d->IdProduk);
                    @endphp
                    <tr>
                        <td>{{ $produkItem ? $produkItem->Nama : '-' }}</td>
                        <td class="text-end">Rp {{ number_format($d->Harga, 0, ',', '.') }}</td>
                        <td class="text-end">Rp
                            {{ number_format(isset($d->HargaPenawaran) ? $d->HargaPenawaran : $d->Harga, 0, ',', '.') }}
                        </td>
                        <td class="text-end">{{ $d->Diskon }}</td>
                        <td class="text-center">{{ $d->JenisDiskon == 'Persen' ? '%' : $d->JenisDiskon }}</td>
                        <td class="text-center">{{ isset($d->Jumlah) ? $d->Jumlah : 1 }}</td>
                        <td class="text-end">Rp
                            {{ number_format(isset($d->Subtotal) ? $d->Subtotal : (isset($d->subtotal) ? $d->subtotal : 0), 0, ',', '.') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <table class="total-section">
            <tr>
                <td class="total-label" style="width:70%; text-align:right;">Total Penawaran :</td>
                <td class="fw-bold text-end" style="font-size:1.1em;">
                    Rp {{ number_format($penawaran->Total, 0, ',', '.') }}
                </td>
            </tr>
        </table>

        <div style="margin-top:18mm;">
            <div style="width:50%;float:right;text-align:center;">
                <div>Hormat Kami,</div>
                <div style="height:26mm;"></div>
                <div style="font-weight:bold;">(__________________________)</div>
                <div style="font-size:smaller;">PT. Nama Perusahaan</div>
            </div>
        </div>
    </div>
</body>

</html>
