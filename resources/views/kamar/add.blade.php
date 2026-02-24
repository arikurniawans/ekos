@extends('layouts.app')

@section('title','Tambah Kamar')
@section('page-title','Tambah Kamar')

@section('content')

<div class="row">
    <div class="col-md-6">

        <div class="card card-primary card-outline mb-4">
            <div class="card-header">
                <div class="card-title">Form Input Kamar</div>
            </div>

            <form action="{{ route('kamar.store') }}" method="POST">
                @csrf

                <div class="card-body">

                    <!-- Nomor Kamar -->
                    <div class="mb-3">
                        <label class="form-label">Nomor Kamar</label>
                        <input
                            type="text"
                            name="nomor_kamar"
                            class="form-control @error('nomor_kamar') is-invalid @enderror"
                            placeholder="Contoh: A01"
                            value="{{ old('nomor_kamar') }}"
                        >
                        @error('nomor_kamar')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Kapasitas -->
                    <div class="mb-3">
                        <label class="form-label">Kapasitas</label>
                        <input
                            type="number"
                            name="kapasitas"
                            class="form-control @error('kapasitas') is-invalid @enderror"
                            placeholder="Jumlah Orang"
                            value="{{ old('kapasitas') }}"
                        >
                        @error('kapasitas')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Tarif -->
                    <div class="mb-3">
                        <label class="form-label">Tarif (Rp)</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input
                                type="text"
                                id="tarif_display"
                                class="form-control"
                                placeholder="Contoh: 750.000"
                            >
                        </div>
                        <!-- Hidden input untuk dikirim ke server -->
                            <input
                            type="hidden"
                            name="tarif"
                            id="tarif"
                            value="{{ old('tarif') }}"
                        >
                        @error('tarif')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select
                            name="status"
                            class="form-select @error('status') is-invalid @enderror"
                        >
                            <option value="">-- Pilih Status --</option>
                            <option value="Kosong" {{ old('status') == 'Kosong' ? 'selected' : '' }}>
                                Kosong
                            </option>
                            <option value="Terisi" {{ old('status') == 'Terisi' ? 'selected' : '' }}>
                                Terisi
                            </option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Simpan
                    </button>

                    <a href="{{ route('kamar.index') }}" class="btn btn-secondary float-end">
                        Kembali
                    </a>
                </div>

            </form>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {

    const tarifDisplay = document.getElementById("tarif_display");
    const tarifHidden = document.getElementById("tarif");

    // Format Rupiah
    function formatRupiah(angka) {
        return angka.replace(/\D/g, "")
                    .replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    tarifDisplay.addEventListener("keyup", function(e) {

        let angka = this.value.replace(/\D/g, "");

        this.value = formatRupiah(angka);

        // Simpan angka asli ke hidden input
        tarifHidden.value = angka;
    });

});
</script>
@endpush

