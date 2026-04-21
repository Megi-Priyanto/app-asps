@extends('layouts.admin')

@section('title', 'Tambah Kategori Barang')

@section('content')

<div class="card" style="max-width: 480px;">
    <div class="card-header">Pilih Kategori Barang untuk Lokasi Anda</div>
    <div class="card-body">
        @if($kategoriTersedia->isEmpty())
            <div class="text-center py-4" style="color:#94A3B8;">
                <i class="bi bi-check-circle fs-2 d-block mb-2"></i>
                Semua kategori sudah ditambahkan ke lokasi Anda.
            </div>
            <a href="{{ route('admin.kategori-barang.index') }}" class="btn btn-secondary w-100 mt-2">Kembali</a>
        @else
            <form method="POST" action="{{ route('admin.kategori-barang.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Pilih Kategori</label>
                    <select name="kategori_barang_id" class="form-select @error('kategori_barang_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($kategoriTersedia as $k)
                            <option value="{{ $k->id }}" {{ old('kategori_barang_id') == $k->id ? 'selected' : '' }}>
                                {{ $k->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                    @error('kategori_barang_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <button type="submit" class="btn btn-primary">Tambahkan ke Lokasi</button>
                <a href="{{ route('admin.kategori-barang.index') }}" class="btn btn-secondary ms-2">Batal</a>
            </form>
        @endif
    </div>
</div>

@endsection
