<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Kwitansi Pembayaran Booking - Template</title>
    <style>
        /* Page size: setengah A4 (landscape A5) */
        @page {
            size: 21cm 14.85cm;
            margin: 0;
        }

        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        /* Canvas area: 0.7cm padding inside page for safe printing */
        .page {
            box-sizing: border-box;
            width: 21cm;
            height: 14.85cm;
            padding: 0.7cm;
            font-family: "Arial", "Helvetica", sans-serif;
            font-size: 12px;
            color: #111;
        }

        /* Overall layout: two columns: left narrow yellow column, right big form */
        .container {
            display: flex;
            height: 100%;
            gap: 0.5cm;
        }

        /* Left yellow column */
        .left {
            width: 6.2cm;
            /* adjust to visually match photo */
            background: #ffd949;
            /* warm yellow */
            padding: 0.45cm;
            box-sizing: border-box;
            border: 1px solid #d9b400;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            gap: 0.35cm;
        }

        .left h4 {
            margin: 0;
            font-size: 13px;
            text-align: left;
        }

        .left ol {
            margin: 0;
            padding-left: 1.05em;
            line-height: 1.2;
            font-size: 11px;
        }

        .left .metode {
            margin-top: 6px;
            display: flex;
            gap: 0.25cm;
            flex-wrap: wrap;
        }

        .chk {
            display: flex;
            align-items: center;
            gap: 4px;
            font-size: 11px;
        }

        .left .small-note {
            font-size: 10px;
            margin-top: auto;
            line-height: 1.05;
        }

        /* Right form column */
        .right {
            flex: 1;
            border: 1px solid #999;
            padding: 0.35cm;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        /* Header row (title + logo) */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header .title {
            font-weight: 700;
            font-size: 14px;
            letter-spacing: 0.5px;
        }

        .logo {
            width: 3.6cm;
            text-align: right;
            font-size: 11px;
        }

        /* Main info table (two columns of labels/values) */
        table.info {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            font-size: 12px;
        }

        table.info td.label {
            width: 28%;
            vertical-align: top;
            padding: 4px 6px 4px 0;
            font-weight: 600;
        }

        table.info td.colon {
            width: 2%;
            padding: 4px 2px;
        }

        table.info td.value {
            width: 70%;
            padding: 4px 2px;
            border-bottom: 1px dotted #999;
        }

        /* Right side small form for booking number and method */
        .meta-row {
            display: flex;
            gap: 12px;
            align-items: flex-start;
        }

        .meta-block {
            flex: 1;
        }

        .meta-block .field {
            display: flex;
            gap: 6px;
            align-items: center;
            margin-bottom: 6px;
        }

        .field .line {
            flex: 1;
            border-bottom: 1px dotted #666;
            height: 1px;
        }

        /* Big "Pembayaran sebesar" line */
        .amount {
            margin-top: 6px;
            font-weight: 700;
        }

        .amount .rp {
            display: inline-block;
            width: 6.5cm;
            border-bottom: 1px dotted #333;
            padding-bottom: 2px;
            margin-left: 8px;
        }

        /* Booking / DP checkboxes */
        .checkboxes {
            display: flex;
            gap: 16px;
            margin-top: 6px;
        }

        .checkboxes label {
            display: inline-flex;
            gap: 6px;
            align-items: center;
        }

        .checkboxes .box {
            width: 14px;
            height: 14px;
            border: 1px solid #333;
            display: inline-block;
        }

        /* Footer signature area */
        .footer {
            margin-top: auto;
            display: flex;
            justify-content: space-between;
            gap: 0.5cm;
            align-items: flex-end;
        }

        .sig {
            width: 40%;
            min-height: 2.4cm;
            border-top: 1px solid #111;
            text-align: center;
            padding-top: 6px;
            font-size: 11px;
        }

        .sig.small {
            width: 28%;
        }

        /* For dotted long lines used as fill-in */
        .dotted-line {
            border-bottom: 1px dotted #666;
            display: block;
            height: 1.1em;
        }

        /* Small vertical note area on very bottom-right */
        .catatan {
            margin-top: 6px;
            font-size: 10px;
        }

        /* Ensure print colors (yellow) print with background */
        @media print {
            .left {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }

        /* Reduce text slightly to make fit */
        .muted {
            color: #444;
            font-weight: normal;
            font-size: 11px;
        }
    </style>
</head>

<body>
    <div class="page">
        <div class="container">
            <!-- LEFT YELLOW COLUMN -->
            <div class="left">
                <h4>Ketentuan :</h4>
                <ol>
                    <li>Booking fee minimal sebesar Rp. 1.000.000,-</li>
                    <li>Booking fee dianggap sebagai konfirmasi dan tidak dapat dibatalkan.</li>
                    <li>Konfirmasi pembatalan maksimal 3 hari setelah pembayaran booking.</li>
                    <li>Penyelesaian pembayaran sesuai jadwal yang disepakati.</li>
                    <li>Seluruh perubahan dikomunikasikan ke admin.</li>
                    <li>Surat SKGR akan dikeluarkan setelah pelunasan penuh.</li>
                    <li>Biaya administrasi apabila ada: 50% dari booking fee.</li>
                </ol>

                <div class="metode">
                    <div class="chk"><input type="checkbox" /> Cash</div>
                    <div class="chk"><input type="checkbox" /> Transfer</div>
                    <div class="chk"><input type="checkbox" /> Cek/Giro</div>
                </div>

                <div class="small-note">
                    <strong>Catatan:</strong><br />
                    Simpan kwitansi ini sebagai bukti pembayaran. Kwitansi dianggap sah bila ditandatangani pihak
                    penerima.
                </div>
            </div>

            <!-- RIGHT FORM COLUMN -->
            <div class="right">
                <div class="header">
                    <div class="title">KWITANSI PEMBAYARAN BOOKING</div>
                    <div class="logo">
                        <!-- Jika ada logo, bisa diganti dengan <img src="logo.png" style="max-width:100%;"> -->
                        <div style="opacity:.9;">[Logo / Nama Perusahaan]</div>
                    </div>
                </div>

                <!-- Top meta (nomor booking + tanggal) -->
                <div class="meta-row" style="margin-top:6px;">
                    <div class="meta-block">
                        <div class="field">
                            <div class="label muted" style="width:5.3cm;">Nomor Booking</div>
                            <div class="line"></div>
                        </div>
                        <div class="field">
                            <div class="label muted" style="width:5.3cm;">Nama</div>
                            <div class="line"></div>
                        </div>
                        <div class="field">
                            <div class="label muted" style="width:5.3cm;">Lokasi Proyek</div>
                            <div class="line"></div>
                        </div>
                        <div class="field">
                            <div class="label muted" style="width:5.3cm;">Pilihan Kavling</div>
                            <div class="line"></div>
                        </div>
                    </div>

                    <div class="meta-block" style="max-width:9.2cm;">
                        <div class="field">
                            <div class="label muted" style="width:4.6cm;">Hari / Tanggal</div>
                            <div class="line"></div>
                        </div>
                        <div class="field">
                            <div class="label muted" style="width:4.6cm;">Keterangan</div>
                            <div class="line"></div>
                        </div>
                        <div class="field">
                            <div class="label muted" style="width:4.6cm;">Pilihan Pembayaran</div>
                            <div style="display:flex;gap:8px;">
                                <div style="flex:1;border-bottom:1px dotted #666;height:1px;"></div>
                            </div>
                        </div>
                        <div style="height:6px;"></div>
                        <div style="font-size:11px;" class="muted">Pembayaran Sebesar :</div>
                        <div class="amount">
                            Rp <span
                                class="rp">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                        </div>
                    </div>
                </div>

                <!-- small table-like info for transfer/tunai and booking/dp -->
                <div style="display:flex;gap:12px;margin-top:8px;align-items:flex-start;">
                    <div style="flex:1; font-size:12px;">
                        <div style="display:flex;gap:8px;align-items:center;">
                            <div style="width:6.2cm; font-weight:600;">Via</div>
                            <div style="flex:1;border-bottom:1px dotted #666;height:1px;"></div>
                            <div style="width:9px;"></div>
                            <div style="font-size:11px;">(Transfer / Tunai)</div>
                        </div>
                    </div>

                    <div style="width:9cm;">
                        <div class="checkboxes">
                            <label><span class="box"></span> BOOKING</label>
                            <label><span class="box"></span> DP</label>
                        </div>
                    </div>
                </div>

                <!-- dotted line -->
                <div style="height:8px;"></div>

                <!-- Signature area & penerima/penyetor -->
                <div class="footer">
                    <div>
                        <div style="font-size:11px; margin-bottom:6px;">Penerima</div>
                        <div class="sig">Nama / Tanda Tangan<br /><span
                                class="muted">(...........................)</span></div>
                    </div>

                    <div style="flex:1;">
                        <div style="font-size:11px; margin-bottom:6px;">Penyetor</div>
                        <div class="sig">Nama / Tanda Tangan<br /><span
                                class="muted">(...........................)</span></div>
                    </div>

                    <div style="width:6.6cm;">
                        <div style="font-size:11px; margin-bottom:6px;">Keterangan</div>
                        <div style="border:1px solid #ddd; padding:6px; min-height:2.3cm; font-size:11px;">
                            <span class="muted">Catatan / keterangan tambahan</span>
                        </div>
                    </div>
                </div>

            </div> <!-- end right -->
        </div> <!-- end container -->
    </div> <!-- end page -->
</body>

</html>
