@extends('layouts.superadmin')

@section('title', 'Edit Kategori Aspirasi')

@section('content')

<div class="card" style="max-width: 480px;">
    <div class="card-header">Edit Kategori Aspirasi</div>
    <div class="card-body">
        <form method="POST" action="{{ route('superadmin.kategori.update', $kategori->id) }}">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label">Nama Kategori</label>
                <input type="text" name="nama_kategori" class="form-control @error('nama_kategori') is-invalid @enderror" value="{{ old('nama_kategori', $kategori->nama_kategori) }}" maxlength="50">
                @error('nama_kategori')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <button type="submit" class="btn btn-primary">Update Kategori</button>
        </form>
    </div>
</div>

@endsection
