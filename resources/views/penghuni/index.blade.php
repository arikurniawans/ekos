@extends('layouts.app')

@section('title','Data Penghuni')
@section('page-title','Manajemen Penghuni')

@section('content')

<a href="{{ route('penghuni.create') }}" class="btn btn-primary mb-3">
    <i class="bi bi-plus"></i> Tambah Penghuni
</a>

@if(session('success'))
<div id="success-alert" class="alert alert-success alert-dismissible fade show">
    {{ session('success') }}
</div>
@endif


<div class="card">
    <div class="card-body table-responsive">
        <table class="table table-bordered table-striped" id="table-penghuni">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>No KTP</th>
                    <th>No HP</th>
                    <th>Kamar</th>
                    <th>Kategori Kos</th>
                    <th>Tanggal Masuk</th>
                    <th>Periode Jatuh Tempo</th>
                    <th width="150" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($penghuni as $key => $item)

                @php
                    $tanggalMasuk = \Carbon\Carbon::parse($item->tanggal_masuk);

                    if($item->kategori_kos == 'Bulanan'){
                        $jatuhTempo = $tanggalMasuk->copy()->addDays(30);
                    }else{
                        $jatuhTempo = $tanggalMasuk->copy()->addDays(360);
                    }
                @endphp

                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $item->nama_penghuni }}</td>
                    <td>{{ $item->no_ktp }}</td>
                    <td>{{ $item->no_hp }}</td>
                    <td>{{ $item->kamar->nomor_kamar ?? '-' }}</td>

                    <!-- KATEGORI KOS -->
                    <td class="text-center">
                        @if($item->kategori_kos == 'Bulanan')
                            <span class="badge bg-primary">Bulanan</span>
                        @else
                            <span class="badge bg-success">Tahunan</span>
                        @endif
                    </td>

                    <td>{{ \Carbon\Carbon::parse($item->tanggal_masuk)->format('d-m-Y') }}</td>

                    <!-- JATUH TEMPO -->
                    <td>
                        {{ $jatuhTempo->format('d-m-Y') }}
                    </td>

                    <td class="text-center">
                        <a href="{{ route('penghuni.edit',$item->id_penghuni) }}"
                           class="btn btn-warning btn-sm px-3">
                            <i class="bi bi-pencil-square"></i>
                        </a>

                        <form action="{{ route('penghuni.destroy', $item->id_penghuni) }}"
                              method="POST"
                              class="form-hapus d-inline">
                            @csrf
                            @method('DELETE')

                            <button type="button"
                                    class="btn btn-danger btn-sm px-3 btn-hapus">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
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
    $('#table-penghuni').DataTable({
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

document.addEventListener("DOMContentLoaded", function() {

let alertBox = document.getElementById('success-alert');

if(alertBox){
    setTimeout(function(){
        alertBox.classList.remove('show');
        alertBox.classList.add('fade');
        alertBox.style.display = 'none';
    }, 5000);
}});

document.querySelectorAll('.btn-hapus').forEach(function(button) {

button.addEventListener('click', function(e) {

    let form = this.closest('.form-hapus');

    Swal.fire({
        title: 'Yakin hapus data?',
        text: "Data yang dihapus tidak dapat dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {

        if (result.isConfirmed) {
            form.submit();
        }

    });

});

});

</script>
@endpush
