@extends('layouts.app')

@section('title','Form Pembayaran')
@section('page-title','Pembayaran Kos')

@section('content')

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row">
    <div class="col-md-10">

        <div class="card card-primary card-outline">
            <div class="card-header">
                <div class="card-title">Form Pembayaran</div>
            </div>

            <form action="{{ route('pembayaran.store') }}"
                  method="POST"
                  enctype="multipart/form-data">
                @csrf

                {{-- Hidden ID Penghuni --}}
                <input type="hidden"
                       name="id_penghuni"
                       value="{{ $penghuni->id_penghuni }}">

                <div class="card-body">

                    <!-- ROW 1 -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Penghuni</label>
                            <input type="text"
                                   class="form-control"
                                   value="{{ $penghuni->nama_penghuni }}"
                                   readonly>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nomor Kamar</label>
                            <input type="text"
                                   class="form-control"
                                   value="{{ $penghuni->kamar->nomor_kamar ?? '-' }}"
                                   readonly>
                        </div>
                    </div>

                    <!-- ROW 2 -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nomor Handphone</label>
                            <input type="text"
                                   class="form-control"
                                   value="{{ $penghuni->no_hp }}"
                                   readonly>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Bayar</label>
                            <input type="date"
                                   name="tanggal_bayar"
                                   value="{{ old('tanggal_bayar', date('Y-m-d')) }}"
                                   class="form-control @error('tanggal_bayar') is-invalid @enderror">

                            @error('tanggal_bayar')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <!-- ROW 3 -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jumlah Bayar (Rp)</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>

                                {{-- Input tampilan format rupiah (tidak dikirim ke server) --}}
                                <input type="text"
                                       id="jumlah_bayar_display"
                                       class="form-control @error('jumlah_bayar') is-invalid @enderror"
                                       placeholder="0"
                                       autocomplete="off"
                                       value="{{ old('jumlah_bayar') ? number_format(old('jumlah_bayar'), 0, ',', '.') : '' }}">

                                {{-- Hidden input yang dikirim ke server berisi angka asli --}}
                                <input type="hidden"
                                       id="jumlah_bayar_hidden"
                                       name="jumlah_bayar"
                                       value="{{ old('jumlah_bayar', 0) }}">

                                @error('jumlah_bayar')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Upload Bukti Bayar</label>
                            <input type="file"
                                   name="bukti_bayar"
                                   class="form-control @error('bukti_bayar') is-invalid @enderror"
                                   accept="image/*">

                            @error('bukti_bayar')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Simpan Pembayaran
                    </button>

                    <a href="{{ route('pembayaran.index') }}"
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
document.addEventListener("DOMContentLoaded", function () {

    const display = document.getElementById("jumlah_bayar_display");
    const hidden  = document.getElementById("jumlah_bayar_hidden");

    // Format angka ke format Rupiah (titik sebagai pemisah ribuan)
    function formatRupiah(angka) {
        return angka.replace(/\D/g, "")
                    .replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    display.addEventListener("keyup", function () {
        let angka = this.value.replace(/\D/g, ""); // ambil angka saja
        this.value = formatRupiah(angka);           // tampilkan dengan format
        hidden.value = angka;                        // simpan angka asli ke hidden
    });

    // Pastikan saat form submit, hidden sudah berisi angka bersih
    display.closest("form").addEventListener("submit", function () {
        hidden.value = display.value.replace(/\D/g, "");
    });

});
</script>
@endpush
