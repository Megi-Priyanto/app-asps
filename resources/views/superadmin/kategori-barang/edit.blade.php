@extends('layouts.superadmin')

@section('title', 'Edit Kategori Barang')

@section('content')

<div class="card" style="max-width: 480px;">
    <div class="card-header">Edit Kategori Barang</div>
    <div class="card-body">
        <form method="POST" action="{{ route('superadmin.kategori-barang.update', $kategoriBarang->id) }}">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label">Nama Kategori</label>
                <input type="text" name="nama_kategori" class="form-control @error('nama_kategori') is-invalid @enderror" value="{{ old('nama_kategori', $kategoriBarang->nama_kategori) }}" maxlength="100" required>
                @error('nama_kategori')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="{{ route('superadmin.kategori-barang.index') }}" class="btn btn-secondary ms-2">Batal</a>
        </form>
    </div>
</div>

@endsection
