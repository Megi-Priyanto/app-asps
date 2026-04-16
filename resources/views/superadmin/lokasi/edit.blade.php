@extends('layouts.superadmin')

@section('title', 'Edit Lokasi')

@section('content')

<div class="card" style="max-width: 480px;">
    <div class="card-header">Edit Lokasi</div>
    <div class="card-body">
        <form method="POST" action="{{ route('superadmin.lokasi.update', $lokasi->id) }}">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label">Nama Lokasi</label>
                <input type="text" name="nama_lokasi" class="form-control @error('nama_lokasi') is-invalid @enderror" value="{{ old('nama_lokasi', $lokasi->nama_lokasi) }}" maxlength="50">
                @error('nama_lokasi')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <button type="submit" class="btn btn-primary">Update Lokasi</button>
        </form>
    </div>
</div>

@endsection
