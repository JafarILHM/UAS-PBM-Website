@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<h1 class="h3 mb-3"><strong>Analisis</strong> Gudang</h1>

<div class="row">
    <div class="col-xl-6 col-xxl-5 d-flex">
        <div class="w-100">
            <div class="row">
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col mt-0">
                                    <h5 class="card-title">Jenis Barang</h5>
                                </div>
                                <div class="col-auto">
                                    <div class="stat text-primary">
                                        <i class="align-middle" data-feather="package"></i>
                                    </div>
                                </div>
                            </div>
                            <h1 class="mt-1 mb-3">{{ number_format($totalItems) }}</h1>
                            <div class="mb-0">
                                <span class="text-muted">Item terdaftar</span>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col mt-0">
                                    <h5 class="card-title">Total Stok Fisik</h5>
                                </div>
                                <div class="col-auto">
                                    <div class="stat text-primary">
                                        <i class="align-middle" data-feather="layers"></i>
                                    </div>
                                </div>
                            </div>
                            <h1 class="mt-1 mb-3">{{ number_format($totalStock) }}</h1>
                            <div class="mb-0">
                                <span class="text-muted">Unit barang di gudang</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col mt-0">
                                    <h5 class="card-title">Masuk (Bulan Ini)</h5>
                                </div>
                                <div class="col-auto">
                                    <div class="stat text-success">
                                        <i class="align-middle" data-feather="arrow-down-circle"></i>
                                    </div>
                                </div>
                            </div>
                            <h1 class="mt-1 mb-3">{{ number_format($incomingThisMonth) }}</h1>
                            <div class="mb-0">
                                <span class="text-muted">Unit baru diterima</span>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col mt-0">
                                    <h5 class="card-title">Keluar (Bulan Ini)</h5>
                                </div>
                                <div class="col-auto">
                                    <div class="stat text-danger">
                                        <i class="align-middle" data-feather="arrow-up-circle"></i>
                                    </div>
                                </div>
                            </div>
                            <h1 class="mt-1 mb-3">{{ number_format($outgoingThisMonth) }}</h1>
                            <div class="mb-0">
                                <span class="text-muted">Unit dikirim keluar</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-6 col-xxl-7">
        <div class="card flex-fill w-100">
            <div class="card-header">
                <h5 class="card-title mb-0">Pergerakan Stok (12 Bulan Terakhir)</h5>
            </div>
            <div class="card-body py-3">
                <div class="chart chart-sm">
                    <canvas id="chartjs-dashboard-line"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 col-lg-4 col-xxl-3 d-flex">
        <div class="card flex-fill w-100">
            <div class="card-header">
                <h5 class="card-title mb-0 text-danger">Stok Menipis (Alert)</h5>
            </div>
            <div class="card-body d-flex w-100">
                <ul class="list-group list-group-flush w-100">
                    @forelse($lowStockItems as $item)
                        <li class="list-group-item px-0 pb-3 pt-0">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <strong>{{ $item->name }}</strong><br>
                                    <small class="text-muted">{{ $item->sku }}</small>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-danger">{{ $item->stock }} {{ $item->unit }}</span>
                                    <div class="text-muted small">Min: {{ $item->stock_minimum }}</div>
                                </div>
                            </div>
                        </li>
                    @empty
                        <li class="list-group-item text-center text-muted">Aman! Tidak ada stok kritis.</li>
                    @endforelse
                </ul>
            </div>
            <div class="card-footer border-top">
                <a href="{{ route('items.index') }}" class="btn btn-primary w-100">Lihat Semua Barang</a>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-8 col-xxl-9 d-flex">
        <div class="card flex-fill">
            <div class="card-header">
                <h5 class="card-title mb-0">Aktivitas Terbaru</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover my-0">
                    <thead>
                        <tr>
                            <th>Tipe</th>
                            <th>Barang</th>
                            <th class="d-none d-xl-table-cell">Tanggal</th>
                            <th class="d-none d-xl-table-cell">Jumlah</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentIncoming as $inc)
                        <tr>
                            <td><span class="badge bg-success">Masuk</span></td>
                            <td>{{ $inc->item->name ?? 'Item Dihapus' }}</td>
                            <td class="d-none d-xl-table-cell">{{ $inc->created_at->format('d/m/Y H:i') }}</td>
                            <td class="d-none d-xl-table-cell fw-bold text-success">+{{ $inc->qty }}</td>
                            <td><span class="badge bg-success">Selesai</span></td>
                        </tr>
                        @endforeach

                        @foreach($recentOutgoing as $out)
                        <tr>
                            <td><span class="badge bg-danger">Keluar</span></td>
                            <td>{{ $out->item->name ?? 'Item Dihapus' }}</td>
                            <td class="d-none d-xl-table-cell">{{ $out->created_at->format('d/m/Y H:i') }}</td>
                            <td class="d-none d-xl-table-cell fw-bold text-danger">-{{ $out->qty }}</td>
                            <td><span class="badge bg-danger">Selesai</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var ctx = document.getElementById("chartjs-dashboard-line").getContext("2d");
        var gradient = ctx.createLinearGradient(0, 0, 0, 225);
        gradient.addColorStop(0, "rgba(215, 227, 244, 1)");
        gradient.addColorStop(1, "rgba(215, 227, 244, 0)");

        // Data dari Controller (PHP to JS)
        var labels = @json($chartLabels);
        var dataIncoming = @json($chartIncoming);
        var dataOutgoing = @json($chartOutgoing);

        new Chart(document.getElementById("chartjs-dashboard-line"), {
            type: "line",
            data: {
                labels: labels,
                datasets: [{
                    label: "Barang Masuk",
                    fill: true,
                    backgroundColor: gradient, // Transparan biru
                    borderColor: window.theme.primary,
                    data: dataIncoming
                }, {
                    label: "Barang Keluar",
                    fill: true,
                    backgroundColor: "transparent",
                    borderColor: window.theme.danger, // Merah
                    borderDash: [5, 5],
                    data: dataOutgoing
                }]
            },
            options: {
                maintainAspectRatio: false,
                legend: {
                    display: true
                },
                tooltips: {
                    intersect: false
                },
                hover: {
                    intersect: true
                },
                plugins: {
                    filler: {
                        propagate: false
                    }
                },
                scales: {
                    xAxes: [{
                        reverse: false,
                        gridLines: {
                            color: "rgba(0,0,0,0.0)"
                        }
                    }],
                    yAxes: [{
                        ticks: {
                            stepSize: 10
                        },
                        display: true,
                        borderDash: [3, 3],
                        gridLines: {
                            color: "rgba(0,0,0,0.0)"
                        }
                    }]
                }
            }
        });
    });
</script>
@endsection
