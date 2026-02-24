@extends('layouts.app')

@section('title','Tambah Penghuni')
@section('page-title','Tambah Penghuni')

@section('content')

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
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
                <div class="card-title">Form Input Penghuni</div>
            </div>

            <form action="{{ route('penghuni.store') }}"
                  method="POST"
                  enctype="multipart/form-data">
                @csrf

                <div class="card-body">

                    <!-- ROW 1 -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Penghuni</label>
                            <input type="text"
                                   name="nama_penghuni"
                                   value="{{ old('nama_penghuni') }}"
                                   class="form-control @error('nama_penghuni') is-invalid @enderror">

                            @error('nama_penghuni')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">No KTP</label>
                            <input type="text"
                                   name="no_ktp"
                                   value="{{ old('no_ktp') }}"
                                   class="form-control @error('no_ktp') is-invalid @enderror"
                                   maxlength="16">

                            @error('no_ktp')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <!-- ROW 2 -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">No HP (format: 08xxxxxxxxxx)</label>
                            <input type="text"
                                   name="no_hp"
                                   value="{{ old('no_hp') }}"
                                   class="form-control @error('no_hp') is-invalid @enderror"
                                   maxlength="13">

                            @error('no_hp')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Pilih Kamar</label>
                            <select name="id_kamar"
                                    class="form-select @error('id_kamar') is-invalid @enderror">
                                <option value="">-- Pilih Kamar --</option>
                                @foreach($kamar as $k)
                                    <option value="{{ $k->id_kamar }}"
                                        {{ old('id_kamar') == $k->id_kamar ? 'selected' : '' }}>
                                        {{ $k->nomor_kamar }}
                                    </option>
                                @endforeach
                            </select>

                            @error('id_kamar')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <!-- ROW 3 -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Masuk</label>
                            <input type="date"
                                   name="tanggal_masuk"
                                   value="{{ old('tanggal_masuk') }}"
                                   class="form-control @error('tanggal_masuk') is-invalid @enderror">

                            @error('tanggal_masuk')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Upload KTP</label>
                            <input type="file"
                                   name="foto_ktp"
                                   class="form-control @error('foto_ktp') is-invalid @enderror">

                            @error('foto_ktp')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Simpan
                    </button>

                    <a href="{{ route('penghuni.index') }}"
                       class="btn btn-secondary float-end">
                        Kembali
                    </a>
                </div>

            </form>

        </div>

    </div>
</div>

@endsection


@section('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {

    const ktpInput = document.querySelector("input[name='no_ktp']");
    const hpInput  = document.querySelector("input[name='no_hp']");

    if(ktpInput){
        ktpInput.addEventListener("input", function () {
            this.value = this.value.replace(/\D/g, '').slice(0, 16);
        });
    }

    if(hpInput){
        hpInput.addEventListener("input", function () {
            this.value = this.value.replace(/\D/g, '').slice(0, 13);

            if (this.value.length >= 2 && !this.value.startsWith('08')) {
                this.value = '08';
            }
        });
    }

});
</script>
@endsection
