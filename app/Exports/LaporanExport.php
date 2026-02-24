<?php

namespace App\Exports;

use App\Models\Penghuni;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LaporanExport implements FromCollection, WithHeadings
{
    protected $tahun;

    public function __construct($tahun)
    {
        $this->tahun = $tahun;
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Penghuni',
            'Nomor Kamar',
            'Tarif',
            'Jan','Feb','Mar','Apr','Mei','Jun',
            'Jul','Agu','Sep','Okt','Nov','Des',
            'Total Pembayaran'
        ];
    }

    public function collection()
    {
        $data = [];
        $penghuni = Penghuni::with(['kamar','pembayaran'])
            ->where('kategori_kos','Bulanan')
            ->get();

        foreach($penghuni as $index => $item){

            $bulan = array_fill(1,12,'');

            $total = 0;

            foreach($item->pembayaran as $bayar){
                if(date('Y', strtotime($bayar->tanggal_bayar)) == $this->tahun){
                    $b = date('n', strtotime($bayar->tanggal_bayar));
                    $bulan[$b] = 'X';
                    $total += $bayar->jumlah_bayar;
                }
            }

            $data[] = array_merge([
                $index+1,
                $item->nama_penghuni,
                $item->kamar->nomor_kamar ?? '-',
                $item->kamar->tarif
            ], $bulan, [$total]);
        }

        return collect($data);
    }
}
