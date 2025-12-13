@extends('layouts.app')

@section('content')
<h1 class="h3 mb-3">Edit Barang</h1>

<div class="row">
    <div class="col-12 col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('items.update', $item->id) }}" method="POST">
                    @csrf
                    @method('PUT') <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">SKU (Kode Unik)</label>
                            <input type="text" name="sku" class="form-control" value="{{ old('sku', $item->sku) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Barcode (Opsional)</label>
                            <input type="text" name="barcode" class="form-control" value="{{ old('barcode', $item->barcode) }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama Barang</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $item->name) }}" required>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Kategori</label>
                            <select name="category_id" class="form-select" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id', $item->category_id) == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Satuan</label>
                            <select name="unit" class="form-select" required>
                                <option value="">-- Pilih Satuan --</option>
                                @foreach($units as $unit)
                                    <option value="{{ $unit->symbol }}" {{ old('unit', $item->unit) == $unit->symbol ? 'selected' : '' }}>
                                        {{ $unit->name }} ({{ $unit->symbol }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Stok Minimum (Alert)</label>
                        <input type="number" name="stock_minimum" class="form-control" value="{{ old('stock_minimum', $item->stock_minimum) }}">
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('items.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">Update Barang</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
