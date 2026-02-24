<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
body { font-family: Arial, sans-serif; font-size: 12px; }
table { border-collapse: collapse; width: 100%; }
th, td { border: 1px solid #000; padding: 6px; text-align: center; }
th { background: #ffff00; font-weight: bold; }
.header { text-align: center; font-weight: bold; margin-bottom: 15px; }
.text-left { text-align: left; }
.text-right { text-align: right; }
.status-check { color: green; font-weight: bold; font-size: 16px; }
.status-cross { color: red; font-weight: bold; font-size: 16px; }
</style>
</head>
<body>

<div class="header">
    <div>Rekapitulasi Laporan Kos-kosan Salsabila</div>
    <div>Kategori Tahunan</div>
    <div>Tahun {{ $tahun }}</div>
</div>

<table>
<thead>
<tr>
    <th>No</th>
    <th>Nama Penghuni</th>
    <th>Nomor Kamar</th>
    <th>Tarif</th>
    <th>Status Pembayaran</th>
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

/*
|------------------------------------------------------------
| Hitung pembayaran hanya untuk tahun yang dipilih
|------------------------------------------------------------
*/
foreach($item->pembayaran as $bayar){
    if(date('Y', strtotime($bayar->tanggal_bayar)) == $tahun){
        $totalBayar += $bayar->jumlah_bayar;
    }
}

$totalPemasukan += $totalBayar;
@endphp

<tr>
    <td>{{ $index + 1 }}</td>

    <td class="text-left">
        {{ $item->nama_penghuni }}
    </td>

    <td>
        {{ $item->kamar->nomor_kamar ?? '-' }}
    </td>

    <td class="text-right">
        Rp {{ number_format($item->kamar->tarif ?? 0, 0, ',', '.') }}
    </td>

    {{-- STATUS PEMBAYARAN --}}
    <td>
        @php
            $sudahBayar = false;

            foreach($item->pembayaran as $bayar){
                if(date('Y', strtotime($bayar->tanggal_bayar)) == $tahun){
                    $sudahBayar = true;
                    break;
                }
            }
        @endphp

        @if($sudahBayar)
        <span style="color: green; font-weight: bold; font-size:14px;">
            LUNAS
        </span>
        @else
        <span style="color: red; font-weight: bold; font-size:14px;">
            BELUM LUNAS
        </span>
        @endif
    </td>

    <td class="text-right">
        Rp {{ number_format($totalBayar, 0, ',', '.') }}
    </td>
</tr>

@endforeach

{{-- TOTAL PEMASUKAN --}}
<tr>
    <td colspan="5" class="text-right">
        <strong>Total Pemasukan</strong>
    </td>
    <td class="text-right">
        <strong>Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</strong>
    </td>
</tr>

</tbody>
</table>

</body>
</html>
