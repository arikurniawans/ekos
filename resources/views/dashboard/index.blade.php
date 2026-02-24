@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

<div class="row">

    <!-- Total Kamar -->
    <div class="col-lg-3 col-6">
        <div class="small-box text-bg-primary">
            <div class="inner">
                <h3>{{ $totalKamar }}</h3>
                <p>Total Kamar</p>
            </div>
            <div class="small-box-icon">
                <i class="bi bi-door-open"></i>
            </div>
        </div>
    </div>

    <!-- Kamar Kosong -->
    <div class="col-lg-3 col-6">
        <div class="small-box text-bg-success">
            <div class="inner">
                <h3>{{ $kamarKosong }}</h3>
                <p>Kamar Kosong</p>
            </div>
            <div class="small-box-icon">
                <i class="bi bi-house"></i>
            </div>
        </div>
    </div>

    <!-- Kamar Terisi -->
    <div class="col-lg-3 col-6">
        <div class="small-box text-bg-warning">
            <div class="inner">
                <h3>{{ $kamarTerisi }}</h3>
                <p>Kamar Terisi</p>
            </div>
            <div class="small-box-icon">
                <i class="bi bi-people-fill"></i>
            </div>
        </div>
    </div>

    <!-- Total Penghuni -->
    <div class="col-lg-3 col-6">
        <div class="small-box text-bg-danger">
            <div class="inner">
                <h3>{{ $totalPenghuni }}</h3>
                <p>Total Penghuni</p>
            </div>
            <div class="small-box-icon">
                <i class="bi bi-person"></i>
            </div>
        </div>
    </div>

</div>

<div class="row mt-3 align-items-stretch">

    {{-- KOLOM KIRI --}}
    <div class="col-md-6 d-flex flex-column">

        {{-- CARD PEMASUKAN --}}
        <div class="card shadow-sm">
            <div class="card-header">
                <strong>Pemasukan Bulan Ini</strong>
            </div>
            <div class="card-body">
                <h3 class="mb-0">
                    Rp {{ number_format($pemasukanBulanIni,0,',','.') }}
                </h3>
            </div>
        </div>

        {{-- BAR CHART --}}
        <div class="card shadow-sm mt-3 flex-fill">
            <div class="card-header">
                <strong>
                    Rekap Total Pendapatan Tahun {{ $tahun }} Berdasarkan Kategori Kos
                </strong>
            </div>
            <div class="card-body">
                <div style="position: relative; width: 100%; height: 250px;">
                    <canvas id="pendapatanChart"></canvas>
                </div>
            </div>
        </div>

    </div>

    {{-- KOLOM KANAN --}}
    <div class="col-md-6 d-flex">

        <div class="card shadow-sm w-100 h-100">
            <div class="card-header">
                <strong>Rekap Total Penghuni Berdasarkan Kategori Kos</strong>
            </div>

            <div class="card-body d-flex justify-content-center align-items-center">
                <div style="width: 100%; max-width: 320px; height: 280px;">
                    <canvas id="kategoriChart"></canvas>
                </div>
            </div>
        </div>

    </div>

</div>

@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {

        const ctx = document.getElementById('kategoriChart').getContext('2d');

        const dataBulanan = {{ $jumlahBulanan ?? 0 }};
        const dataTahunan = {{ $jumlahTahunan ?? 0 }};

        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Bulanan', 'Tahunan'],
                datasets: [{
                    data: [dataBulanan, dataTahunan],
                    backgroundColor: ['#007bff', '#28a745'],
                    borderColor: '#ffffff',
                    borderWidth: 2,
                    radius: '90%'   // ukuran lingkaran proporsional
                }]
            },
            plugins: [ChartDataLabels],
            options: {
                responsive: true,
                maintainAspectRatio: false,   // mengikuti tinggi div
                layout: {
                    padding: 30
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom',
                        labels: {
                            boxWidth: 20,
                            padding: 15,
                            font: {
                                size: 14,
                                weight: 'bold'
                            }
                        }
                    },
                    datalabels: {
                        color: '#ffffff',
                        font: {
                            weight: 'bold',
                            size: 16
                        },
                        formatter: function(value) {
                            return value + " orang";
                        }
                    }
                }
            }
        });

        // ================= BAR CHART =================
        const jumlahLunas = [
            {{ $jumlahLunasBulanan }},
            {{ $jumlahLunasTahunan }}
        ];

        new Chart(document.getElementById('pendapatanChart'), {
            type: 'bar',
            data: {
                labels: ['Bulanan', 'Tahunan'],
                datasets: [{
                    data: [
                        {{ $totalPendapatanBulanan }},
                        {{ $totalPendapatanTahunan }}
                    ],
                    backgroundColor: ['#0d6efd', '#198754'],
                    borderRadius: 8,
                    barThickness: 55
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const value = context.raw.toLocaleString('id-ID');
                                const orang = jumlahLunas[context.dataIndex];

                                return [
                                    'Rp ' + value,
                                    'Jumlah Lunas: ' + orang + ' Orang'
                                ];
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });

    });
</script>
@endpush
