@extends('layouts.app')

@section('title', 'Edit Kamar')
@section('page-title', 'Edit Kamar')

@section('content')

<div class="row">
    <div class="col-md-6">

        <div class="card card-warning card-outline">
            <div class="card-header">
                <div class="card-title">Form Edit Kamar</div>
            </div>

            <form action="{{ route('kamar.update', $kamar->id_kamar) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card-body">

                    <div class="mb-3">
                        <label class="form-label">Nomor Kamar</label>
                        <input type="text"
                               class="form-control"
                               value="{{ $kamar->nomor_kamar }}"
                               readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kapasitas</label>
                        <input type="number"
                               name="kapasitas"
                               class="form-control"
                               value="{{ $kamar->kapasitas }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tarif (Rp)</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="text"
                                   name="tarif"
                                   id="tarif"
                                   class="form-control"
                                   value="{{ number_format($kamar->tarif, 0, ',', '.') }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="Kosong" {{ $kamar->status == 'Kosong' ? 'selected' : '' }}>Kosong</option>
                            <option value="Terisi" {{ $kamar->status == 'Terisi' ? 'selected' : '' }}>Terisi</option>
                        </select>
                    </div>

                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-save"></i> Update
                    </button>

                    <a href="{{ route('kamar.index') }}"
                       class="btn btn-secondary float-end">
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

const inputTarif = document.getElementById('tarif');

if (!inputTarif) return; // hentikan jika tidak ada elemen

inputTarif.addEventListener('input', function() {

    let angka = this.value.replace(/[^,\d]/g, '');
    let split = angka.split(',');
    let sisa = split[0].length % 3;
    let rupiah = split[0].substr(0, sisa);
    let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    if (ribuan) {
        let separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }

    this.value = split[1] !== undefined
        ? rupiah + ',' + split[1]
        : rupiah;

});

});
</script>
@endpush
