@extends('layouts.siswa')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap');

    * { box-sizing: border-box; }

    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        margin: 0;
        padding: 0;
        overflow-x: hidden;
    }

    /* ===== HERO SECTION ===== */
    .hero-section {
        position: relative;
        min-height: 100vh;
        display: flex;
        align-items: center;
        background: url('{{ asset('images/school_bg.jpeg') }}') center center / cover no-repeat;
        overflow: hidden;
    }

    .hero-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(
            135deg,
            rgba(10, 18, 42, 0.82) 0%,
            rgba(15, 30, 70, 0.70) 50%,
            rgba(10, 18, 42, 0.55) 100%
        );
    }

    .hero-content {
        position: relative;
        z-index: 2;
        width: 100%;
        max-width: 1280px;
        margin: 0 auto;
        padding: 100px 32px 60px;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 48px;
        align-items: center;
    }

    /* Left Column */
    .hero-left {}

    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        background: rgba(37,99,235,0.2);
        border: 1px solid rgba(96,165,250,0.4);
        color: #93C5FD;
        font-size: 12px;
        font-weight: 700;
        padding: 6px 14px;
        border-radius: 30px;
        margin-bottom: 24px;
        letter-spacing: 0.3px;
        backdrop-filter: blur(6px);
    }

    .hero-badge i {
        font-size: 13px;
        color: #60A5FA;
    }

    .hero-title {
        font-size: clamp(34px, 5vw, 56px);
        font-weight: 900;
        color: #ffffff;
        line-height: 1.1;
        letter-spacing: -1.5px;
        margin-bottom: 20px;
    }

    .hero-title .accent {
        color: #60A5FA;
        display: block;
    }

    .hero-desc {
        font-size: 16px;
        color: rgba(255,255,255,0.72);
        line-height: 1.7;
        margin-bottom: 36px;
        max-width: 480px;
        font-weight: 400;
    }

    .hero-cta {
        display: flex;
        align-items: center;
        gap: 14px;
        flex-wrap: wrap;
    }

    .btn-hero-primary {
        display: inline-flex;
        align-items: center;
        gap: 9px;
        padding: 14px 28px;
        background: #2563EB;
        color: white;
        font-size: 15px;
        font-weight: 700;
        border-radius: 14px;
        text-decoration: none;
        transition: all 0.25s;
        box-shadow: 0 8px 24px rgba(37,99,235,0.4);
        border: none;
        font-family: 'Plus Jakarta Sans', sans-serif;
        cursor: pointer;
    }
    .btn-hero-primary:hover {
        background: #1D4ED8;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 12px 32px rgba(37,99,235,0.5);
        text-decoration: none;
    }

    .btn-hero-outline {
        display: inline-flex;
        align-items: center;
        gap: 9px;
        padding: 14px 28px;
        background: rgba(255,255,255,0.08);
        color: rgba(255,255,255,0.9);
        font-size: 15px;
        font-weight: 700;
        border-radius: 14px;
        text-decoration: none;
        transition: all 0.25s;
        border: 1.5px solid rgba(255,255,255,0.3);
        backdrop-filter: blur(6px);
    }
    .btn-hero-outline:hover {
        background: rgba(255,255,255,0.15);
        color: white;
        border-color: rgba(255,255,255,0.6);
        text-decoration: none;
        transform: translateY(-2px);
    }

    .btn-hero-dashboard {
        display: inline-flex;
        align-items: center;
        gap: 9px;
        padding: 14px 28px;
        background: #10B981;
        color: white;
        font-size: 15px;
        font-weight: 700;
        border-radius: 14px;
        text-decoration: none;
        transition: all 0.25s;
        box-shadow: 0 8px 24px rgba(16,185,129,0.4);
    }
    .btn-hero-dashboard:hover {
        background: #059669;
        color: white;
        transform: translateY(-2px);
        text-decoration: none;
    }

    /* Trust badges */
    .hero-trust {
        margin-top: 36px;
        display: flex;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
    }
    .hero-trust-item {
        display: flex;
        align-items: center;
        gap: 7px;
        color: rgba(255,255,255,0.6);
        font-size: 13px;
        font-weight: 500;
    }
    .hero-trust-item i {
        color: #34D399;
        font-size: 14px;
    }

    /* Right Column */
    .hero-right {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    /* Glass Cards */
    .glass-card {
        background: rgba(255, 255, 255, 0.10);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.18);
        border-radius: 20px;
        padding: 24px;
        transition: all 0.3s;
    }
    .glass-card:hover {
        background: rgba(255, 255, 255, 0.14);
        border-color: rgba(255, 255, 255, 0.25);
    }

    /* How to report */
    .how-title {
        font-size: 15px;
        font-weight: 800;
        color: #ffffff;
        margin-bottom: 18px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .step-item {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        margin-bottom: 16px;
    }
    .step-item:last-child { margin-bottom: 0; }

    .step-num {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        background: #2563EB;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 800;
        font-size: 13px;
        flex-shrink: 0;
        box-shadow: 0 4px 10px rgba(37,99,235,0.35);
    }

    .step-title {
        font-size: 14px;
        font-weight: 700;
        color: #ffffff;
        margin-bottom: 3px;
    }
    .step-desc {
        font-size: 12.5px;
        color: rgba(255,255,255,0.6);
        line-height: 1.5;
    }

    /* Stats card */
    .stats-card {
        background: rgba(255,255,255,0.08);
        backdrop-filter: blur(16px);
        border: 1px solid rgba(255,255,255,0.15);
        border-radius: 20px;
        padding: 24px 28px;
        display: flex;
        align-items: center;
        gap: 20px;
        transition: all 0.3s;
    }
    .stats-card:hover {
        background: rgba(255,255,255,0.13);
    }

    .stats-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        background: rgba(16,185,129,0.2);
        border: 1px solid rgba(16,185,129,0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        color: #34D399;
        flex-shrink: 0;
    }

    .stats-number {
        font-size: 36px;
        font-weight: 900;
        color: #34D399;
        letter-spacing: -1px;
        line-height: 1;
    }

    .stats-label {
        font-size: 13px;
        color: rgba(255,255,255,0.6);
        font-weight: 500;
        margin-top: 4px;
    }

    /* ===== FEATURES SECTION ===== */
    .features-section {
        background: #F4F6FB;
        padding: 80px 32px;
    }

    .section-header {
        text-align: center;
        max-width: 560px;
        margin: 0 auto 56px;
    }

    .section-eyebrow {
        display: inline-block;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        color: #2563EB;
        background: #EFF6FF;
        padding: 5px 14px;
        border-radius: 20px;
        margin-bottom: 14px;
    }

    .section-title {
        font-size: 32px;
        font-weight: 900;
        color: #0F172A;
        letter-spacing: -0.8px;
        margin-bottom: 14px;
        line-height: 1.2;
    }

    .section-desc {
        font-size: 15.5px;
        color: #64748B;
        line-height: 1.7;
    }

    .features-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 24px;
        max-width: 1100px;
        margin: 0 auto;
    }

    .feature-card {
        background: white;
        border: 1px solid #E8EDF5;
        border-radius: 18px;
        padding: 28px 26px;
        transition: all 0.3s;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }
    .feature-card:hover {
        box-shadow: 0 8px 32px rgba(37,99,235,0.1);
        border-color: #BFDBFE;
        transform: translateY(-4px);
    }

    .feature-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        margin-bottom: 18px;
    }
    .feature-icon.blue   { background: #EFF6FF; color: #2563EB; }
    .feature-icon.green  { background: #ECFDF5; color: #10B981; }
    .feature-icon.orange { background: #FFF7ED; color: #EA580C; }

    .feature-title {
        font-size: 16px;
        font-weight: 800;
        color: #0F172A;
        margin-bottom: 8px;
    }

    .feature-desc {
        font-size: 13.5px;
        color: #64748B;
        line-height: 1.65;
    }

    /* ===== CTA BOTTOM SECTION ===== */
    .cta-section {
        background: linear-gradient(135deg, #1E3A8A 0%, #2563EB 60%, #3B82F6 100%);
        padding: 72px 32px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    .cta-section::before {
        content: '';
        position: absolute;
        top: -60px;
        right: -60px;
        width: 300px;
        height: 300px;
        background: rgba(255,255,255,0.05);
        border-radius: 50%;
    }
    .cta-section::after {
        content: '';
        position: absolute;
        bottom: -80px;
        left: -40px;
        width: 250px;
        height: 250px;
        background: rgba(255,255,255,0.04);
        border-radius: 50%;
    }
    .cta-inner {
        position: relative;
        z-index: 2;
        max-width: 600px;
        margin: 0 auto;
    }
    .cta-title {
        font-size: 32px;
        font-weight: 900;
        color: white;
        letter-spacing: -0.8px;
        margin-bottom: 14px;
    }
    .cta-desc {
        font-size: 16px;
        color: rgba(255,255,255,0.75);
        margin-bottom: 32px;
        line-height: 1.6;
    }
    .btn-cta-white {
        display: inline-flex;
        align-items: center;
        gap: 9px;
        padding: 14px 28px;
        background: white;
        color: #2563EB;
        font-size: 15px;
        font-weight: 700;
        border-radius: 14px;
        text-decoration: none;
        transition: all 0.25s;
        box-shadow: 0 4px 16px rgba(0,0,0,0.15);
    }
    .btn-cta-white:hover {
        color: #1D4ED8;
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.2);
        text-decoration: none;
    }
    .btn-cta-outline {
        display: inline-flex;
        align-items: center;
        gap: 9px;
        padding: 14px 28px;
        background: transparent;
        color: rgba(255,255,255,0.9);
        font-size: 15px;
        font-weight: 700;
        border-radius: 14px;
        text-decoration: none;
        transition: all 0.25s;
        border: 1.5px solid rgba(255,255,255,0.4);
    }
    .btn-cta-outline:hover {
        background: rgba(255,255,255,0.1);
        color: white;
        border-color: rgba(255,255,255,0.7);
        text-decoration: none;
    }

    /* ===== FOOTER ===== */
    .site-footer {
        background: #0F172A;
        padding: 32px;
        text-align: center;
    }
    .site-footer p {
        color: rgba(255,255,255,0.4);
        font-size: 13px;
        margin: 0;
    }
    .site-footer a {
        color: #60A5FA;
        text-decoration: none;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 992px) {
        .hero-content { grid-template-columns: 1fr; gap: 32px; padding: 100px 24px 50px; }
        .hero-right { max-width: 600px; }
        .features-grid { grid-template-columns: 1fr 1fr; }
    }

    @media (max-width: 640px) {
        .hero-title { font-size: 32px; }
        .features-section { padding: 60px 20px; }
        .features-grid { grid-template-columns: 1fr; }
        .section-title { font-size: 26px; }
        .cta-section { padding: 56px 20px; }
        .cta-title { font-size: 26px; }
    }

    /* ===== TESTIMONIALS ===== */
    .testimonial-section {
        padding: 80px 32px;
        background: #ffffff;
    }
    .testimonial-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 28px;
        max-width: 1100px;
        margin: 0 auto;
    }
    .testimonial-card {
        background: #F8FAFC;
        border: 1px solid #E2E8F0;
        border-radius: 20px;
        padding: 32px;
        position: relative;
        transition: all 0.3s;
    }
    .testimonial-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        border-color: #CBD5E1;
    }
    .testimonial-quote {
        font-size: 15px;
        line-height: 1.8;
        color: #334155;
        margin-bottom: 24px;
        font-style: italic;
    }
    .testimonial-author {
        display: flex;
        align-items: center;
        gap: 14px;
    }
    .testimonial-avatar {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: linear-gradient(135deg, #2563EB, #60A5FA);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 16px;
    }
    .testimonial-name {
        font-size: 14px;
        font-weight: 800;
        color: #0F172A;
        margin-bottom: 2px;
    }
    .testimonial-role {
        font-size: 12px;
        color: #64748B;
        font-weight: 600;
    }
    .quote-icon {
        position: absolute;
        top: 24px;
        right: 24px;
        font-size: 32px;
        color: #E2E8F0;
        opacity: 0.5;
    }
</style>

<!-- ===== HERO ===== -->
<section class="hero-section">
    <div class="hero-overlay"></div>
    <div class="hero-content">

        <!-- Left -->
        <div class="hero-left">
            <div class="hero-badge">
                <i class="bi bi-stars"></i> v2.0 Enterprise
            </div>

            <h1 class="hero-title">
                Aplikasi Pengaduan
                <span class="accent">Sarana Sekolah</span>
            </h1>

            <p class="hero-desc">
                Platform digital terpadu untuk melaporkan dan melacak status perbaikan fasilitas sekolah Anda. Mari bersama wujudkan lingkungan belajar yang nyaman dan aman.
            </p>

            <div class="hero-cta">
                @guest('siswa')
                    <a href="{{ route('login') }}" class="btn-hero-primary">
                        <i class="bi bi-box-arrow-in-right"></i> Masuk
                    </a>
                @endguest
                @auth('siswa')
                    <a href="{{ route('siswa.dashboard') }}" class="btn-hero-dashboard">
                        <i class="bi bi-speedometer2"></i> Buka Dashboard
                    </a>
                @endauth
            </div>

            <div class="hero-trust">
                <div class="hero-trust-item">
                    <i class="bi bi-check-circle-fill"></i> Mudah Digunakan
                </div>
                <div class="hero-trust-item">
                    <i class="bi bi-check-circle-fill"></i> Respon Cepat
                </div>
                <div class="hero-trust-item">
                    <i class="bi bi-check-circle-fill"></i> Terpantau Real-time
                </div>
            </div>
        </div>

        <!-- Right -->
        <div class="hero-right">
            <!-- How to Report Card -->
            <div class="glass-card">
                <div class="how-title">
                    <i class="bi bi-lightning-charge-fill" style="color:#F59E0B;"></i>
                    Cara Melapor:
                </div>
                <div class="step-item">
                    <div class="step-num">1</div>
                    <div>
                        <div class="step-title">Foto Kerusakan</div>
                        <div class="step-desc">Temukan fasilitas rusak dan ambil foto kondisinya.</div>
                    </div>
                </div>
                <div class="step-item">
                    <div class="step-num">2</div>
                    <div>
                        <div class="step-title">Isi Formulir & Kirim</div>
                        <div class="step-desc">Tentukan lokasi, kategori, lalu kirimkan laporan Anda.</div>
                    </div>
                </div>
                <div class="step-item">
                    <div class="step-num">3</div>
                    <div>
                        <div class="step-title">Pantau Prosesnya</div>
                        <div class="step-desc">Lihat respons dari Admin dan tunggu perbaikannya selesai!</div>
                    </div>
                </div>
            </div>

            <!-- Stats Card -->
            @php
                $resolvedCount = \Illuminate\Support\Facades\DB::table('aspirasis')->where('status', 'selesai')->count();
            @endphp
            <div class="stats-card">
                <div class="stats-icon">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <div>
                    <div class="stats-number">{{ $resolvedCount }}+</div>
                    <div class="stats-label">Laporan Fasilitas Telah Diselesaikan</div>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- ===== FEATURES ===== -->
<section class="features-section">
    <div class="section-header">
        <span class="section-eyebrow">Fitur Unggulan</span>
        <h2 class="section-title">Semua yang Kamu Butuhkan</h2>
        <p class="section-desc">Sistem pengaduan modern yang memudahkan siswa melaporkan kerusakan fasilitas sekolah dengan cepat dan transparan.</p>
    </div>

    <div class="features-grid">
        <div class="feature-card">
            <div class="feature-icon blue">
                <i class="bi bi-camera-fill"></i>
            </div>
            <div class="feature-title">Laporan dengan Foto</div>
            <div class="feature-desc">Sertakan foto bukti kerusakan langsung dari kamera atau galeri untuk laporan yang lebih akurat dan informatif.</div>
        </div>
        <div class="feature-card">
            <div class="feature-icon green">
                <i class="bi bi-activity"></i>
            </div>
            <div class="feature-title">Pantau Status Real-time</div>
            <div class="feature-desc">Lacak perkembangan laporan kamu kapan saja — mulai dari diterima, diproses, hingga selesai diperbaiki.</div>
        </div>
        <div class="feature-card">
            <div class="feature-icon orange">
                <i class="bi bi-chat-dots-fill"></i>
            </div>
            <div class="feature-title">Respon dari Admin</div>
            <div class="feature-desc">Dapatkan tanggapan dan pembaruan langsung dari admin sekolah terkait laporan yang kamu kirimkan.</div>
    </div>
</section>

@if(isset($tanggapan) && $tanggapan->count() > 0)
@php
    $mask = function($str) {
        if (!$str) return '***';
        $parts = explode(' ', $str);
        $maskedParts = array_map(function($part) {
            if (strlen($part) <= 1) return $part;
            return substr($part, 0, 1) . str_repeat('*', min(3, strlen($part) - 1));
        }, $parts);
        return implode(' ', $maskedParts);
    };
@endphp
<!-- ===== TESTIMONIALS ===== -->
<section class="testimonial-section">
    <div class="section-header">
        <span class="section-eyebrow">Tanggapan Pengguna</span>
        <h2 class="section-title">Apa Kata Mereka?</h2>
        <p class="section-desc">Kesan dan pesan dari para pengguna aplikasi terkait kenyamanan dan efektivitas sistem pengaduan kami.</p>
    </div>
    
    <div class="testimonial-grid">
        @foreach($tanggapan as $item)
            @php
                $roleName = 'Warga Sekolah';
                if ($item->user_type === 'App\Models\Siswa') {
                    $roleName = 'Siswa';
                } elseif ($item->user_type === 'App\Models\Guru') {
                    $roleName = 'Guru';
                } elseif ($item->user_type === 'App\Models\Pegawai') {
                    $roleName = 'Pegawai';
                }
            @endphp
            <div class="testimonial-card">
                <i class="bi bi-quote quote-icon"></i>
                <div class="testimonial-quote">
                    "{{ $item->catatan }}"
                </div>
                <div class="testimonial-author">
                    <div class="testimonial-avatar" style="background: linear-gradient(135deg, #64748B, #94A3B8);">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <div>
                        <div class="testimonial-name">Pengguna Terverifikasi</div>
                        <div class="testimonial-role">{{ $roleName }}</div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</section>
@endif

<!-- ===== FOOTER ===== -->
<footer class="site-footer">
    <p>© {{ date('Y') }} <strong style="color:rgba(255,255,255,0.6);">Apss</strong> — Aplikasi Pengaduan Sarana Sekolah. All rights reserved.</p>
</footer>

@endsection
