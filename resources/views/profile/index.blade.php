@extends('layouts.app')

@section('content')
<h1 class="h3 mb-3">Profil Saya</h1>

<div class="row">
    <div class="col-md-4 col-xl-3">
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="card-title mb-0">Detail Profil</h5>
            </div>
            <div class="card-body text-center">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random&color=fff&size=128"
                     alt="{{ $user->name }}" class="img-fluid rounded-circle mb-2" width="128" height="128" />

                <h5 class="card-title mb-0">{{ $user->name }}</h5>
                <div class="text-muted mb-2">{{ $user->email }}</div>

                <div>
                    @if($user->role === 'admin')
                        <span class="badge bg-primary">Administrator</span>
                    @else
                        <span class="badge bg-success">Staff Gudang</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8 col-xl-9">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Edit Informasi</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <hr class="my-3">
                    <h5 class="mb-3 text-danger"><i data-feather="lock" class="me-1"></i> Ganti Password (Opsional)</h5>
                    <div class="text-muted small mb-3">Biarkan kosong jika tidak ingin mengubah password.</div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Password Baru</label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Konfirmasi Password Baru</label>
                                <input type="password" name="password_confirmation" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
