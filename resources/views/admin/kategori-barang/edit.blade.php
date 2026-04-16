@extends('layouts.admin')

@section('title', 'Edit Kategori Barang')

@section('content')

<div class="card" style="max-width: 480px;">
    <div class="card-header">Edit Kategori Barang</div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.kategori-barang.update', $kategoriBarang->id) }}">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label">Nama Kategori</label>
                <input type="text" name="nama_kategori" class="form-control @error('nama_kategori') is-invalid @enderror" value="{{ old('nama_kategori', $kategoriBarang->nama_kategori) }}" maxlength="100" required>
                @error('nama_kategori')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <button type="submit" class="btn btn-primary">Update Kategori</button>
        </form>
    </div>
</div>

@endsection
