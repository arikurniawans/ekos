<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penghuni;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanExport;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $tanggal_awal  = $request->tanggal_awal;
        $tanggal_akhir = $request->tanggal_akhir;
        $kategori      = $request->kategori;
        $statusFilter  = $request->status;

        $today = Carbon::today();

        // =========================
        // AMBIL DATA PENGHUNI + RELASI
        // =========================
        $query = Penghuni::with(['kamar', 'pembayaran']);

        // =========================
        // FILTER KATEGORI (Bulanan/Tahunan)
        // =========================
        if ($kategori) {
            $query->where('kategori_kos', $kategori);
        }

        $penghuni = $query->get();

        // =========================
        // FILTER RENTANG TANGGAL (BERDASARKAN JATUH TEMPO)
        // =========================
        if ($tanggal_awal && $tanggal_akhir) {
            $penghuni = $penghuni->filter(function ($item) use ($tanggal_awal, $tanggal_akhir) {

                $status = $item->status_pembayaran;
                $jatuhTempo = $this->hitungJatuhTempo($item);

                return $jatuhTempo >= $tanggal_awal &&
                       $jatuhTempo <= $tanggal_akhir;
            });
        }

        // =========================
        // FILTER STATUS
        // =========================
        if ($statusFilter) {
            $penghuni = $penghuni->filter(function ($item) use ($statusFilter) {

                $label = $item->status_pembayaran['label'];

                if ($statusFilter == 'lunas') {
                    return str_contains($label, 'Lunas');
                }

                if ($statusFilter == 'belum_jatuh_tempo') {
                    return $label == 'Belum Jatuh Tempo';
                }

                if ($statusFilter == 'mendekati') {
                    return str_contains($label, 'Hari Lagi');
                }

                if ($statusFilter == 'jatuh_tempo') {
                    return $label == 'Jatuh Tempo';
                }

                if ($statusFilter == 'telat') {
                    return str_contains($label, 'Telat Bayar');
                }

                return true;
            });
        }

        // =========================
        // HITUNG SUMMARY
        // =========================
        $total_penghuni = $penghuni->count();

        $total_pemasukan = $penghuni->sum(function ($item) {
            return $item->pembayaran->sum('jumlah_bayar');
        });

        return view('laporan.index', compact(
            'penghuni',
            'tanggal_awal',
            'tanggal_akhir',
            'kategori',
            'statusFilter',
            'total_penghuni',
            'total_pemasukan'
        ));
    }

    // =====================================
    // FUNCTION HITUNG JATUH TEMPO
    // =====================================
    private function hitungJatuhTempo($penghuni)
    {
        $today        = Carbon::today();
        $tanggalMasuk = Carbon::parse($penghuni->tanggal_masuk);

        $pembayaranTerakhir = $penghuni->pembayaran()
            ->whereNotNull('bukti_bayar')
            ->orderByDesc('tanggal_bayar')
            ->first();

        $baseline = $pembayaranTerakhir
            ? Carbon::parse($pembayaranTerakhir->tanggal_bayar)
            : $tanggalMasuk;

        if ($penghuni->kategori_kos == 'Bulanan') {
            return $baseline->copy()->addDays(30);
        }

        if ($penghuni->kategori_kos == 'Tahunan') {
            return $baseline->copy()->addDays(360);
        }

        return $baseline->copy()->addDays(30);
    }

    public function exportPdf(Request $request)
    {
        $tahun = $request->tahun ?? date('Y');
        $kategori = $request->kategori; // ambil dari modal

        if (!$kategori) {
            return back()->with('error', 'Kategori belum dipilih');
        }

        $penghuni = \App\Models\Penghuni::with(['kamar','pembayaran'])
            ->where('kategori_kos', $kategori)
            ->get();

        // Tentukan view berdasarkan kategori
        $view = $kategori == 'Tahunan'
            ? 'laporan.export_pdf_tahunan'
            : 'laporan.export_pdf_bulanan';

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
            $view,
            compact('penghuni','tahun')
        )->setPaper('A4','landscape');

        return $pdf->download('laporan-'.$kategori.'-'.$tahun.'.pdf');
    }

    public function exportExcel(Request $request)
    {
        $kategori = $request->kategori;

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\LaporanExport($kategori),
            'laporan-kos-salsabila-kategori-'.$kategori.'.xlsx'
        );
    }

}
