@extends('layouts.app')

@section('content')
<h1 class="h3 mb-3">Riwayat Barang Masuk</h1>

<div class="card">
    <div class="card-header">
        <a href="{{ route('incoming.create') }}" class="btn btn-primary">
            <i data-feather="plus"></i> Input Barang Masuk
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Barang</th>
                        <th>Jml</th>
                        <th>Supplier</th>
                        <th>Batch / Exp</th>
                        <th>Petugas</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($incomingItems as $inc)
                    <tr>
                        <td>{{ $inc->created_at->format('d M Y H:i') }}</td>
                        <td>
                            <strong>{{ $inc->item->name ?? '-' }}</strong><br>
                            <small class="text-muted">{{ $inc->item->sku ?? '' }}</small>
                        </td>
                        <td>
                            <span class="badge bg-success">+ {{ $inc->quantity }} {{ $inc->item->unit->symbol ?? '' }}</span>
                        </td>
                        <td>{{ $inc->supplier->name ?? '-' }}</td>
                        <td>
                            @if($inc->batch_no)
                                <small>Batch: {{ $inc->batch_no }}</small><br>
                            @endif
                            @if($inc->expiry_date)
                                <small class="text-danger">Exp: {{ \Carbon\Carbon::parse($inc->expiry_date)->format('d M Y') }}</small>
                            @endif
                        </td>
                        <td>{{ $inc->user->name ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
