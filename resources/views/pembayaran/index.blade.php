@extends('layouts.app')

@section('title','Data Pembayaran')
@section('page-title','Manajemen Pembayaran')

@section('content')

@if(session('success'))
<div id="success-alert" class="alert alert-success alert-dismissible fade show">
    {{ session('success') }}
</div>
@endif

<div class="card">
    <div class="card-body table-responsive">
        <table class="table table-bordered table-striped" id="table-pembayaran">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Penghuni</th>
                    <th>Tanggal/Tahun Masuk</th>
                    <th>Kategori Kos</th>
                    <th>Periode Bayar</th>
                    <th>Status Pembayaran</th>
                    <th width="250" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($penghuni as $key => $item)
                @php
                    $status      = $item->status_pembayaran;
                    $statusLabel = is_array($status) ? ($status['label'] ?? '') : '';
                    $sudahLunas  = in_array($statusLabel, [
                        'Lunas / Belum Jatuh Tempo',
                        'Belum Jatuh Tempo',
                    ]);
                @endphp
                <tr>
                    <td>{{ $key+1 }}</td>

                    <td>{{ $item->nama_penghuni }}</td>
                    <td>{{ $item->tanggal_masuk }}</td>
                    {{-- ================= KATEGORI ================= --}}
                    <td>
                        <span class="badge {{ $item->kategori_kos == 'Bulanan' ? 'bg-dark' : 'bg-info' }}">
                            {{ $item->kategori_kos }}
                        </span>
                    </td>
                    {{-- ================= PERIODE ================= --}}
                    <td>
                        @php
                            $tanggalMasuk = \Carbon\Carbon::parse($item->tanggal_masuk);
                        @endphp

                        @if($item->kategori_kos == 'Bulanan')
                            {{-- Tampilkan tanggal saja --}}
                            Per tanggal {{ $tanggalMasuk->day }}
                        @else
                            {{-- Tampilkan bulan saja --}}
                            Per bulan {{ $tanggalMasuk->translatedFormat('F') }}
                        @endif
                    </td>
                    {{-- ================= STATUS ================= --}}
                    <td>
                        @php
                            $status = $item->status_pembayaran;
                        @endphp

                        @if($status && is_array($status))
                            <span class="badge bg-{{ $status['class'] }}">
                                {{ $status['label'] }}
                            </span>
                        @else
                            <span class="badge bg-secondary">
                                Status tidak tersedia
                            </span>
                        @endif
                    </td>
                    {{-- ================= AKSI ================= --}}
                    <td class="text-center">

                        {{-- Tombol Bayar: disabled jika Lunas/Belum Jatuh Tempo --}}
                        @if($sudahLunas)
                            <button class="btn btn-primary btn-sm px-2" disabled title="Pembayaran masih aktif">
                                <i class="bi bi-cash"></i> Bayar
                            </button>
                        @else
                            <a href="{{ route('pembayaran.create', ['penghuni' => $item->id_penghuni]) }}"
                               class="btn btn-primary btn-sm px-2">
                                <i class="bi bi-cash"></i> Bayar
                            </a>
                        @endif

                        {{-- <a href="{{ route('pembayaran.detail',['penghuni'=>$item->id_penghuni]) }}"
                           class="btn btn-info btn-sm px-2">
                            <i class="bi bi-eye"></i> Detail
                        </a> --}}

                        {{-- <form action="{{ route('pembayaran.batal',['penghuni'=>$item->id_penghuni]) }}"
                              method="POST"
                              class="form-batal d-inline">
                            @csrf
                            @method('PUT')

                            <button type="button"
                                    class="btn btn-danger btn-sm px-2 btn-batal">
                                <i class="bi bi-x-circle"></i> Batal
                            </button>
                        </form> --}}

                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection


@push('scripts')
<script>

$(document).ready(function() {

    $('#table-pembayaran').DataTable({
        responsive: true,
        autoWidth: false,
        pageLength: 5,
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            paginate: {
                previous: "Sebelumnya",
                next: "Berikutnya"
            }
        }
    });

});

// Auto hide alert
document.addEventListener("DOMContentLoaded", function() {

    let alertBox = document.getElementById('success-alert');

    if(alertBox){
        setTimeout(function(){
            alertBox.classList.remove('show');
            alertBox.classList.add('fade');
            alertBox.style.display = 'none';
        }, 5000);
    }

});

// SweetAlert Batal
document.querySelectorAll('.btn-batal').forEach(function(button) {

    button.addEventListener('click', function() {

        let form = this.closest('.form-batal');

        Swal.fire({
            title: 'Batalkan pembayaran?',
            text: "Status akan dikembalikan ke posisi awal.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya',
            cancelButtonText: 'Tidak'
        }).then((result) => {

            if (result.isConfirmed) {
                form.submit();
            }

        });

    });

});

</script>
@endpush
