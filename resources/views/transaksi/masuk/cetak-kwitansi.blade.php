<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kwitansi Pembayaran</title>
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
                <h2>KWITANSI PEMBAYARAN</h2>
            </td>
        </tr>
    </table>

    <!-- Main Kwitansi Table -->
    <table class="main-table">
        <tr>
            <td colspan="6" style="padding: 5px;">
                <strong>Sudah terima dari</strong>
            </td>
        </tr>
        <tr>
            <td class="label-col">Nama</td>
            <td class="value-col" colspan="2">: {{ $data->getCustomer->name ?? '-' }}</td>
            <td class="label-col" style="padding-left: 20px;">Nomor Transaksi</td>
            <td class="value-col" colspan="2">: {{ $data->KodeBayar ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label-col">Alamat</td>
            <td class="value-col" colspan="2">: {{ $data->getCustomer->alamat ?? '-' }}</td>
            <td class="label-col" style="padding-left: 20px;">Tanggal</td>
            <td class="value-col" colspan="2">:
                {{ $data->DibayarPada ? \Carbon\Carbon::parse($data->DibayarPada)->isoFormat('dddd, D MMMM YYYY') : '-' }}
            </td>
        </tr>
        <tr>
            <td class="label-col">Uang Sejumlah</td>
            <td class="value-col" colspan="5">: <strong>Rp.
                    {{ number_format($data->TotalPembayaran ?? 0, 0, ',', '.') }}</strong></td>
        </tr>
        <tr>
            <td class="label-col">Terbilang</td>
            <td class="value-col" colspan="5">:
                <em>{{ terbilang($data->TotalPembayaran) }}</em>
            </td>
        </tr>
        <tr>
            <td class="label-col">Untuk Pembayaran</td>
            <td class="value-col" colspan="5">:
                Untuk pembayaran cicilan atau tagihan yang ke
                <strong>{{ $data->CicilanKe ?? '-' }}</strong><br>
                <small>
                    <em>
                        (Kode Bayar: {{ $data->KodeBayar ?? '-' }})
                    </em>
                </small>
            </td>
        </tr>
        {{-- <tr>
            <td class="label-col">Metode Pembayaran</td>
            <td class="value-col" colspan="5">:
                @php
                    $label = '-';
                    if ($data->metode_bayar == 'cash') {
                        $label = 'Cash';
                    } elseif ($data->metode_bayar == 'tempo_3x') {
                        $label = 'Cash Tempo 3x';
                    } elseif ($data->metode_bayar == 'tempo_4x') {
                        $label = 'Cash Tempo 4x';
                    } elseif ($data->metode_bayar == 'tempo_5x') {
                        $label = 'Cash Tempo 5x';
                    } elseif ($data->metode_bayar == 'tempo_6x') {
                        $label = 'Cash Tempo 6x';
                    } elseif ($data->metode_bayar == 'kredit') {
                        $label = 'Kredit';
                    }
                @endphp
                {{ $label }}
            </td>
        </tr> --}}
    </table>

    <br>

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

    <!-- Notes Section -->
    <table class="notes-table" style="margin-top: 8px;">
        <tr>
            <td>
                <h3>Ketentuan & Catatan Penting:</h3>
                <ol>
                    <li>Pastikan data transaksi sudah benar sebelum melakukan pembayaran.</li>
                    <li>Simpan kwitansi ini sebagai bukti pembayaran yang sah.</li>
                    <li>Pembayaran diterima hanya melalui rekening/cara pembayaran resmi yang ditunjuk oleh perusahaan.
                    </li>
                    <li>Keabsahan kwitansi berlaku jika disertai tanda tangan penerima dan penyetor.</li>
                    <li>Kwitansi ini tidak dapat digunakan untuk proses lain di luar transaksi yang tertera di atas.
                    </li>
                    <li>Pembayaran dianggap lunas apabila seluruh tagihan sudah dibayarkan sesuai dengan nominal yang
                        tercantum.</li>
                    <li>Segala pertanyaan atau komplain mengenai pembayaran harus disampaikan selambat-lambatnya 3
                        (tiga) hari setelah transaksi dilakukan.</li>
                </ol>
            </td>
        </tr>
    </table>
</body>

</html>
