<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Penghuni;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class PembayaranController extends Controller
{
    // =====================================
    // INDEX
    // =====================================
    public function index()
    {
        $penghuni = Penghuni::with('pembayaran')
            ->orderBy('nama_penghuni')
            ->get();

        return view('pembayaran.index', compact('penghuni'));
    }

    // =====================================
    // CREATE
    // =====================================
    public function create(Request $request)
    {
        $penghuni = Penghuni::with('kamar')
                        ->findOrFail($request->penghuni);

        return view('pembayaran.add', compact('penghuni'));
    }

    // =====================================
    // STORE
    // =====================================
    public function store(Request $request)
    {
        $request->validate([
            'id_penghuni'   => 'required|exists:penghuni,id_penghuni',
            'tanggal_bayar' => 'required|date',
            'jumlah_bayar'  => 'required|numeric|min:0', // ← tambah validasi
            'bukti_bayar'   => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $penghuni = Penghuni::findOrFail($request->id_penghuni);

        // Cegah bayar sebelum tanggal masuk
        if ($request->tanggal_bayar < $penghuni->tanggal_masuk) {
            return back()->withErrors([
                'tanggal_bayar' => 'Tanggal bayar tidak boleh sebelum tanggal masuk.'
            ])->withInput();
        }

        $path = null;

        if ($request->hasFile('bukti_bayar')) {
            $path = $request->file('bukti_bayar')
                            ->store('bukti_bayar', 'public');
        }

        Pembayaran::create([
            'id_penghuni'       => $request->id_penghuni,
            'bulan'             => Carbon::parse($request->tanggal_bayar)->translatedFormat('F Y'),
            'jumlah_bayar'      => $request->jumlah_bayar, // ← ambil dari input
            'tanggal_bayar'     => $request->tanggal_bayar,
            'status_pembayaran' => 'Lunas',
            'bukti_bayar'       => $path
        ]);

        return redirect()->route('pembayaran.index')
                        ->with('success', 'Pembayaran berhasil disimpan');
    }

    // =====================================
    // EDIT
    // =====================================
    public function edit($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);
        $penghuni = Penghuni::orderBy('nama_penghuni')->get();

        return view('pembayaran.edit', compact('pembayaran', 'penghuni'));
    }

    // =====================================
    // UPDATE
    // =====================================
    public function update(Request $request, $id)
    {
        $pembayaran = Pembayaran::findOrFail($id);

        $request->validate([
            'id_penghuni'       => 'required|exists:penghuni,id_penghuni',
            'bulan'             => 'required|string',
            'jumlah_bayar'      => 'required|numeric|min:0',
            'tanggal_bayar'     => 'required|date',
            'status_pembayaran' => 'required|in:Lunas,Belum Lunas',
            'bukti_bayar'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        if ($request->hasFile('bukti_bayar')) {

            // Hapus file lama
            if ($pembayaran->bukti_bayar &&
                Storage::disk('public')->exists($pembayaran->bukti_bayar)) {

                Storage::disk('public')->delete($pembayaran->bukti_bayar);
            }

            $path = $request->file('bukti_bayar')
                            ->store('bukti_bayar', 'public');

            $pembayaran->bukti_bayar = $path;
        }

        $pembayaran->id_penghuni       = $request->id_penghuni;
        $pembayaran->bulan             = $request->bulan;
        $pembayaran->jumlah_bayar      = $request->jumlah_bayar;
        $pembayaran->tanggal_bayar     = $request->tanggal_bayar;
        $pembayaran->status_pembayaran = $request->status_pembayaran;

        $pembayaran->save();

        return redirect()->route('pembayaran.index')
                         ->with('success', 'Data pembayaran berhasil diupdate');
    }

    // =====================================
    // DESTROY
    // =====================================
    public function destroy($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);

        if ($pembayaran->bukti_bayar &&
            Storage::disk('public')->exists($pembayaran->bukti_bayar)) {

            Storage::disk('public')->delete($pembayaran->bukti_bayar);
        }

        $pembayaran->delete();

        return redirect()->route('pembayaran.index')
                         ->with('success', 'Data pembayaran berhasil dihapus');
    }

    public function kwitansi($penghuni)
    {
        Carbon::setLocale('id');

        $data = Pembayaran::with(['penghuni.kamar'])
            ->where('id_penghuni', $penghuni)
            ->orderBy('tanggal_bayar', 'desc')
            ->first();

        if (!$data) {
            abort(404);
        }

        $terbilang = strtoupper($this->terbilang($data->jumlah_bayar)) . ' RUPIAH';

        $tanggal = Carbon::now()->format('Y-m-d');

        $namaFile = "Kwitansi-Salsabila-{$tanggal}.pdf";

        $pdf = Pdf::loadView('pembayaran.kwitansi', compact('data', 'terbilang'))
                ->setPaper('A5', 'landscape')
                ->setOption([
                    'margin-top'           => 14,
                    'margin-right'         => 14,
                    'margin-bottom'        => 14,
                    'margin-left'          => 14,
                    'isHtml5ParserEnabled' => true,
                    'dpi'                  => 96,
                ]);

        return $pdf->stream($namaFile);
    }

    private function penyebut($nilai)
    {
        $nilai = abs($nilai);
        $huruf = ["", "satu", "dua", "tiga", "empat", "lima",
                "enam", "tujuh", "delapan", "sembilan",
                "sepuluh", "sebelas"];

        if ($nilai < 12) {
            return " " . $huruf[$nilai];
        } else if ($nilai < 20) {
            return $this->penyebut($nilai - 10) . " belas";
        } else if ($nilai < 100) {
            return $this->penyebut($nilai / 10) . " puluh" . $this->penyebut($nilai % 10);
        } else if ($nilai < 200) {
            return " seratus" . $this->penyebut($nilai - 100);
        } else if ($nilai < 1000) {
            return $this->penyebut($nilai / 100) . " ratus" . $this->penyebut($nilai % 100);
        } else if ($nilai < 2000) {
            return " seribu" . $this->penyebut($nilai - 1000);
        } else if ($nilai < 1000000) {
            return $this->penyebut($nilai / 1000) . " ribu" . $this->penyebut($nilai % 1000);
        }
    }

    private function terbilang($nilai)
    {
        return trim($this->penyebut($nilai));
    }

}
