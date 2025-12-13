@extends('layouts.app')

@section('content')
<h1 class="h3 mb-3">Data Barang</h1>

<div class="row">
    <div class="col-12">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card">
            <div class="card-header">
                <a href="{{ route('items.create') }}" class="btn btn-primary">
                    <i data-feather="plus"></i> Tambah Barang Baru
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>SKU / Barcode</th>
                                <th>Nama Barang</th>
                                <th>Kategori</th>
                                <th>Stok</th>
                                <th>Satuan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $item)
                            <tr>
                                <td>
                                    <strong>{{ $item->sku }}</strong><br>
                                    <small class="text-muted">{{ $item->barcode }}</small>
                                    <div class="mt-1">
                                        {!! DNS1D::getBarcodeHTML($item->barcode, 'C128', 1, 25) !!}
                                    </div>
                                </td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->category->name ?? '-' }}</td>
                                <td>
                                    @if($item->stock <= $item->stock_minimum)
                                        <span class="text-danger fw-bold">{{ $item->stock }}</span>
                                        <i data-feather="alert-circle" class="text-danger" width="14"></i>
                                    @else
                                        {{ $item->stock }}
                                    @endif
                                </td>
                                <td>{{ $item->unit }}</td>
                                <td>
                                    <a href="{{ route('items.edit', $item->id) }}" class="btn btn-sm btn-info">Edit</a>
                                    <form action="{{ route('items.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus barang ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
