@extends('layouts.app')

@section('content')
<h1 class="h3 mb-3">Data Barang</h1>

<div class="row">
    <div class="col-12">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <a href="{{ route('items.create') }}" class="btn btn-primary">
                    <i data-feather="plus"></i> Tambah Barang Baru
                </a>

                <a href="{{ route('items.export') }}" class="btn btn-success">
                    <i data-feather="download"></i> Export Excel
                </a>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead>
                            <tr>
                                <th>SKU / Barcode</th> <th>Nama Barang</th>
                                <th>Kategori</th>
                                <th>Stok</th>
                                <th>Satuan</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $item)
                            <tr>
                                <td>
                                    <strong>{{ $item->sku }}</strong>
                                    <div class="mt-1">
                                        {!! DNS1D::getBarcodeHTML($item->sku, 'C128', 1, 25) !!}
                                    </div>
                                </td>
                                <td>{{ $item->name }}</td>
                                <td>
                                    <span class="badge bg-secondary">{{ $item->category->name ?? '-' }}</span>
                                </td>
                                <td>
                                    <span class="{{ $item->stock <= $item->stock_minimum ? 'text-danger fw-bold' : 'text-success fw-bold' }}">
                                        {{ $item->stock }}
                                    </span>
                                </td>
                                <td>{{ $item->unit->symbol ?? '-' }}</td>

                                <td class="text-end">
                                    <a href="{{ route('items.edit', $item->id) }}" class="btn btn-sm btn-info me-1">
                                        <i data-feather="edit"></i> Edit
                                    </a>

                                    <form action="{{ route('items.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus barang ini? Stok dan riwayat transaksi juga akan terhapus.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i data-feather="trash-2"></i> Hapus
                                        </button>
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
