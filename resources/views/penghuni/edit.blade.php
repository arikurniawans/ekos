@extends('layouts.app')

@section('title','Edit Penghuni')
@section('page-title','Edit Penghuni')

@section('content')

<div class="row">
    <div class="col-md-10">

        <div class="card card-warning card-outline">
            <div class="card-header">
                <div class="card-title">Form Edit Penghuni</div>
            </div>

            <form action="{{ route('penghuni.update', $penghuni->id_penghuni) }}"
                  method="POST"
                  enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="card-body">

                    <!-- ROW 1 -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Penghuni</label>
                            <input type="text"
                                   name="nama_penghuni"
                                   value="{{ old('nama_penghuni', $penghuni->nama_penghuni) }}"
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
                                   value="{{ $penghuni->no_ktp }}"
                                   class="form-control"
                                   readonly>
                        </div>
                    </div>

                    <!-- ROW 2 -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">No HP (format: 08xxxxxxxxxx)</label>
                            <input type="text"
                                   name="no_hp"
                                   value="{{ old('no_hp', $penghuni->no_hp) }}"
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
                                @foreach($kamar as $k)
                                    <option value="{{ $k->id_kamar }}"
                                        {{ $penghuni->id_kamar == $k->id_kamar ? 'selected' : '' }}>
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
                                   value="{{ old('tanggal_masuk', $penghuni->tanggal_masuk) }}"
                                   class="form-control @error('tanggal_masuk') is-invalid @enderror">

                            @error('tanggal_masuk')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Upload KTP (Opsional)</label>
                            <input type="file"
                                   name="foto_ktp"
                                   class="form-control @error('foto_ktp') is-invalid @enderror">

                            @error('foto_ktp')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                            <!-- Preview Foto Lama -->
                            <div class="mt-3">
                                <small class="text-muted">KTP Saat Ini:</small><br>
                                <img src="{{ asset('storage/'.$penghuni->file_ktp) }}"
                                     class="img-thumbnail mt-2"
                                     style="max-width:200px;">
                            </div>
                        </div>
                    </div>

                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-save"></i> Update
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

    const hpInput  = document.querySelector("input[name='no_hp']");

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
