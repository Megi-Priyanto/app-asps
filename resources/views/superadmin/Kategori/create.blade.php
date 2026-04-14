@extends('layouts.superadmin')

@section('title', 'Tambah Kategori')

@section('content')

<div class="card" style="max-width: 480px;">
    <div class="card-header">Tambah Kategori Baru</div>
    <div class="card-body">
        <form method="POST" action="{{ route('superadmin.kategori.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Nama Kategori</label>
                <input type="text" name="nama_kategori" class="form-control @error('nama_kategori') is-invalid @enderror" placeholder="Max 50 karakter" value="{{ old('nama_kategori') }}" maxlength="50">
                @error('nama_kategori')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <button type="submit" class="btn btn-primary">Simpan Kategori</button>
        </form>
    </div>
</div>

@endsection
