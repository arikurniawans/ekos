@extends('layouts.app')

@section('content')
<div class="container">

    <h4 class="mb-3">Edit Pembayaran</h4>

    <form action="{{ route('pembayaran.update', $pembayaran->id_pembayaran) }}"
          method="POST"
          enctype="multipart/form-data">

        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Bulan</label>
            <input type="text" name="bulan"
                   value="{{ $pembayaran->bulan }}"
                   class="form-control">
        </div>

        <div class="mb-3">
            <label>Jumlah Bayar</label>
            <input type="number" name="jumlah_bayar"
                   value="{{ $pembayaran->jumlah_bayar }}"
                   class="form-control">
        </div>

        <div class="mb-3">
            <label>Tanggal Bayar</label>
            <input type="date" name="tanggal_bayar"
                   value="{{ $pembayaran->tanggal_bayar }}"
                   class="form-control">
        </div>

        <div class="mb-3">
            <label>Status</label>
            <select name="status_pembayaran" class="form-control">
                <option value="Lunas"
                    {{ $pembayaran->status_pembayaran == 'Lunas' ? 'selected' : '' }}>
                    Lunas
                </option>
                <option value="Belum Lunas"
                    {{ $pembayaran->status_pembayaran == 'Belum Lunas' ? 'selected' : '' }}>
                    Belum Lunas
                </option>
            </select>
        </div>

        <div class="mb-3">
            <label>Bukti Bayar</label>
            <input type="file" name="bukti_bayar" class="form-control">

            @if($pembayaran->bukti_bayar)
                <br>
                <img src="{{ asset('storage/'.$pembayaran->bukti_bayar) }}" width="100">
            @endif
        </div>

        <button class="btn btn-success">Update</button>
        <a href="{{ route('pembayaran.index') }}" class="btn btn-secondary">Kembali</a>

    </form>
</div>
@endsection
