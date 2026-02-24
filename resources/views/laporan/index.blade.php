@extends('layouts.app')

@section('title','Rekapitulasi Laporan')
{{-- @section('page-title','Rekapitulasi Laporan') --}}
@section('content')

<div class="container">

    <h3 class="mb-4">Rekapitulasi Laporan Kos</h3>

    {{-- ============================= --}}
    {{-- FORM FILTER --}}
    {{-- ============================= --}}
    <form method="GET" action="{{ route('laporan.index') }}" class="card p-3 mb-4">
        <div class="row">

            <div class="col-md-3">
                <label>Tanggal Awal</label>
                <input type="date" name="tanggal_awal" class="form-control"
                       value="{{ $tanggal_awal }}">
            </div>

            <div class="col-md-3">
                <label>Tanggal Akhir</label>
                <input type="date" name="tanggal_akhir" class="form-control"
                       value="{{ $tanggal_akhir }}">
            </div>

            <div class="col-md-2">
                <label>Kategori Kos</label>
                <select name="kategori" class="form-control">
                    <option value="">-- Semua --</option>
                    <option value="Bulanan" {{ $kategori == 'Bulanan' ? 'selected' : '' }}>
                        Bulanan
                    </option>
                    <option value="Tahunan" {{ $kategori == 'Tahunan' ? 'selected' : '' }}>
                        Tahunan
                    </option>
                </select>
            </div>

            <div class="col-md-2">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="">-- Semua --</option>
                    <option value="lunas" {{ $statusFilter == 'lunas' ? 'selected' : '' }}>
                        Lunas
                    </option>
                    <option value="belum_jatuh_tempo" {{ $statusFilter == 'belum_jatuh_tempo' ? 'selected' : '' }}>
                        Belum Jatuh Tempo
                    </option>
                    <option value="mendekati" {{ $statusFilter == 'mendekati' ? 'selected' : '' }}>
                        Mendekati Jatuh Tempo
                    </option>
                    <option value="jatuh_tempo" {{ $statusFilter == 'jatuh_tempo' ? 'selected' : '' }}>
                        Jatuh Tempo Hari Ini
                    </option>
                    <option value="telat" {{ $statusFilter == 'telat' ? 'selected' : '' }}>
                        Telat Bayar
                    </option>
                </select>
            </div>

            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    Filter
                </button>
            </div>

        </div>
    </form>


    {{-- ============================= --}}
    {{-- SUMMARY --}}
    {{-- ============================= --}}
    <div class="row mb-4">

        <div class="col-md-6">
            <div class="card p-3 text-center bg-light">
                <h6>Total Penghuni</h6>
                <h4>{{ $total_penghuni }}</h4>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card p-3 text-center bg-light">
                <h6>Total Pemasukan</h6>
                <h4>Rp {{ number_format($total_pemasukan,0,',','.') }}</h4>
            </div>
        </div>

    </div>

    {{-- ============================= --}}
    {{-- TOMBOL EXPORT --}}
    {{-- ============================= --}}
    <div class="mb-3">

        <!-- PDF -->
        <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalExportPdf">
            <i class="bi bi-file-earmark-pdf"></i> Export PDF
        </button>

        <!-- EXCEL -->
        {{-- <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalExportExcel">
            <i class="bi bi-file-earmark-excel"></i> Export Excel
        </button> --}}

    </div>

    <div class="modal fade" id="modalExportPdf" tabindex="-1">
        <div class="modal-dialog">
          <div class="modal-content">
            <form method="GET" action="{{ route('laporan.export.pdf') }}">
              <div class="modal-header">
                <h5 class="modal-title">Pilih Kategori Kos (PDF)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>

              <div class="modal-body">
                <select name="kategori" class="form-control" required>
                  <option value="">-- Pilih Kategori --</option>
                  <option value="Bulanan">Bulanan</option>
                  <option value="Tahunan">Tahunan</option>
                </select>
              </div>

              <div class="modal-footer">
                <button type="submit"
                        id="btnDownloadPdf"
                        class="btn btn-danger">
                    <span class="btn-text">
                        <i class="bi bi-file-earmark-pdf"></i> Download PDF
                    </span>
                    <span class="btn-loading d-none">
                        <span class="spinner-border spinner-border-sm"></span>
                        Processing...
                    </span>
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>


      {{-- <div class="modal fade" id="modalExportExcel" tabindex="-1">
        <div class="modal-dialog">
          <div class="modal-content">
            <form method="GET" action="{{ route('laporan.export.excel') }}">
              <div class="modal-header">
                <h5 class="modal-title">Pilih Kategori Kos (Excel)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>

              <div class="modal-body">
                <select name="kategori" class="form-control" required>
                  <option value="">-- Pilih Kategori --</option>
                  <option value="Bulanan">Bulanan</option>
                  <option value="Tahunan">Tahunan</option>
                </select>
              </div>

              <div class="modal-footer">
                <button type="submit" class="btn btn-success">Download Excel</button>
              </div>
            </form>
          </div>
        </div>
      </div> --}}
    {{-- ============================= --}}
    {{-- TABEL LAPORAN --}}
    {{-- ============================= --}}
    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Nama Penghuni</th>
                        <th>Kamar</th>
                        <th>Kategori</th>
                        <th>Tarif</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>

                @forelse($penghuni as $index => $item)

                    @php
                        $status = $item->status_pembayaran;
                    @endphp

                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->nama_penghuni }}</td>
                        <td>{{ $item->kamar->nomor_kamar ?? '-' }}</td>
                        <td>{{ $item->kategori_kos }}</td>
                        <td>
                            Rp {{ number_format($item->kamar->tarif ?? 0,0,',','.') }}
                        </td>
                        <td>
                            <span class="badge bg-{{ $status['class'] }}">
                                {{ $status['label'] }}
                            </span>
                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="6" class="text-center">
                            Data tidak ditemukan
                        </td>
                    </tr>
                @endforelse

                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
    function startLoading(form) {
        let btn = document.getElementById('btnDownloadPdf');

        btn.disabled = true;
        btn.querySelector('.btn-text').classList.add('d-none');
        btn.querySelector('.btn-loading').classList.remove('d-none');
    }

    // Deteksi selesai download dari iframe
    document.querySelector('iframe[name="downloadFrame"]').addEventListener('load', function() {
        let btn = document.getElementById('btnDownloadPdf');

        btn.disabled = false;
        btn.querySelector('.btn-text').classList.remove('d-none');
        btn.querySelector('.btn-loading').classList.add('d-none');
    });
</script>
@endpush
