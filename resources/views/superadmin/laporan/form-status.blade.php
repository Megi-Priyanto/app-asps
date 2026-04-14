<div class="card">
    <div class="card-header"><i class="bi bi-sliders me-2" style="color:#2563EB;"></i>Update Status</div>
    <div class="card-body">
        <form action="{{ route('superadmin.laporan.update', $laporan->id) }}" method="POST">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label">Status Laporan</label>
                <select name="status" class="form-select" required>
                    <option value="">-- Pilih Status --</option>
                    <option value="proses" {{ $laporan->aspirasi?->status === 'proses' ? 'selected' : '' }}>Proses</option>
                    <option value="selesai" {{ $laporan->aspirasi?->status === 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary w-100">
                <i class="bi bi-check-lg me-1"></i>Simpan Perubahan
            </button>
        </form>
    </div>
</div>
