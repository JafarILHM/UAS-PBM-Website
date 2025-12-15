@extends('layouts.app')

@section('content')
<h1 class="h3 mb-3">Riwayat Barang Keluar</h1>

<div class="card">
    <div class="card-header">
        <a href="{{ route('outgoing.create') }}" class="btn btn-danger">
            <i data-feather="minus-circle"></i> Catat Barang Keluar
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Barang</th>
                        <th>Jml Keluar</th>
                        <th>Tujuan / Catatan</th>
                        <th>Petugas</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($outgoingItems as $out)
                    <tr>
                        <td>{{ $out->created_at->format('d M Y H:i') }}</td>
                        <td>
                            <strong>{{ $out->item->name ?? '-' }}</strong><br>
                            <small class="text-muted">{{ $out->item->sku ?? '' }}</small>
                        </td>
                        <td>
                            <span class="badge bg-danger">- {{ $out->quantity }} {{ $out->item->unit->symbol ?? '' }}</span>
                        </td>
                        <td>{{ $out->notes ?? '-' }}</td>
                        <td>{{ $out->user->name ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
