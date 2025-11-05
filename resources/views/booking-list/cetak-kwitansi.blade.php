<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kwitansi Pembayaran Booking</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            line-height: 1.2;
            padding: 5px;
            margin: 0;
            height: 100%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table {
            margin-bottom: 5px;
        }

        .header-table td {
            text-align: center;
            padding: 5px;
            border-bottom: 2px solid #000;
        }

        .header-table h2 {
            font-size: 14px;
            font-weight: bold;
        }

        .main-table {
            border: 1px solid #000;
            margin-bottom: 5px;
        }

        .main-table td {
            padding: 3px 6px;
            vertical-align: top;
        }

        .label-col {
            width: 130px;
        }

        .value-col {
            border-bottom: 1px dotted #000;
        }

        .checkbox {
            width: 15px;
            height: 15px;
            border: 1px solid #000;
            display: inline-block;
            text-align: center;
            line-height: 15px;
            vertical-align: middle;
        }

        .notes-table {
            background-color: #FFD700;
            border: 1px solid #000;
            margin-bottom: 5px;
        }

        .notes-table td {
            padding: 6px;
        }

        .notes-table h3 {
            font-size: 11px;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .notes-table ol {
            margin-left: 18px;
        }

        .notes-table li {
            margin-bottom: 2px;
            font-size: 9px;
        }

        .signature-table {
            border: 1px solid #000;
        }

        .signature-table td {
            width: 50%;
            padding: 10px;
            text-align: center;
            vertical-align: top;
            height: 110px;
            border-right: 1px solid #000;
        }

        .signature-table td:last-child {
            border-right: none;
        }

        .signature-table h4 {
            font-size: 11px;
            margin-bottom: 60px;
        }

        .signature-line {
            border-bottom: 1px dotted #000;
            width: 120px;
            margin: 0 auto;
        }

        .no-border {
            border: none !important;
        }

        .border-right {
            border-right: 1px solid #000;
        }

        @page {
            margin: 0;
        }

        html,
        body {
            height: 100%;
            overflow: hidden;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <table class="header-table">
        <tr>
            <td>
                <h2>KWITANSI PEMBAYARAN BOOKING</h2>
            </td>
        </tr>
    </table>

    <!-- Main Form Section -->
    <table class="main-table">
        <tr>
            <td colspan="6" style="padding: 5px;">
                <strong>Telah Diterima :</strong>
            </td>
        </tr>
        <tr>
            <td class="label-col">Nama</td>
            <td class="value-col" colspan="2">: {{ $data->getCustomer->name ?? '-' }}</td>
            <td class="label-col" style="padding-left: 20px;">Nomor Booking</td>
            <td class="value-col" colspan="2">: {{ $data->Nomor ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label-col">Lokasi Proyek</td>
            <td class="value-col" colspan="2">: {{ $data->getProduk->getProyek->AlamatProyek ?? '-' }}</td>
            <td class="label-col" style="padding-left: 20px;">Hari/Tanggal</td>
            <td class="value-col" colspan="2">:
                {{ $data->tanggal ? \Carbon\Carbon::parse($data->tanggal)->isoFormat('dddd, D MMMM YYYY') : '-' }}
            </td>
        </tr>
        <tr>
            <td class="label-col">Pilihan Kavling</td>
            <td class="value-col" colspan="2">: {{ $data->getProduk->Nama ?? '-' }}</td>
            <td class="label-col" style="padding-left: 20px;">Keterangan</td>
            <td class="value-col" colspan="2">: {{ $data->Keterangan ?? '(Transfer / Tunai)*' }}</td>
        </tr>
        <tr>
            <td class="label-col">Pembayaran Sebesar</td>
            <td class="value-col" colspan="2">: Rp. {{ number_format($data->pembayaran_sebesar ?? 0, 0, ',', '.') }}
            </td>
            <td colspan="3" style="padding-left: 20px; padding-top: 5px;">
                <span class="checkbox">{{ $data->tipe_bayar == 'BOOKING' ? '✓' : '' }}</span> BOOKING
                {{-- <span style="margin-left: 20px;" class="checkbox">{{ $data->tipe_bayar == 'DP' ? '✓' : '' }}</span> DP --}}
            </td>
        </tr>
        <tr>
            <td colspan="6" style="padding-top: 5px; border-bottom: 1px dotted #000;"></td>
        </tr>
        <tr>
            <td class="label-col" style="padding-top: 5px;">Catatan:</td>
            <td colspan="5" style="padding-top: 5px;"></td>
        </tr>
        <tr>
            <td colspan="6" style="border-bottom: 1px dotted #000; padding-bottom: 8px;"></td>
        </tr>
    </table>

    <!-- Notes Section -->
    <table class="notes-table">
        <tr>
            <td>
                <h3>Ketentuan :</h3>
                <ol>
                    <li><strong>Booking Fee minimal Sebesar Rp. 1.000.000,-</strong></li>
                    <li>Booking fee dapat dikembalikan 100% jika konfirmasi pembatalan dilakukan maximal 3 hari setelah
                        pembayaran booking.</li>
                    <li>Jika konfirmasi pembatalan booking disampaikan lebih dari 3 hari, maka dikenakan potongan biaya
                        administrasi sebesar 50%</li>
                    <li>Pengembalian dana booking dibayarkan setelah lahan yang dibooking terjual kembali.</li>
                    <li>Dengan menandatangani kwitansi ini, berarti Anda telah menyetujui semua ketentuan yang berlaku.
                    </li>
                    <li>
                        Include Surat SKGR
                        <span class="checkbox">{{ $data->include_skgr ? '✓' : '' }}</span>
                        Tidak Include Surat SKGR
                        <span class="checkbox">{{ !$data->include_skgr ? '✓' : '' }}</span>
                    </li>
                    <li>
                        <strong>Metode Pembayaran :</strong>
                        <div style="margin-left: 20px; margin-top: 5px;">
                            <div style="margin-bottom: 5px;">
                                1. Cash <span class="checkbox">{{ $data->metode_bayar == 'cash' ? '✓' : '' }}</span>
                            </div>
                            <div style="margin-bottom: 5px;">
                                2. Cash Tempo : 3 Kali Bayar <span
                                    class="checkbox">{{ $data->metode_bayar == 'tempo_3x' ? '✓' : '' }}</span>
                                4 kali Bayar <span
                                    class="checkbox">{{ $data->metode_bayar == 'tempo_4x' ? '✓' : '' }}</span>
                                5 Kali Bayar <span
                                    class="checkbox">{{ $data->metode_bayar == 'tempo_5x' ? '✓' : '' }}</span>
                                6 Kali Bayar <span
                                    class="checkbox">{{ $data->metode_bayar == 'tempo_6x' ? '✓' : '' }}</span>
                            </div>
                            <div>
                                3. Kredit <span
                                    class="checkbox">{{ $data->metode_bayar == 'kredit' ? '✓' : '' }}</span>
                            </div>
                        </div>
                    </li>
                </ol>
            </td>
        </tr>
    </table>

    <!-- Signature Section -->
    <table class="signature-table">
        <tr>
            <td>
                <h4>Penerima</h4>
                <div class="signature-line"></div>
            </td>
            <td>
                <h4>Penyetor</h4>
                <div class="signature-line"></div>
            </td>
        </tr>
    </table>
</body>

</html>
