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
            margin-top: 2.0cm;
            margin-bottom: 2.0cm;
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
    {{-- <div class="watermark">
        <img src="{{ public_path('assets/img/bgsurat/surat.png') }}" alt="" width="100%" height="100%">
    </div> --}}
    <div style="margin-top: 0.3cm; margin-bottom: 0.3cm;">
        <center>
            <span style="font-size: 16pt; font-weight: bold;">SURAT PERNYATAAN PEMBATALAN PESANAN</span>

        </center>
    </div>
    Saya yang bertada tangan dibawah ini :
    <br><br>
    <table class="tabel-info" style="line-height: 1;">
        <tr style="line-height: 1;">
            <td width="35%">Nama Customer</td>
            <td width="1%">:</td>
            <td style="text-transform: uppercase;">{{ $bookingList->getPenawaran->getCustomer->name ?? '-' }}</td>
        </tr>
        <tr style="line-height: 1;">
            <td>NIK</td>
            <td>:</td>
            <td>{{ $bookingList->getPenawaran->getCustomer->nik ?? '-' }}</td>
        </tr>
        <tr style="line-height: 1;">
            <td>Alamat Customer</td>
            <td>:</td>
            <td>{{ $bookingList->getPenawaran->getCustomer->alamat ?? '-' }}</td>
        </tr>
        <tr style="line-height: 1;">
            <td>No. Telepon Customer</td>
            <td>:</td>
            <td>0{{ $bookingList->getPenawaran->getCustomer->nohp ?? '-' }}</td>
        </tr>
        <tr style="line-height: 1;">
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
        <tr style="line-height: 1;">
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

    <div class="statement" style="margin-bottom: 0px;">
        <p style="margin-bottom: 1px;">Dengan ini saya menyatakan <strong><i>Cancel / Batal</i></strong> untuk membeli
            tanah kavling Pesona Maharani
            hal ini dikarenakan : <strong>Karna kebutuhan lainnya</strong></p>
        <p style="text-indent: 30px; margin-bottom: 0;">Saya menyepakati bahwa metode pengembalian uang yang telah
            disetorkan sesuai
            ketentuan management Pesona Maharani sebagai berikut :</p>
    </div>
    <div class="terms" style="margin-top: 0;">
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
        @php
            // Booking fee diambil dari $totalBookingFee dari controller
            $bookingFee = $totalBookingFee ?? 0;

            // Uang muka (DP) diambil dari $totalDownPayment dari controller
            $uangMuka = $totalDownPayment ?? 0;

            // Total angsuran diambil dari $totalAngsuran dari controller
            $angsuran = $totalAngsuran ?? 0;

            $totalMasuk = $bookingFee + $uangMuka + $angsuran;
        @endphp
        <table class="tabel-info" style="line-height: 1;">
            <thead>
                <tr style="background:#f2f2f2; text-align:center; line-height:1;">
                    <th style="padding:2px 4px; line-height:1;">Jenis Pembayaran</th>
                    <th style="padding:2px 4px; line-height:1;">Nominal</th>
                </tr>
            </thead>
            <tbody>
                <tr style="line-height:1;">
                    <td style="padding:2px 4px; line-height:1;">Booking Fee</td>
                    <td style="padding:2px 4px; line-height:1;">
                        Rp {{ number_format($bookingFee, 0, ',', '.') }}
                    </td>
                </tr>
                <tr style="line-height:1;">
                    <td style="padding:2px 4px; line-height:1;">Uang Muka (DP)</td>
                    <td style="padding:2px 4px; line-height:1;">
                        Rp {{ number_format($uangMuka, 0, ',', '.') }}
                    </td>
                </tr>
                <tr style="line-height:1;">
                    <td style="padding:2px 4px; line-height:1;">Total Angsuran Masuk</td>
                    <td style="padding:2px 4px; line-height:1;">
                        Rp {{ number_format($angsuran, 0, ',', '.') }}
                    </td>
                </tr>
                <tr style="line-height:1;">
                    <td style="padding:2px 4px; text-align:right; font-weight:bold; line-height:1;">Total</td>
                    <td style="padding:2px 4px; font-weight:bold; line-height:1;">
                        Rp {{ number_format($totalMasuk, 0, ',', '.') }}
                    </td>
                </tr>
                @if ($bookingFee == 0 && $uangMuka == 0 && $angsuran == 0)
                    <tr style="line-height:1;">
                        <td colspan="2" style="text-align:center;padding:4px; line-height:1;">Tidak ada data
                            pembayaran.</td>
                    </tr>
                @endif
            </tbody>
        </table>

    </div>
    <br><br>
    <table style="width:100%; margin-top:40px;">
        <tr>
            <td style="width:50%; text-align:center; vertical-align:bottom;">
                Menyetujui,<br><br><br>
                <div style="height:70px;"></div>
                <u style="display: inline-block; width: 85%;">&nbsp;</u>
            </td>
            <td style="width:50%; text-align:center; vertical-align:bottom;">
                Pekanbaru, {{ \Carbon\Carbon::now()->format('d F Y') }}<br>
                Yang Menyatakan,<br><br><br>
                <div style="height:70px;"></div>
                <u style="display: inline-block; width: 85%;">&nbsp;</u>
            </td>
        </tr>
    </table>
</body>

</html>
