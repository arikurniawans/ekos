<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
body { font-family: Arial; font-size: 11px; }
table { border-collapse: collapse; width: 100%; }
th, td { border: 1px solid #000; padding: 4px; text-align: center; }
th { background: yellow; }
.header { text-align: center; font-weight: bold; margin-bottom: 10px; }
.text-left { text-align: left; }
.text-right { text-align: right; }
</style>
</head>
<body>

<div class="header">
    <div>Rekapitulasi Laporan Kos-kosan Salsabila</div>
    <div>Kategori Bulanan</div>
    <div>Tahun {{ $tahun }}</div>
</div>

<table>
<thead>
<tr>
    <th>No</th>
    <th>Nama Penghuni Kos</th>
    <th>Nomor Kamar</th>
    <th>Tarif</th>
    <th>Jan</th>
    <th>Feb</th>
    <th>Mar</th>
    <th>Apr</th>
    <th>Mei</th>
    <th>Jun</th>
    <th>Jul</th>
    <th>Agu</th>
    <th>Sep</th>
    <th>Okt</th>
    <th>Nov</th>
    <th>Des</th>
    <th>Total Pembayaran</th>
</tr>
</thead>
<tbody>

@php
$totalPemasukan = 0;
@endphp

@foreach($penghuni as $index => $item)

@php
$totalBayar = 0;
$bulanBayar = [];

foreach($item->pembayaran as $bayar){
    if(date('Y', strtotime($bayar->tanggal_bayar)) == $tahun){
        $bulanBayar[] = date('n', strtotime($bayar->tanggal_bayar));
        $totalBayar += $bayar->jumlah_bayar;
    }
}

$totalPemasukan += $totalBayar;
@endphp

<tr>
<td>{{ $index+1 }}</td>
<td class="text-left">{{ $item->nama_penghuni }}</td>
<td>{{ $item->kamar->nomor_kamar ?? '-' }}</td>
<td class="text-right">Rp {{ number_format($item->kamar->tarif ?? 0,0,',','.') }}</td>

@for($i=1;$i<=12;$i++)
<td>{{ in_array($i,$bulanBayar) ? 'X' : '' }}</td>
@endfor

<td class="text-right">
Rp {{ number_format($totalBayar,0,',','.') }}
</td>
</tr>

@endforeach

<tr>
<td colspan="16" class="text-right"><strong>Total Pemasukan</strong></td>
<td class="text-right">
<strong>Rp {{ number_format($totalPemasukan,0,',','.') }}</strong>
</td>
</tr>

</tbody>
</table>

</body>
</html>
