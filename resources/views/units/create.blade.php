@extends('layouts.app')

@section('content')
<h1 class="h3 mb-3">Tambah Satuan Baru</h1>

<div class="row">
    <div class="col-12 col-md-6"> <div class="card">
            <div class="card-body">
                <form action="{{ route('units.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Nama Satuan</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Contoh: Kilogram" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Simbol / Singkatan</label>
                        <input type="text" name="symbol" class="form-control @error('symbol') is-invalid @enderror" placeholder="Contoh: kg" value="{{ old('symbol') }}" required>
                        @error('symbol')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Simbol ini yang akan muncul di belakang angka stok (Misal: 10 kg).</div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('units.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan Satuan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
