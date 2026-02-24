<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Penghuni;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PembayaranController extends Controller
{
    // =====================================
    // INDEX
    // =====================================
    public function index()
    {
        $penghuni = \App\Models\Penghuni::with('pembayaran')
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
}
