@extends('layouts.app')

@section('content')
<h1 class="h3 mb-3">Edit Satuan</h1>

<div class="row">
    <div class="col-12 col-md-6">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('units.update', $unit->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Nama Satuan</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $unit->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Simbol / Singkatan</label>
                        <input type="text" name="symbol" class="form-control @error('symbol') is-invalid @enderror" value="{{ old('symbol', $unit->symbol) }}" required>
                        @error('symbol')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('units.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">Update Satuan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
