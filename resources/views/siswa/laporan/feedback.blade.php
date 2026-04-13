<div class="card mb-3">
    <div class="card-header"><i class="bi bi-star-fill me-2" style="color:#F59E0B;"></i>Feedback Kepuasan</div>
    <div class="card-body">
        @if ($laporan->aspirasi->feedback)
            <div style="background:#ECFDF5; color:#065F46; padding:12px 16px; border-radius:8px; font-size:13.5px; font-weight:600; margin-bottom:12px;">
                <i class="bi bi-check-circle-fill me-2"></i>
                Feedback telah diberikan:
                {{ [1=>'Tidak Puas',2=>'Kurang Puas',3=>'Cukup Puas',4=>'Puas',5=>'Sangat Puas'][$laporan->aspirasi->feedback] ?? '-' }}
            </div>
            @if ($laporan->aspirasi->alasan)
                <div style="background:#FFFBEB; color:#92400E; padding:12px 16px; border-radius:8px; font-size:13px;">
                    <strong>Alasan:</strong> {{ $laporan->aspirasi->alasan }}
                </div>
            @endif
        @else
            <form action="{{ route('siswa.laporan.feedback', $laporan->aspirasi->id) }}" method="POST">
                @csrf
                <p style="font-size:13.5px; color:#64748B; margin-bottom:14px;">Bagaimana kepuasan Anda terhadap penanganan laporan ini?</p>

                <div class="d-flex flex-wrap gap-2 mb-3">
                    @foreach([1=>'😞 Tidak Puas', 2=>'😐 Kurang Puas', 3=>'🙂 Cukup Puas', 4=>'😊 Puas', 5=>'🤩 Sangat Puas'] as $val => $label)
                        <label style="cursor:pointer;">
                            <input type="radio" name="feedback" value="{{ $val }}" class="feedback-radio d-none" {{ old('feedback') == $val ? 'checked' : '' }}>
                            <span class="feedback-opt" style="display:inline-block; padding:7px 14px; border-radius:20px; font-size:13px; font-weight:600; border:1.5px solid #E2E8F0; color:#64748B; transition:all 0.15s; user-select:none;">{{ $label }}</span>
                        </label>
                    @endforeach
                </div>
                @error('feedback')<div style="color:#EF4444; font-size:12px; margin-bottom:10px;">{{ $message }}</div>@enderror

                <div id="alasan-container" style="display:{{ in_array(old('feedback'), ['1','2']) ? 'block' : 'none' }}; margin-bottom:14px;">
                    <textarea name="alasan" id="alasan" rows="3" class="form-control" placeholder="Jelaskan alasan ketidakpuasan Anda..." style="font-family:'Plus Jakarta Sans',sans-serif; font-size:13.5px; border:1.5px solid #E2E8F0; border-radius:8px; padding:10px 13px; width:100%;">{{ old('alasan') }}</textarea>
                    @error('alasan')<div style="color:#EF4444; font-size:12px; margin-top:4px;">{{ $message }}</div>@enderror
                </div>

                <button class="btn btn-primary btn-sm" style="padding:8px 20px;">
                    <i class="bi bi-send me-1"></i>Kirim Feedback
                </button>
            </form>

            <script>
            document.addEventListener('DOMContentLoaded', function() {
                const radios = document.querySelectorAll('.feedback-radio');
                const alasanContainer = document.getElementById('alasan-container');
                const opts = document.querySelectorAll('.feedback-opt');

                function updateStyles() {
                    radios.forEach((r, i) => {
                        opts[i].style.background = r.checked ? '#EFF6FF' : '';
                        opts[i].style.borderColor = r.checked ? '#2563EB' : '#E2E8F0';
                        opts[i].style.color = r.checked ? '#2563EB' : '#64748B';
                    });
                }

                radios.forEach(function(radio) {
                    radio.addEventListener('change', function() {
                        alasanContainer.style.display = (this.value === '1' || this.value === '2') ? 'block' : 'none';
                        if (this.value !== '1' && this.value !== '2') document.getElementById('alasan').value = '';
                        updateStyles();
                    });
                });
                updateStyles();
            });
            </script>
        @endif
    </div>
</div>
