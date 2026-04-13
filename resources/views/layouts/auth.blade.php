@extends('layouts.main')
@section('body')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap');

    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Plus Jakarta Sans', sans-serif; background: #F4F6FB; min-height: 100vh; }

    .auth-wrapper { display: flex; min-height: 100vh; }

    /* LEFT */
    .auth-left {
        flex: 1; position: relative;
        background: url('{{ asset('images/school_bg.jpeg') }}') center center / cover no-repeat;
        display: flex; flex-direction: column;
        padding: 36px 44px; overflow: hidden; min-height: 100vh;
    }
    .auth-left-overlay {
        position: absolute; inset: 0;
        background: linear-gradient(160deg, rgba(10,20,55,0.88) 0%, rgba(20,50,120,0.75) 50%, rgba(10,20,55,0.80) 100%);
    }
    .auth-left-content {
        position: relative; z-index: 2;
        display: flex; flex-direction: column; height: 100%;
        justify-content: flex-start; gap: 80px;
    }
    .auth-brand { display: flex; align-items: center; gap: 12px; text-decoration: none; }
    .auth-brand-icon {
        width: 42px; height: 42px;
        background: linear-gradient(135deg, #2563EB, #60A5FA);
        border-radius: 12px; display: flex; align-items: center; justify-content: center;
        color: white; font-size: 20px; box-shadow: 0 6px 16px rgba(37,99,235,0.4); flex-shrink: 0;
    }
    .auth-brand-text { font-size: 20px; font-weight: 800; color: white; letter-spacing: -0.5px; line-height: 1.1; }
    .auth-brand-sub  { font-size: 11px; font-weight: 500; color: rgba(255,255,255,0.55); display: block; }

    .auth-left-hero { padding: 20px 0; }
    .auth-left-badge {
        display: inline-flex; align-items: center; gap: 7px;
        background: rgba(37,99,235,0.25); border: 1px solid rgba(96,165,250,0.35);
        color: #93C5FD; font-size: 11.5px; font-weight: 700;
        padding: 5px 13px; border-radius: 20px; margin-bottom: 20px; letter-spacing: 0.3px;
    }
    .auth-left-title { font-size: clamp(26px,3vw,40px); font-weight: 900; color: white; line-height: 1.1; letter-spacing: -1px; margin-bottom: 16px; }
    .auth-left-title .accent { color: #60A5FA; }
    .auth-left-desc { font-size: 14.5px; color: rgba(255,255,255,0.65); line-height: 1.7; max-width: 360px; }



    /* RIGHT */
    .auth-right {
        width: 480px; min-width: 480px; background: white;
        display: flex; flex-direction: column;
        align-items: center; justify-content: center;
        padding: 48px 52px; position: relative; overflow-y: auto;
    }

    .auth-form-inner { width: 100%; max-width: 360px; }

    /* Form elements */
    .auth-form-header { margin-bottom: 30px; text-align: center; }
    .auth-form-icon {
        width: 52px; height: 52px; background: #EFF6FF; border: 1px solid #BFDBFE;
        border-radius: 14px; display: flex; align-items: center; justify-content: center;
        font-size: 22px; color: #2563EB; margin: 0 auto 18px;
    }
    .auth-form-title { font-size: 24px; font-weight: 900; color: #0F172A; letter-spacing: -0.6px; margin-bottom: 6px; }
    .auth-form-sub   { font-size: 14px; color: #64748B; line-height: 1.5; }

    .auth-field { margin-bottom: 18px; }
    .auth-label { display: block; font-size: 13px; font-weight: 700; color: #374151; margin-bottom: 7px; }
    .auth-input-wrap { position: relative; }
    .auth-input-icon { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #94A3B8; font-size: 15px; pointer-events: none; z-index: 1; }
    .auth-input {
        width: 100%; padding: 12px 14px 12px 42px;
        border: 1.5px solid #E2E8F0; border-radius: 11px;
        font-size: 14px; font-family: 'Plus Jakarta Sans', sans-serif;
        color: #0F172A; background: #FAFBFC; outline: none; transition: all 0.2s;
    }
    .auth-input:focus { border-color: #2563EB; background: white; box-shadow: 0 0 0 3px rgba(37,99,235,0.1); }
    .auth-input.is-invalid { border-color: #EF4444; background: #FFF8F8; }
    .auth-input.is-invalid:focus { box-shadow: 0 0 0 3px rgba(239,68,68,0.1); }

    .auth-error { display: flex; align-items: center; gap: 5px; font-size: 12px; color: #EF4444; font-weight: 600; margin-top: 6px; }
    .auth-error i { font-size: 12px; }

    .auth-submit {
        width: 100%; padding: 14px; background: #2563EB; color: white; border: none; border-radius: 12px;
        font-family: 'Plus Jakarta Sans', sans-serif; font-size: 15px; font-weight: 700; cursor: pointer;
        transition: all 0.2s; margin-top: 6px; display: flex; align-items: center; justify-content: center;
        gap: 8px; box-shadow: 0 4px 14px rgba(37,99,235,0.3); letter-spacing: -0.2px;
    }
    .auth-submit:hover { background: #1D4ED8; box-shadow: 0 6px 20px rgba(37,99,235,0.4); transform: translateY(-1px); }
    .auth-submit:active { transform: translateY(0); }

    .auth-divider { display: flex; align-items: center; gap: 12px; margin: 22px 0; }
    .auth-divider-line { flex: 1; height: 1px; background: #F1F5F9; }
    .auth-divider-text { font-size: 12.5px; color: #94A3B8; font-weight: 600; white-space: nowrap; }

    .auth-bottom-link {
        text-align: center; font-size: 13.5px; color: #64748B;
        padding: 16px; background: #F8FAFC; border-radius: 12px; border: 1px solid #F1F5F9;
    }
    .auth-bottom-link a { color: #2563EB; font-weight: 700; text-decoration: none; }
    .auth-bottom-link a:hover { text-decoration: underline; }

    .auth-alert {
        background: #FEF2F2; border: 1px solid #FECACA; border-radius: 11px;
        padding: 12px 16px; font-size: 13px; color: #DC2626; font-weight: 600;
        margin-bottom: 20px; display: flex; align-items: flex-start; gap: 8px;
    }
    .auth-alert i { font-size: 14px; flex-shrink: 0; margin-top: 1px; }

    @media (max-width: 900px) {
        .auth-left { display: none; }
        .auth-right { width: 100%; min-width: unset; padding: 40px 28px; }
    }
    @media (max-width: 480px) {
        .auth-right { padding: 32px 20px; }
    }
</style>

<div class="auth-wrapper">

    <div class="auth-left">
        <div class="auth-left-overlay"></div>
        <div class="auth-left-content">
            <a href="{{ route('welcome') }}" class="auth-brand">
                <div class="auth-brand-icon"><i class="bi bi-shield-check"></i></div>
                <div class="auth-brand-text">Apss<span class="auth-brand-sub">Sarana Sekolah</span></div>
            </a>
            <div class="auth-left-hero">
                <div class="auth-left-badge"><i class="bi bi-stars"></i> Platform Pengaduan Digital</div>
                <h2 class="auth-left-title">Suaramu <span class="accent">Membangun</span><br>Sekolah Lebih Baik</h2>
                <p class="auth-left-desc">Laporkan kerusakan fasilitas sekolah dengan mudah dan pantau progresnya secara real-time bersama kami.</p>
            </div>
        </div>
    </div>

    <div class="auth-right">
        {{-- Tidak ada tombol "Kembali ke Beranda" --}}
        <div class="auth-form-inner">
            @yield('content')
        </div>
    </div>

</div>

@endsection