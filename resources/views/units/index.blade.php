@extends('layouts.app')

@section('content')
<h1 class="h3 mb-3">Kelola Satuan (Unit)</h1>

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
                <a href="{{ route('units.create') }}" class="btn btn-primary">
                    <i data-feather="plus"></i> Tambah Satuan Baru
                </a>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Nama Satuan</th>
                                <th>Simbol</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($units as $item)
                            <tr>
                                <td>{{ $item->name }}</td>
                                <td><span class="badge bg-secondary">{{ $item->symbol }}</span></td>
                                <td class="text-end">
                                    <a href="{{ route('units.edit', $item->id) }}" class="btn btn-sm btn-info">
                                        <i data-feather="edit"></i> Edit
                                    </a>

                                    <form action="{{ route('units.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus satuan ini?');">
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
