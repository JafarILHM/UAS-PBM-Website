@extends('layouts.app')

@section('content')
<h1 class="h3 mb-3">Kelola Kategori</h1>

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
                <a href="{{ route('categories.create') }}" class="btn btn-primary">
                    <i data-feather="plus"></i> Tambah Kategori Baru
                </a>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Nama Kategori</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as $item)
                            <tr>
                                <td>{{ $item->name }}</td>
                                <td class="text-end">
                                    <a href="{{ route('categories.edit', $item->id) }}" class="btn btn-sm btn-info">
                                        <i data-feather="edit"></i> Edit
                                    </a>

                                    <form action="{{ route('categories.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus kategori ini? Barang yang terhubung mungkin akan kehilangan kategorinya.')">
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
