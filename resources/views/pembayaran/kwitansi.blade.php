<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
* { margin:0; padding:0; box-sizing:border-box; }

body {
    font-family: DejaVu Sans, sans-serif;
    font-size: 7.5pt;
    color: #333;
}

/* HEADER */
.t-header { width:100%; border-collapse:collapse; margin-bottom:3pt; }
.logo-main { font-size:12pt; font-weight:bold; color:#1a1a1a; line-height:1.1; }
.logo-sub  { font-size:8.5pt; font-weight:bold; color:#4ab0c1; letter-spacing:1pt; }
.hd-right  {
    text-align:right; font-size:6.5pt; color:#444;
    vertical-align:top; line-height:1.5; width:35%;
    padding-right:2pt;
}
.hd-right b { font-size:7pt; color:#222; }

/* GARIS */
.t-line { width:100%; border-collapse:collapse; margin:2pt 0; }
.t-line td { border-top:0.8pt solid #aaa; padding:0; height:0; line-height:0; font-size:0; }

/* TITLE */
.t-title { width:100%; border-collapse:collapse; margin-bottom:4pt; }
.t-title td {
    background-color:#e2e2e2;
    text-align:center; padding:3.5pt 0;
    border-top:0.5pt solid #bbb; border-bottom:0.5pt solid #bbb;
    font-size:10pt; font-weight:bold; letter-spacing:4pt; color:#555;
}

/* INFO TABLE */
.t-info { width:100%; border-collapse:collapse; table-layout:fixed; }
.t-info td {
    padding:2.5pt 5pt;
    vertical-align:top; font-size:7.5pt; line-height:1.45;
    border:0.1pt solid transparent;
}
.row-odd   td { background-color:#f4f4f4; }
.row-even  td { background-color:#ffffff; }
.row-nominal   td { background-color:#e2e2e2 !important; padding:3.5pt 5pt; border-top:0.5pt solid #ccc; border-bottom:0.5pt solid #ccc; }
.row-terbilang td { background-color:#e2e2e2 !important; padding:3.5pt 5pt; border-bottom:0.5pt solid #ccc; }

.c-lbl { width:23%; color:#555; }
.c-val { width:77%; }

.nominal-text   { font-size:13pt; font-weight:bold; font-style:italic; color:#444; }
.terbilang-text { font-size:9.5pt; font-weight:bold; font-style:italic; color:#444; }

/* FOOTER */
.t-footer { width:100%; border-collapse:collapse; margin-top:5pt; }
.ft-note  { font-style:italic; font-size:6.5pt; color:#777; text-align:center; vertical-align:middle; }
.ft-sign  {
    text-align:right; font-size:7pt; color:#444;
    line-height:1.5; vertical-align:top; width:24%;
    padding-right:20pt;
}
</style>
</head>
<body>

{{-- HEADER --}}
<table class="t-header">
    <tr>
        <td style="vertical-align:top; width:65%;">
            <div class="logo-main">KOS-KOSAN</div>
            <div class="logo-sub">SALSABILA</div>
        </td>
        <td class="hd-right">
            <b>KOS-KOSAN SALSABILA</b><br>
            Jl. Lintas Bar., Sukajaya<br>
            Kec. Sukarami, Palembang<br>
            0823-7149-3333
        </td>
    </tr>
</table>

{{-- GARIS --}}
<table class="t-line"><tr><td></td></tr></table>

{{-- TITLE --}}
<table class="t-title"><tr><td>K W I T A N S I</td></tr></table>

{{-- INFO --}}
<table class="t-info">
    <colgroup>
        <col style="width:23%;">
        <col style="width:77%;">
    </colgroup>
    <tr class="row-odd">
        <td class="c-lbl">Nomor Kamar:</td>
        <td class="c-val"><b>{{ $data->penghuni->kamar->nomor_kamar }}</b></td>
    </tr>
    <tr class="row-even">
        <td class="c-lbl">Telah terima dari :</td>
        <td class="c-val">
            <b>{{ $data->penghuni->nama_penghuni }}</b><br>
            {{ $data->penghuni->no_hp }}<br>
            Kategori Kos ({{ $data->penghuni->kategori_kos }})
        </td>
    </tr>
    <tr class="row-odd">
        <td class="c-lbl">Untuk Pembayaran :</td>
        <td class="c-val">
            @if($data->penghuni->kategori_kos == 'Bulanan')
                Kost {{ \Carbon\Carbon::parse($data->tanggal_bayar)->translatedFormat('F Y') }}
            @else
                Kost Tahunan {{ \Carbon\Carbon::parse($data->tanggal_bayar)->year }}
            @endif
        </td>
    </tr>
    <tr class="row-nominal">
        <td class="c-lbl">Terbilang :</td>
        <td class="c-val"><span class="nominal-text">Rp.{{ number_format($data->jumlah_bayar, 0, ',', '.') }},-</span></td>
    </tr>
    <tr class="row-terbilang">
        <td class="c-lbl">Uang Sejumlah :</td>
        <td class="c-val"><span class="terbilang-text">{{ $terbilang }}</span></td>
    </tr>
</table>

{{-- SIGN AREA --}}
<table style="width:100%; border-collapse:collapse; margin-top:4pt;">
    <tr>
        <td style="width:70%;"></td>
        <td style="text-align:right; font-size:7pt; color:#444; line-height:1.8; padding-right:20pt; vertical-align:top;">
            Palembang, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br><br>
            Bapak Sohar
        </td>
    </tr>
</table>

{{-- GARIS BAWAH --}}
<table class="t-line" style="margin-top:6pt;"><tr><td></td></tr></table>

{{-- NOTE --}}
<table style="width:100%; border-collapse:collapse; margin-top:5pt;">
    <tr>
        <td style="text-align:center; font-style:italic; font-size:6.5pt; color:#777;">
            Terima kasih atas kepercayaan anda.
        </td>
    </tr>
</table>

</body>
</html>
