<?php

namespace App\Http\Controllers;

use App\Models\Kamar;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class KamarController extends Controller
{
    public function index()
    {
        $data = Kamar::orderBy('id_kamar', 'desc')->get();
        return view('kamar.index', compact('data'));
    }

    public function create()
    {
        return view('kamar.add');
    }

    public function store(Request $request)
    {
        try {

            $request->validate([
                'nomor_kamar' => 'required|unique:kamar,nomor_kamar',
                'kapasitas'   => 'required|numeric',
                'tarif'       => 'required|numeric',
                'status'      => 'required'
            ], [
                'nomor_kamar.required' => 'Nomor kamar wajib diisi.',
                'nomor_kamar.unique'   => 'Nomor kamar sudah digunakan.',
                'kapasitas.required'   => 'Kapasitas wajib diisi.',
                'tarif.required'       => 'Tarif wajib diisi.',
                'status.required'      => 'Status wajib dipilih.'
            ]);

            Kamar::create($request->all());

            return redirect()->route('kamar.index')
                ->with('success', 'Data kamar berhasil ditambahkan');

        } catch (ValidationException $e) {

            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();

        } catch (\Exception $e) {

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan sistem.')
                ->withInput();
        }
    }

    public function edit($id)
    {
        $kamar = Kamar::findOrFail($id);
        return view('kamar.edit', compact('kamar'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            // 'nomor_kamar' => 'required',
            'kapasitas' => 'required|numeric',
            'tarif' => 'required|numeric',
            'status' => 'required'
        ]);

        $kamar = Kamar::findOrFail($id);
        $tarif = str_replace('.', '', $request->tarif);

        $kamar->update([
            'kapasitas' => $request->kapasitas,
            'tarif'     => $tarif,
            'status'    => $request->status,
        ]);

        return redirect()->route('kamar.index')
            ->with('success', 'Data kamar berhasil diperbarui');
    }

    public function destroy($id)
    {
        try {

            $kamar = Kamar::findOrFail($id);
            $kamar->delete();

            return redirect()->route('kamar.index')
                ->with('success', 'Data kamar berhasil dihapus');

        } catch (\Exception $e) {

            return redirect()->back()
                ->with('error', 'Gagal menghapus data');
        }
    }

}
