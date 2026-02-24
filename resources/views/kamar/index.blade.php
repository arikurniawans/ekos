@extends('layouts.app')

@section('title','Data Kamar')
@section('page-title','Manajemen Kamar')

@section('content')

<a href="{{ route('kamar.create') }}" class="btn btn-primary mb-3">
    <i class="bi bi-plus"></i> Tambah Kamar
</a>

@if(session('success'))
<div id="success-alert" class="alert alert-success alert-dismissible fade show">
    {{ session('success') }}
</div>
@endif

<div class="card">
    <div class="card-body table-responsive">
        <table id="table-kamar" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nomor</th>
                    <th>Kapasitas</th>
                    <th>Tarif</th>
                    <th class="text-center" width="120">Status</th>
                    <th class="text-center" width="150">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $row)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $row->nomor_kamar }}</td>
                    <td>{{ $row->kapasitas }} Orang</td>
                    <td>Rp {{ number_format($row->tarif,0,',','.') }}</td>
                    <td>
                        <span class="badge bg-{{ $row->status == 'Kosong' ? 'success' : 'danger' }}">
                            {{ $row->status }}
                        </span>
                    </td>
                    <td class="text-center align-middle">
                        <div class="d-flex justify-content-center gap-2">

                            <a href="{{ route('kamar.edit', $row->id_kamar) }}"
                               class="btn btn-warning btn-sm px-3">
                                <i class="bi bi-pencil-square"></i>
                            </a>

                            <form action="{{ route('kamar.destroy', $row->id_kamar) }}"
                                  method="POST"
                                  class="form-hapus d-inline">
                                @csrf
                                @method('DELETE')

                                <button type="button"
                                        class="btn btn-danger btn-sm px-3 btn-hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>

                        </div>
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
    $('#table-kamar').DataTable({
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
}

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
});

</script>
@endpush
