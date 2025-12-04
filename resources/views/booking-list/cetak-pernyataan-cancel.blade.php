<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Surat Pernyataan Pembatalan Pesanan</title>
    <style>
        @page {
            margin: 0cm 0cm;
        }

        body {
            font-family: 'Arial', sans-serif;
            margin-top: 4.0cm;
            margin-bottom: 4.0cm;
            margin-left: 2.54cm;
            margin-right: 2.54cm;
            font-size: 14px;
            text-align: justify;
            line-height: 0.7cm;
        }

        .header {
            text-align: center;
        }

        .watermark {
            position: fixed;
            bottom: 0px;
            left: 0px;
            top: 0px;
            right: 0px;
            width: 21cm;
            height: 29.7cm;
            z-index: -10;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            margin-top: 20px;
        }

        .tabel-info {
            width: 100%;
            margin-bottom: 0px;
        }

        .tabel-info td {
            padding: 3px 5px;
            vertical-align: top;
        }

        .tanda-tangan {
            width: 100%;
            margin-top: 2cm;
        }

        .tanda-tangan td {
            vertical-align: top;
            text-align: center;
            padding: 0 8px;
        }
    </style>
</head>

<body>
    <div class="watermark">
        <img src="{{ public_path('assets/img/bgsurat/surat.png') }}" alt="" width="100%" height="100%">
    </div>
    <div style="margin-top: 0.3cm; margin-bottom: 0.3cm;">
        <center>
            <span style="font-size: 16pt; font-weight: bold;">SURAT PERNYATAAN PEMBATALAN PESANAN</span>

        </center>
    </div>
    Saya yang bertada tangan dibawah ini :
    <table class="tabel-info">
        <tr>
            <td width="35%">Nama Customer</td>
            <td width="1%">:</td>
            <td style="text-transform: uppercase;">{{ $bookingList->getPenawaran->getCustomer->name ?? '-' }}</td>
        </tr>
        <tr>
            <td>NIK</td>
            <td>:</td>
            <td>{{ $bookingList->getPenawaran->getCustomer->nik ?? '-' }}</td>
        </tr>
        <tr>
            <td>Alamat Customer</td>
            <td>:</td>
            <td>{{ $bookingList->getPenawaran->getCustomer->alamat ?? '-' }}</td>
        </tr>
        <tr>
            <td>No. Telepon Customer</td>
            <td>:</td>
            <td>0{{ $bookingList->getPenawaran->getCustomer->nohp ?? '-' }}</td>
        </tr>
        <tr>
            <td>Lokasi Proyek</td>
            <td>:</td>
            <td>
                @if (isset($bookingList->getPenawaran->DetailPenawaran) && count($bookingList->getPenawaran->DetailPenawaran))
                    @foreach ($bookingList->getPenawaran->DetailPenawaran as $index => $item)
                        {{ $item->getProduk->getProyek->AlamatProyek ?? '-' }}@if (!$loop->last)
                            ,
                        @endif
                    @endforeach
                @else
                    -
                @endif
            </td>
        </tr>
        <tr>
            <td>Produk</td>
            <td>:</td>
            <td>
                @if (isset($bookingList->getPenawaran->DetailPenawaran) && count($bookingList->getPenawaran->DetailPenawaran))
                    @foreach ($bookingList->getPenawaran->DetailPenawaran as $index => $item)
                        {{ $item->getProduk->Nama ?? '-' }}@if (!$loop->last)
                            ,
                        @endif
                    @endforeach
                @else
                    -
                @endif
            </td>
        </tr>
    </table>

    <div class="statement" style="margin-bottom: 10px;">
        <p style="margin-bottom: 6px;">Dengan ini saya menyatakan <strong><i>Cancel / Batal</i></strong> untuk membeli
            tanah kavling Pesona Maharani
            hal ini dikarenakan : <strong>Karna kebutuhan lainnya</strong></p>
        <p style="text-indent: 40px; margin-bottom: 0;">Saya menyepakati bahwa metode pengembalian uang yang telah
            disetorkan sesuai
            ketentuan management Pesona Maharani sebagai berikut :</p>
    </div>
    <div class="terms">
        <ol>
            <li>Lahan tanah kavling tersebut dapat dijual Kembali kepada Pihak Lain.</li>
            <li>Pembayaran dana pengembalian setelah lahan tanah kavling tsb terjual kembali kepada Pihak Lain yang
                telah menandatangani Surat Perjanjian Jual Beli di Notaris atau selambat- lambatnya 3 (tiga) bulan
                setelah pengajuan cancel / pembatalan.</li>
            <li>Dana pengembalian Cancel / Pembatalan dikenakan potongan biaya administrasi sebesar 50 % sesuai
                <strong>"Surat Perjanjian Jual Beli Pasal 8 Point 2"</strong>
            </li>
        </ol>
    </div>
    <div>
        Adapun Pembayaran / Uang Masuk :
        <table style="width:100%; font-size:13px; border-collapse:collapse; margin-top:8px; margin-bottom: 10px;"
            border="1">
            <thead>
                <tr style="background:#f2f2f2; text-align:center;">
                    <th style="padding:4px;">Jenis Pembayaran</th>
                    <th style="padding:4px;">Tanggal</th>
                    <th style="padding:4px;">Nominal</th>
                    <th style="padding:4px;">Bank</th>
                    <th style="padding:4px;">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @if ($bookingList->getDp)
                    <tr>
                        <td style="padding:4px;">Booking Fee</td>
                        <td style="padding:4px;">
                            {{ \Carbon\Carbon::parse($bookingList->getDp->TanggalBayar ?? $bookingList->getDp->created_at)->translatedFormat('d F Y') ?? '-' }}
                        </td>
                        <td style="padding:4px;">Rp
                            {{ number_format($bookingList->getDp->NominalBayar ?? 0, 0, ',', '.') }}</td>
                        <td style="padding:4px;">
                            {{ $bookingList->getDp->getBank->NamaBank ?? '-' }}
                        </td>
                        <td style="padding:4px;">
                            {{ $bookingList->getDp->Keterangan ?? '-' }}
                        </td>
                    </tr>
                @endif

                @if ($bookingList->getTransaksiHeader && ($bookingList->getTransaksiHeader->JenisPembayaran ?? '') == 'DP')
                    <tr>
                        <td style="padding:4px;">Uang Muka (DP)</td>
                        <td style="padding:4px;">
                            {{ \Carbon\Carbon::parse($bookingList->getTransaksiHeader->TanggalBayar ?? $bookingList->getTransaksiHeader->created_at)->translatedFormat('d F Y') ?? '-' }}
                        </td>
                        <td style="padding:4px;">Rp
                            {{ number_format($bookingList->getTransaksiHeader->TotalBayar ?? 0, 0, ',', '.') }}</td>
                        <td style="padding:4px;">
                            {{ $bookingList->getTransaksiHeader->getBank->NamaBank ?? '-' }}
                        </td>
                        <td style="padding:4px;">
                            {{ $bookingList->getTransaksiHeader->Keterangan ?? '-' }}
                        </td>
                    </tr>
                @endif



                @if (
                    !$bookingList->getDp &&
                        !($bookingList->getTransaksiHeader && ($bookingList->getTransaksiHeader->JenisPembayaran ?? '') == 'DP') &&
                        !(
                            $bookingList->getTransaksiHeader &&
                            $bookingList->getTransaksiHeader->getTransaksi &&
                            $bookingList->getTransaksiHeader->getTransaksi->count()
                        ))
                    <tr>
                        <td colspan="5" style="text-align:center;padding:7px;">Tidak ada data pembayaran.</td>
                    </tr>
                @endif
            </tbody>
        </table>

    </div>
    <table class="tanda-tangan" style="margin-top: 2.5cm;">
        <tr>
            <td>Pekanbaru, {{ \Carbon\Carbon::parse($bookingList->TanggalCancel)->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td style="height: 60px;">&nbsp;</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">
                {{ $bookingList->getPenawaran->getCustomer->name ?? '-' }}
            </td>
        </tr>
    </table>

    <br>
    <br>
    <div style="page-break-inside: avoid; break-inside: avoid;">
        <table width="80%" style="font-size:12px; margin-top:40px;">
            <tr>
                <td width="50%" style="text-align:left;">
                    Dibuat Oleh:
                    <br><br><br>
                    @if ($bookingList->UserCancel)
                        <span style="font-weight: bold;">{{ $bookingList->UserCancel }}</span>
                    @else
                        <span style="font-weight: bold;">-</span>
                    @endif
                </td>
                <td width="50%" style="text-align:left;">
                    Customer,
                    <br><br><br>
                    <span style="font-weight: bold;">{{ $bookingList->getPenawaran->getCustomer->name ?? '-' }}</span>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
