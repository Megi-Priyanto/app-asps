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
        padding: 5px 13px; border-radius: 20px; margin-bottom: 24px; letter-spacing: 0.3px;
    }
    .auth-left-title { font-size: clamp(28px, 3.5vw, 42px); font-weight: 900; color: white; line-height: 1.15; letter-spacing: -1.2px; margin-bottom: 20px; }
    .auth-left-title .accent { color: #60A5FA; }
    .auth-left-desc { font-size: 15.5px; color: rgba(255,255,255,0.7); line-height: 1.7; max-width: 420px; margin-bottom: 40px; }

    /* FEATURE LIST */
    .auth-features { display: grid; grid-template-columns: 1fr; gap: 16px; margin-top: 10px; }
    .auth-feature-item {
        display: flex; align-items: center; gap: 14px;
        background: rgba(255,255,255,0.04); padding: 12px 16px;
        border-radius: 12px; border: 1px solid rgba(255,255,255,0.08);
        backdrop-filter: blur(4px); transition: all 0.2s;
        max-width: 380px;
    }
    .auth-feature-item:hover { background: rgba(255,255,255,0.08); transform: translateX(5px); border-color: rgba(255,255,255,0.15); }
    .auth-feature-icon {
        width: 36px; height: 36px; border-radius: 10px; background: rgba(37,99,235,0.2);
        display: flex; align-items: center; justify-content: center;
        color: #60A5FA; font-size: 18px; flex-shrink: 0;
    }
    .auth-feature-text { color: rgba(255,255,255,0.85); font-size: 13.5px; font-weight: 600; }



    /* RIGHT */
    .auth-right {
        width: 520px; min-width: 520px; background: white;
        display: flex; flex-direction: column;
        align-items: center; justify-content: center;
        padding: 48px 60px; position: relative; overflow-y: auto;
        box-shadow: -10px 0 30px rgba(0,0,0,0.02);
    }

    .auth-form-inner { width: 100%; max-width: 380px; }

    /* Form elements */
    .auth-form-header { margin-bottom: 32px; text-align: center; }
    .auth-form-icon {
        width: 60px; height: 60px; background: linear-gradient(135deg, #EFF6FF, #DBEAFE);
        border: 1px solid #BFDBFE; border-radius: 18px;
        display: flex; align-items: center; justify-content: center;
        font-size: 26px; color: #2563EB; margin: 0 auto 20px;
        box-shadow: 0 4px 10px rgba(37, 99, 235, 0.08);
    }
    .auth-form-title { font-size: 26px; font-weight: 900; color: #0F172A; letter-spacing: -0.8px; margin-bottom: 8px; }
    .auth-form-sub   { font-size: 14.5px; color: #64748B; line-height: 1.6; }

    .auth-field { margin-bottom: 22px; }
    .auth-label { display: block; font-size: 13.5px; font-weight: 700; color: #334155; margin-bottom: 8px; }
    .auth-input-wrap { position: relative; }
    .auth-input-icon { position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #94A3B8; font-size: 16px; pointer-events: none; z-index: 1; transition: color 0.2s; }
    .auth-input {
        width: 100%; padding: 14px 16px 14px 46px;
        border: 1.5px solid #E2E8F0; border-radius: 12px;
        font-size: 14.5px; font-family: 'Plus Jakarta Sans', sans-serif;
        color: #0F172A; background: #F8FAFC; outline: none; transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .auth-input:focus { border-color: #2563EB; background: white; box-shadow: 0 0 0 4px rgba(37,99,235,0.08); }
    .auth-input:focus + .auth-input-icon { color: #2563EB; }
    .auth-input.is-invalid { border-color: #EF4444; background: #FFF8F8; }
    .auth-input.is-invalid:focus { box-shadow: 0 0 0 4px rgba(239,68,68,0.08); }

    .auth-error { display: flex; align-items: center; gap: 6px; font-size: 12.5px; color: #EF4444; font-weight: 600; margin-top: 8px; }
    .auth-error i { font-size: 13px; }

    .auth-submit {
        width: 100%; padding: 15px; background: linear-gradient(135deg, #2563EB, #1E40AF);
        color: white; border: none; border-radius: 14px;
        font-family: 'Plus Jakarta Sans', sans-serif; font-size: 15.5px; font-weight: 700; cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); margin-top: 10px; display: flex; align-items: center; justify-content: center;
        gap: 10px; box-shadow: 0 8px 20px rgba(37,99,235,0.25); letter-spacing: -0.2px;
    }
    .auth-submit:hover { transform: translateY(-2px); box-shadow: 0 12px 28px rgba(37,99,235,0.35); filter: brightness(1.05); }
    .auth-submit:active { transform: translateY(0); }

    .auth-divider { display: flex; align-items: center; gap: 14px; margin: 26px 0; }
    .auth-divider-line { flex: 1; height: 1px; background: #E2E8F0; }
    .auth-divider-text { font-size: 13px; color: #94A3B8; font-weight: 600; white-space: nowrap; text-transform: uppercase; letter-spacing: 0.5px; }

    .auth-bottom-link {
        text-align: center; font-size: 14px; color: #64748B;
        padding: 18px; background: #F8FAFC; border-radius: 14px; border: 1px solid #E2E8F0;
        transition: all 0.2s;
    }
    .auth-bottom-link:hover { border-color: #CBD5E1; background: #F1F5F9; }
    .auth-bottom-link a { color: #2563EB; font-weight: 700; text-decoration: none; }
    .auth-bottom-link a:hover { text-decoration: underline; }

    .auth-alert {
        background: #FEF2F2; border: 1px solid #FECACA; border-radius: 12px;
        padding: 14px 18px; font-size: 13.5px; color: #DC2626; font-weight: 600;
        margin-bottom: 24px; display: flex; align-items: center; gap: 10px;
        animation: shake 0.5s cubic-bezier(.36,.07,.19,.97) both;
    }
    @keyframes shake {
        10%, 90% { transform: translate3d(-1px, 0, 0); }
        20%, 80% { transform: translate3d(2px, 0, 0); }
        30%, 50%, 70% { transform: translate3d(-4px, 0, 0); }
        40%, 60% { transform: translate3d(4px, 0, 0); }
    }
    .auth-alert i { font-size: 16px; flex-shrink: 0; }

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
                <div class="auth-brand-icon" style="background: transparent; box-shadow: none;">
                    <img src="{{ asset('images/logosmk_transparent.png') }}" alt="Logo" style="width: 100%; height: 100%; object-fit: contain;">
                </div>
                <div class="auth-brand-text">Asps<span class="auth-brand-sub">Sarana Sekolah</span></div>
            </a>
            <div class="auth-left-hero">
                <div class="auth-left-badge"><i class="bi bi-stars"></i> Sinergi Layanan Digital</div>
                <h2 class="auth-left-title">Layanan Sekolah <span class="accent">Modern</span><br>dalam Satu Genggaman</h2>
                <p class="auth-left-desc">Dari penyampaian aspirasi hingga peminjaman fasilitas sekolah, semua jadi lebih praktis, transparan, dan terintegrasi.</p>

                <div class="auth-features">
                    <div class="auth-feature-item">
                        <div class="auth-feature-icon"><i class="bi bi-chat-left-dots-fill"></i></div>
                        <span class="auth-feature-text">Aspirasi Digital Cepat & Tanggap</span>
                    </div>
                    <div class="auth-feature-item">
                        <div class="auth-feature-icon"><i class="bi bi-box-seam-fill"></i></div>
                        <span class="auth-feature-text">Manajemen Peminjaman Barang Efisien</span>
                    </div>
                    <div class="auth-feature-item">
                        <div class="auth-feature-icon"><i class="bi bi-shield-fill-check"></i></div>
                        <span class="auth-feature-text">Tracking Status Real-time & Akurat</span>
                    </div>
                </div>
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
