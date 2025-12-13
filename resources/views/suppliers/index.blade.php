@extends('layouts.app')

@section('content')
<h1 class="h3 mb-3">Kelola Supplier</h1>

<div class="row">
    <div class="col-12">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSupplierModal">
                    <i data-feather="plus"></i> Tambah Supplier Baru
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover my-0">
                        <thead>
                            <tr>
                                <th>Nama Supplier</th>
                                <th>Kontak / No. Telp</th>
                                <th>Alamat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($suppliers as $supplier)
                            <tr>
                                <td><strong>{{ $supplier->name }}</strong></td>
                                <td>{{ $supplier->contact ?? '-' }}</td>
                                <td>{{ $supplier->address ?? '-' }}</td>
                                <td>
                                    <button class="btn btn-sm btn-info me-1" data-bs-toggle="modal" data-bs-target="#editSupplierModal{{ $supplier->id }}">
                                        Edit
                                    </button>
                                    <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus supplier ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                </td>
                            </tr>

                            <div class="modal fade" id="editSupplierModal{{ $supplier->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('suppliers.update', $supplier->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Supplier</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Nama Supplier</label>
                                                    <input type="text" name="name" class="form-control" value="{{ $supplier->name }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Kontak (HP/Telp)</label>
                                                    <input type="text" name="contact" class="form-control" value="{{ $supplier->contact }}">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Alamat</label>
                                                    <textarea name="address" class="form-control" rows="2">{{ $supplier->address }}</textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary">Update</button>
                                            </div>
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
</div>

<div class="modal fade" id="addSupplierModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('suppliers.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Supplier Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Supplier</label>
                        <input type="text" name="name" class="form-control" placeholder="PT. Maju Jaya" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kontak (HP/Telp)</label>
                        <input type="text" name="contact" class="form-control" placeholder="0812...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea name="address" class="form-control" rows="2" placeholder="Jl. Raya..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
