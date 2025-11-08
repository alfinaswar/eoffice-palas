<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cetak Penawaran Harga</title>
    <style>
        @page {
            margin: 0cm 0cm;
        }

        body {
            font-family: 'Arial ', sans-serif;
            /* font-size: 12px; */
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
            /* width: 21cm;
            height: 29.7cm; */
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


        #tabelTransaksi {
            border-collapse: collapse;
            width: 100%;
            padding: 1px;
            vertical-align: middle;
            line-height: 15px;
        }

        #tabelTransaksi th {
            border: 1px solid #000000;
            text-align: center;
            vertical-align: middle;
        }

        #tabelTransaksi td {
            border: 1px solid #000000;
            padding: 5px;
            text-align: left;
            vertical-align: middle;
        }
    </style>
</head>

<body>
    <div class="watermark">
        <img src="{{ public_path('assets/img/bgsurat/surat.png') }}" alt="" width="100%" height="100%">
    </div>
    <div style="margin-top: 0.3cm; margin-bottom: 0.3cm; align-content:center;">
        <center>
            <span style="font-size: 14pt; font-weight: bold;">PENAWARAN HARGA</span>
        </center>
    </div>
    <table style="margin-top: 0.5cm;" id="header">
        <thead>
            <tr>
                <td width="20%">Nomor Penawaran</td>
                <td width="5%">:</td>
                <td width="75%">{{ $penawaran->Nomor }}</td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Ditujukan Kepada</td>
                <td>:</td>
                <td style="text-transform: uppercase;">BAGIAN ADMINISTRASI PERUSAHAAN</td>
            </tr>
            <tr>
                <td>Nama Calon Customer</td>
                <td>:</td>
                <td style="text-transform: uppercase;">{{ $penawaran->getCustomer->name }}</td>
            </tr>
            <tr>
                <td>Alamat Customer</td>
                <td>:</td>
                <td>{{ $penawaran->getCustomer->alamat }}</td>
            </tr>
            <tr>
                <td>No. Telepon Customer</td>
                <td>:</td>
                <td>0{{ $penawaran->getCustomer->nohp }}</td>
            </tr>
        </tbody>
    </table>
    <br>
    <div>
        Berdasarkan harga yang diajukan oleh calon customer, bersama ini kami dari bagian Marketing mengajukan penawaran
        harga sebagai berikut:
    </div>

    <table id="tabelTransaksi">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Produk</th>
                <th>Jumlah</th>
                <th>Harga</th>
                <th>Diskon</th>
                <th>Harga Penawaran</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @php
                $total = 0;
                $rowCount = count($penawaran->DetailPenawaran);
            @endphp
            @foreach ($penawaran->DetailPenawaran as $index => $item)
                @php
                    $total += $item->Subtotal;
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        {{ $item->getProduk->Nama ?? '-' }}
                    </td>
                    <td>{{ $item->Jumlah }}</td>
                    <td>Rp {{ number_format($item->Harga, 0, ',', '.') }}</td>
                    <td>
                        @if ($item->JenisDiskon === 'Persen')
                            {{ $item->Diskon }}%
                        @elseif ($item->JenisDiskon === 'Rp')
                            Rp {{ number_format($item->Diskon, 0, ',', '.') }}
                        @else
                            -
                        @endif
                    </td>
                    <td>Rp {{ number_format($item->HargaPenawaran, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($item->Subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="6" style="text-align: right; font-weight: bold;">Total</td>
                <td style="font-weight: bold;">Rp {{ number_format($total, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <span>Terbilang : {{ terbilang($total) }} rupiah</span>

    </div>
    <div>
        <p>
            Bersama surat ini, kami dari <strong>Tim Marketing</strong> bermaksud untuk mengajukan penawaran harga atas
            produk/jasa yang telah dibahas dengan calon customer. Penawaran ini kami susun berdasarkan permintaan dari
            pihak customer serta mempertimbangkan harga terbaik yang dapat kami ajukan.
        </p>
        <p>
            Besar harapan kami agar kerjasama ini dapat terwujud serta memberikan manfaat bagi kedua belah pihak. Atas
            perhatian dan kepercayaan yang diberikan, kami ucapkan terima kasih.<br><br>
            Hormat kami,<br>
            <strong>Tim Marketing</strong>
        </p>
    </div>
    <div id="deskripsi">

    </div>
    {{-- <section style="page-break-inside: avoid; break-inside: avoid;">
        <table width="100%">
            <tr>
                <td>Pekanbaru, {{ \Carbon\Carbon::parse($data->TanggalPo)->translatedFormat('d F Y') }}</td>
            </tr>
            <tr>
                <td>Pemesan</td>
                <td>Dibuat Oleh,</td>
                <td>Menyetujui,</td>
            </tr>
            <tr style="line-height: 2cm; vertical-align: middle;">
                <td></td>
                <td>
                    <img src="{{ asset('storage/DigitalSign/' . $data->getUser->DigitalSign ?? null) }}" alt="Signature"
                        style="width: 100px; height: auto;">

                </td>
                <td>
                    <img src="{{ asset('storage/DigitalSign/' . $data->getKaryawan->DigitalSign ?? null) }}"
                        alt="Signature" style="width: 100px; height: auto;">

                </td>
            </tr>
            <tr style="font-weight: bold">
                <td>(.......................................)</td>
                <td>
                    {{ $data->getUser->name }}
                </td>
                <td>
                    {{ $data->getKaryawan->name ?? null }}
                </td>
            </tr>


        </table>

    </section> --}}
</body>

</html>
