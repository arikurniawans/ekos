<?php

namespace App\Http\Controllers;
use App\Models\Penghuni;
use App\Models\Kamar;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PenghuniController extends Controller
{
    //
    public function index()
    {
        $penghuni = Penghuni::with('kamar')->get();
        return view('penghuni.index', compact('penghuni'));
    }

    public function create()
    {
        $kamar = Kamar::where('status', 'Kosong')->get();
        return view('penghuni.add', compact('kamar'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_penghuni' => 'required',
            'no_ktp'        => 'required|digits:16',
            'no_hp'         => 'required|regex:/^08\d{8,11}$/',
            'id_kamar'      => 'required',
            'tanggal_masuk' => 'required|date',
            'foto_ktp'      => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        DB::beginTransaction();

        try {

            $kamar = Kamar::where('id_kamar', $request->id_kamar)
                        ->lockForUpdate()
                        ->firstOrFail();

            // 🚫 Jika kapasitas sudah 0 berarti penuh
            if ($kamar->kapasitas <= 0) {
                DB::rollBack();
                return back()->with('error', 'Kamar sudah penuh');
            }

            $foto = $request->file('foto_ktp')->store('ktp', 'public');

            Penghuni::create([
                'nama_penghuni' => $request->nama_penghuni,
                'no_ktp'        => $request->no_ktp,
                'no_hp'         => $request->no_hp,
                'id_kamar'      => $request->id_kamar,
                'tanggal_masuk' => $request->tanggal_masuk,
                'file_ktp'      => $foto
            ]);

            // ✅ Kurangi kapasitas
            $kamar->kapasitas = $kamar->kapasitas - 1;

            // ✅ Jika sudah habis → Terisi
            if ($kamar->kapasitas == 0) {
                $kamar->status = 'Terisi';
            } else {
                $kamar->status = 'Kosong';
            }

            $kamar->save();

            DB::commit();

            return redirect()->route('penghuni.index')
                            ->with('success', 'Data penghuni berhasil ditambahkan');

        } catch (\Exception $e) {

            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function edit($id)
    {
        $penghuni = Penghuni::findOrFail($id);

        // Ambil kamar yang masih tersedia (Kosong atau masih ada kapasitas)
        $kamar = Kamar::where('status', 'Kosong')->get();

        return view('penghuni.edit', compact('penghuni', 'kamar'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_penghuni' => 'required',
            'no_hp'         => 'required',
            'tanggal_masuk' => 'required|date',
            'id_kamar'      => 'required',
            'foto_ktp'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        DB::beginTransaction();

        try {

            $penghuni = Penghuni::findOrFail($id);

            $kamarLama = Kamar::where('id_kamar', $penghuni->id_kamar)
                            ->lockForUpdate()
                            ->first();

            $kamarBaru = Kamar::where('id_kamar', $request->id_kamar)
                            ->lockForUpdate()
                            ->first();

            // ===============================
            // Jika pindah kamar
            // ===============================
            if ($penghuni->id_kamar != $request->id_kamar) {

                // 🚫 Cegah jika kamar baru penuh
                if ($kamarBaru->kapasitas <= 0) {
                    DB::rollBack();
                    return back()->with('error', 'Kamar tujuan sudah penuh');
                }

                // 🔄 Kembalikan kapasitas kamar lama
                $kamarLama->kapasitas += 1;
                $kamarLama->status = ($kamarLama->kapasitas == 0) ? 'Terisi' : 'Kosong';
                $kamarLama->save();

                // 🔄 Kurangi kapasitas kamar baru
                $kamarBaru->kapasitas -= 1;
                $kamarBaru->status = ($kamarBaru->kapasitas == 0) ? 'Terisi' : 'Kosong';
                $kamarBaru->save();

                $penghuni->id_kamar = $request->id_kamar;
            }

            // ===============================
            // Update Foto (jika ada)
            // ===============================
            if ($request->hasFile('foto_ktp')) {

                if ($penghuni->file_ktp &&
                    Storage::disk('public')->exists($penghuni->file_ktp)) {

                    Storage::disk('public')->delete($penghuni->file_ktp);
                }

                $path = $request->file('foto_ktp')->store('ktp', 'public');
                $penghuni->file_ktp = $path;
            }

            // ===============================
            // Update Data Lain
            // ===============================
            $penghuni->nama_penghuni = $request->nama_penghuni;
            $penghuni->no_hp         = $request->no_hp;
            $penghuni->tanggal_masuk = $request->tanggal_masuk;

            $penghuni->save();

            DB::commit();

            return redirect()->route('penghuni.index')
                            ->with('success', 'Data penghuni berhasil diupdate');

        } catch (\Exception $e) {

            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {

            $penghuni = Penghuni::findOrFail($id);

            $kamar = Kamar::where('id_kamar', $penghuni->id_kamar)
                        ->lockForUpdate()
                        ->first();

            // 🔄 Kembalikan kapasitas kamar
            $kamar->kapasitas += 1;

            // Update status
            if ($kamar->kapasitas > 0) {
                $kamar->status = 'Kosong';
            }

            $kamar->save();

            // Hapus file KTP jika ada
            if ($penghuni->file_ktp &&
                Storage::disk('public')->exists($penghuni->file_ktp)) {

                Storage::disk('public')->delete($penghuni->file_ktp);
            }

            $penghuni->delete();

            DB::commit();

            return redirect()->route('penghuni.index')
                            ->with('success', 'Data penghuni berhasil dihapus');

        } catch (\Exception $e) {

            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }
}
