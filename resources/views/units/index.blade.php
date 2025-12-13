@extends('layouts.app')

@section('content')
<h1 class="h3 mb-3">Kelola Satuan (Unit)</h1>
<div class="row">
    <div class="col-12">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <div class="card">
            <div class="card-header">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">Tambah Satuan</button>
            </div>
            <div class="card-body">
                <table class="table table-hover my-0">
                    <thead><tr><th>Nama</th><th>Simbol</th><th>Aksi</th></tr></thead>
                    <tbody>
                        @foreach ($units as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td><span class="badge bg-secondary">{{ $item->symbol }}</span></td>
                            <td>
                                <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#editModal{{ $item->id }}">Edit</button>
                                <form action="{{ route('units.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')<button class="btn btn-sm btn-danger">Hapus</button></form>
                            </td>
                        </tr>
                        <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('units.update', $item->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <div class="modal-header"><h5 class="modal-title">Edit Satuan</h5></div>
                                        <div class="modal-body">
                                            <div class="mb-3"><label class="form-label">Nama (Misal: Kilogram)</label><input type="text" name="name" class="form-control" value="{{ $item->name }}" required></div>
                                            <div class="mb-3"><label class="form-label">Simbol (Misal: kg)</label><input type="text" name="symbol" class="form-control" value="{{ $item->symbol }}" required></div>
                                        </div>
                                        <div class="modal-footer"><button type="submit" class="btn btn-primary">Update</button></div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('units.store') }}" method="POST">
                @csrf
                <div class="modal-header"><h5 class="modal-title">Tambah Satuan</h5></div>
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label">Nama</label><input type="text" name="name" class="form-control" placeholder="Box" required></div>
                    <div class="mb-3"><label class="form-label">Simbol</label><input type="text" name="symbol" class="form-control" placeholder="box" required></div>
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-primary">Simpan</button></div>
            </form>
        </div>
    </div>
</div>
@endsection
