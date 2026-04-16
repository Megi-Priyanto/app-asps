@extends('layouts.superadmin')

@section('title', 'Tambah Lokasi')

@section('content')

<div class="card" style="max-width: 480px;">
    <div class="card-header">Tambah Lokasi Baru</div>
    <div class="card-body">
        <form method="POST" action="{{ route('superadmin.lokasi.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Nama Lokasi</label>
                <input type="text" name="nama_lokasi" class="form-control @error('nama_lokasi') is-invalid @enderror" placeholder="Max 50 karakter" value="{{ old('nama_lokasi') }}" maxlength="50">
                @error('nama_lokasi')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <button type="submit" class="btn btn-primary">Simpan Lokasi</button>
        </form>
    </div>
</div>

@endsection
