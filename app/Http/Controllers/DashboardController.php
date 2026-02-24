<?php

namespace App\Http\Controllers;

use App\Models\Kamar;
use App\Models\Penghuni;
use App\Models\Pembayaran;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $tahun = Carbon::now()->year;
        $bulan = Carbon::now()->month;

        // ===============================
        // DATA SUMMARY
        // ===============================
        $totalKamar = Kamar::count();
        $kamarKosong = Kamar::where('status', 'Kosong')->count();
        $kamarTerisi = Kamar::where('status', 'Terisi')->count();
        $totalPenghuni = Penghuni::count();

        // ===============================
        // PEMASUKAN BULAN INI
        // ===============================
        $pemasukanBulanIni = Pembayaran::whereYear('tanggal_bayar', $tahun)
            ->whereMonth('tanggal_bayar', $bulan)
            ->sum('jumlah_bayar');

        // ===============================
        // TOTAL PENDAPATAN PER KATEGORI
        // ===============================
        $totalPendapatanBulanan = Pembayaran::whereYear('tanggal_bayar', $tahun)
            ->whereHas('penghuni', function ($q) {
                $q->where('kategori_kos', 'Bulanan');
            })
            ->sum('jumlah_bayar');

        $totalPendapatanTahunan = Pembayaran::whereYear('tanggal_bayar', $tahun)
            ->whereHas('penghuni', function ($q) {
                $q->where('kategori_kos', 'Tahunan');
            })
            ->sum('jumlah_bayar');

        // ===============================
        // JUMLAH ORANG LUNAS PER KATEGORI
        // ===============================
        $jumlahLunasBulanan = Pembayaran::whereYear('tanggal_bayar', $tahun)
            ->where('status_pembayaran', 'Lunas')
            ->whereHas('penghuni', function ($q) {
                $q->where('kategori_kos', 'Bulanan');
            })
            ->distinct('id_penghuni')
            ->count('id_penghuni');

        $jumlahLunasTahunan = Pembayaran::whereYear('tanggal_bayar', $tahun)
            ->where('status_pembayaran', 'Lunas')
            ->whereHas('penghuni', function ($q) {
                $q->where('kategori_kos', 'Tahunan');
            })
            ->distinct('id_penghuni')
            ->count('id_penghuni');

        // ===============================
        // DATA PIE CHART (TOTAL PENGHUNI)
        // ===============================
        $jumlahBulanan = Penghuni::where('kategori_kos', 'Bulanan')->count();
        $jumlahTahunan = Penghuni::where('kategori_kos', 'Tahunan')->count();

        return view('dashboard.index', compact(
            'totalKamar',
            'kamarKosong',
            'kamarTerisi',
            'totalPenghuni',
            'pemasukanBulanIni',
            'totalPendapatanBulanan',
            'totalPendapatanTahunan',
            'jumlahLunasBulanan',
            'jumlahLunasTahunan',
            'jumlahBulanan',
            'jumlahTahunan',
            'tahun'
        ));
    }
}
