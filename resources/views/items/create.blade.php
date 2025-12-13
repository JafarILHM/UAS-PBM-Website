@extends('layouts.app')

@section('content')
<h1 class="h3 mb-3">Tambah Barang Baru</h1>

<div class="row">
    <div class="col-12 col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('items.store') }}" method="POST">
                    @csrf

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">SKU (Kode Unik)</label>
                            <input type="text" name="sku" class="form-control" value="{{ $autoSku }}" required>
                            <small class="text-muted">Bisa diubah manual atau scan barcode.</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Barcode (Opsional)</label>
                            <input type="text" name="barcode" class="form-control" placeholder="Scan di sini jika ada">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama Barang</label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: Indomie Goreng" required>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Kategori</label>
                            <select name="category_id" class="form-select" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Satuan</label>
                            <select name="unit" class="form-select" required>
                                <option value="">-- Pilih Satuan --</option>
                                @foreach($units as $unit)
                                    <option value="{{ $unit->symbol }}">{{ $unit->name }} ({{ $unit->symbol }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Stok Minimum (Alert)</label>
                        <input type="number" name="stock_minimum" class="form-control" value="5">
                        <small class="text-muted">Sistem akan memberi peringatan jika stok di bawah angka ini.</small>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('items.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan Barang</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
