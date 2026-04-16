<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    .apss-navbar {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 1000;
        padding: 0;
        font-family: 'Plus Jakarta Sans', sans-serif;
        transition: all 0.35s ease;
    }

    /* Transparent on top of hero */
    .apss-navbar.nav-transparent {
        background: transparent;
    }

    /* Solid white when scrolled */
    .apss-navbar.nav-scrolled {
        background: rgba(255, 255, 255, 0.97);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        box-shadow: 0 1px 0 rgba(0,0,0,0.06), 0 4px 20px rgba(0,0,0,0.06);
    }

    .apss-navbar .navbar-inner {
        display: flex;
        align-items: center;
        justify-content: space-between;
        height: 66px;
        padding: 0 32px;
        max-width: 1280px;
        margin: 0 auto;
        gap: 20px;
    }

    /* Brand */
    .apss-navbar .nav-brand {
        display: flex;
        align-items: center;
        gap: 10px;
        text-decoration: none;
        flex-shrink: 0;
    }

    .apss-navbar .nav-brand-icon {
        width: 36px;
        height: 36px;
        background: linear-gradient(135deg, #2563EB, #60A5FA);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 16px;
        box-shadow: 0 4px 10px rgba(37,99,235,0.3);
        flex-shrink: 0;
    }

    .apss-navbar .nav-brand-text {
        font-size: 16px;
        font-weight: 800;
        letter-spacing: -0.4px;
        line-height: 1.1;
        transition: color 0.3s;
    }

    .apss-navbar .nav-brand-sub {
        font-size: 10px;
        font-weight: 500;
        display: block;
        opacity: 0.7;
        letter-spacing: 0;
    }

    .nav-transparent .nav-brand-text { color: #ffffff; }
    .nav-scrolled   .nav-brand-text { color: #0F172A; }

    /* Nav links */
    .apss-navbar .nav-links {
        display: flex;
        align-items: center;
        gap: 4px;
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .apss-navbar .nav-links .nav-link-item a {
        display: flex;
        align-items: center;
        gap: 7px;
        padding: 8px 14px;
        border-radius: 10px;
        font-size: 13.5px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
    }

    .nav-transparent .nav-links .nav-link-item a {
        color: rgba(255,255,255,0.88);
    }
    .nav-transparent .nav-links .nav-link-item a:hover {
        color: #ffffff;
        background: rgba(255,255,255,0.12);
    }

    .nav-scrolled .nav-links .nav-link-item a {
        color: #475569;
    }
    .nav-scrolled .nav-links .nav-link-item a:hover {
        color: #0F172A;
        background: #F8FAFC;
    }

    /* Notif bell */
    .nav-notif-btn {
        width: 40px;
        height: 40px;
        border-radius: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        text-decoration: none;
        position: relative;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
        background: none;
    }

    .nav-transparent .nav-notif-btn {
        color: rgba(255,255,255,0.88);
    }
    .nav-transparent .nav-notif-btn:hover {
        background: rgba(255,255,255,0.12);
        color: white;
    }
    .nav-scrolled .nav-notif-btn {
        color: #64748B;
    }
    .nav-scrolled .nav-notif-btn:hover {
        background: #F1F5F9;
        color: #0F172A;
    }

    .nav-notif-dot {
        position: absolute;
        top: 7px;
        right: 7px;
        width: 8px;
        height: 8px;
        background: #EF4444;
        border-radius: 50%;
        border: 2px solid white;
    }

    .nav-transparent .nav-notif-dot {
        border-color: transparent;
    }

    /* Right group */
    .apss-navbar .nav-right {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-shrink: 0;
    }

    /* Ghost/outline button */
    .nav-btn-ghost {
        display: flex;
        align-items: center;
        gap: 7px;
        padding: 9px 18px;
        border-radius: 11px;
        font-size: 13.5px;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.2s;
        border: 1.5px solid;
        font-family: 'Plus Jakarta Sans', sans-serif;
        cursor: pointer;
        background: none;
    }

    .nav-transparent .nav-btn-ghost {
        color: rgba(255,255,255,0.9);
        border-color: rgba(255,255,255,0.4);
    }
    .nav-transparent .nav-btn-ghost:hover {
        color: white;
        border-color: rgba(255,255,255,0.8);
        background: rgba(255,255,255,0.1);
    }

    .nav-scrolled .nav-btn-ghost {
        color: #374151;
        border-color: #D1D5DB;
    }
    .nav-scrolled .nav-btn-ghost:hover {
        color: #0F172A;
        border-color: #9CA3AF;
        background: #F9FAFB;
    }

    /* Primary button */
    .nav-btn-primary {
        display: flex;
        align-items: center;
        gap: 7px;
        padding: 9px 18px;
        border-radius: 11px;
        font-size: 13.5px;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.2s;
        background: #2563EB;
        color: white;
        border: 1.5px solid #2563EB;
        font-family: 'Plus Jakarta Sans', sans-serif;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(37,99,235,0.3);
    }
    .nav-btn-primary:hover {
        background: #1D4ED8;
        border-color: #1D4ED8;
        color: white;
        box-shadow: 0 6px 16px rgba(37,99,235,0.4);
        transform: translateY(-1px);
        text-decoration: none;
    }

    /* Notif Dropdown */
    .nav-notif-dropdown {
        position: relative;
    }
    
    .nav-notif-menu {
        position: absolute;
        top: calc(100% + 10px);
        right: -10px;
        width: 320px;
        background: white;
        border: 1px solid #E8EDF5;
        border-radius: 14px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.12), 0 2px 8px rgba(0,0,0,0.06);
        padding: 0;
        opacity: 0;
        visibility: hidden;
        transform: translateY(-8px);
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 200;
        overflow: hidden;
    }
    .nav-notif-menu.open {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }
    .nav-notif-header {
        padding: 14px 16px;
        border-bottom: 1px solid #F1F5F9;
        font-size: 14px;
        font-weight: 700;
        color: #0F172A;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .nav-notif-badge {
        background: #EF4444;
        color: white;
        font-size: 11px;
        padding: 2px 6px;
        border-radius: 6px;
    }
    .nav-notif-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 14px 16px;
        text-decoration: none;
        border-bottom: 1px solid #F8FAFC;
        transition: all 0.2s;
    }
    .nav-notif-item:hover {
        background: #F8FAFC;
    }
    .nav-notif-item:last-child {
        border-bottom: none;
    }
    .nav-notif-icon {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: #EFF6FF;
        color: #2563EB;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        flex-shrink: 0;
    }
    .nav-notif-text {
        font-size: 13.5px;
        color: #374151;
        line-height: 1.4;
    }
    .nav-notif-text strong {
        color: #0F172A;
        font-weight: 700;
    }
    .nav-notif-time {
        font-size: 11px;
        color: #94A3B8;
        margin-top: 4px;
        font-weight: 500;
    }
    .nav-notif-empty {
        padding: 30px 20px;
        text-align: center;
        color: #94A3B8;
        font-size: 13px;
    }
    .nav-notif-empty i {
        font-size: 24px;
        display: block;
        margin-bottom: 8px;
        color: #CBD5E1;
    }
    .nav-notif-footer {
        padding: 10px;
        text-align: center;
        background: #F8FAFC;
        border-top: 1px solid #F1F5F9;
    }
    .nav-notif-footer a {
        font-size: 13px;
        font-weight: 600;
        color: #2563EB;
        text-decoration: none;
    }
    .nav-notif-footer a:hover {
        text-decoration: underline;
    }

    /* User dropdown */
    .nav-user-dropdown {
        position: relative;
    }

    .nav-user-trigger {
        display: flex;
        align-items: center;
        gap: 9px;
        padding: 6px 12px 6px 7px;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
        background: none;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .nav-transparent .nav-user-trigger {
        border: 1.5px solid rgba(255,255,255,0.3);
    }
    .nav-transparent .nav-user-trigger:hover {
        background: rgba(255,255,255,0.1);
        border-color: rgba(255,255,255,0.5);
    }

    .nav-scrolled .nav-user-trigger {
        border: 1.5px solid #E2E8F0;
        background: #F8FAFC;
    }
    .nav-scrolled .nav-user-trigger:hover {
        border-color: #CBD5E1;
        background: #F1F5F9;
    }

    .nav-user-avatar {
        width: 32px;
        height: 32px;
        border-radius: 9px;
        background: linear-gradient(135deg, #2563EB, #60A5FA);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 12px;
        flex-shrink: 0;
    }

    .nav-user-name {
        font-size: 13px;
        font-weight: 700;
        transition: color 0.3s;
        white-space: nowrap;
        max-width: 120px;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .nav-transparent .nav-user-name { color: rgba(255,255,255,0.92); }
    .nav-scrolled   .nav-user-name { color: #0F172A; }

    .nav-user-chevron {
        font-size: 12px;
        transition: all 0.2s;
    }
    .nav-transparent .nav-user-chevron { color: rgba(255,255,255,0.7); }
    .nav-scrolled   .nav-user-chevron { color: #94A3B8; }

    .nav-user-trigger.open .nav-user-chevron {
        transform: rotate(180deg);
    }

    /* Dropdown menu */
    .nav-dropdown-menu {
        position: absolute;
        top: calc(100% + 10px);
        right: 0;
        min-width: 200px;
        background: white;
        border: 1px solid #E8EDF5;
        border-radius: 14px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.12), 0 2px 8px rgba(0,0,0,0.06);
        padding: 8px;
        opacity: 0;
        visibility: hidden;
        transform: translateY(-8px);
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 200;
    }

    .nav-dropdown-menu.open {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .nav-dropdown-header {
        padding: 10px 12px 8px;
        border-bottom: 1px solid #F1F5F9;
        margin-bottom: 6px;
    }
    .nav-dropdown-header-name {
        font-size: 13px;
        font-weight: 700;
        color: #0F172A;
    }
    .nav-dropdown-header-role {
        font-size: 11px;
        color: #94A3B8;
        font-weight: 500;
        margin-top: 1px;
    }

    .nav-dropdown-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 12px;
        border-radius: 9px;
        font-size: 13.5px;
        font-weight: 600;
        color: #374151;
        text-decoration: none;
        transition: all 0.15s;
        background: none;
        border: none;
        width: 100%;
        cursor: pointer;
        font-family: 'Plus Jakarta Sans', sans-serif;
        text-align: left;
    }
    .nav-dropdown-item:hover {
        background: #F8FAFC;
        color: #0F172A;
        text-decoration: none;
    }
    .nav-dropdown-item i {
        font-size: 15px;
        width: 18px;
        text-align: center;
        color: #94A3B8;
        flex-shrink: 0;
    }
    .nav-dropdown-item:hover i {
        color: #2563EB;
    }
    .nav-dropdown-item.danger { color: #DC2626; }
    .nav-dropdown-item.danger:hover { background: #FEF2F2; color: #DC2626; }
    .nav-dropdown-item.danger i { color: #FCA5A5; }
    .nav-dropdown-item.danger:hover i { color: #DC2626; }

    .nav-dropdown-divider {
        height: 1px;
        background: #F1F5F9;
        margin: 6px 0;
    }

    /* Mobile toggle */
    .nav-mobile-toggle {
        display: none;
        width: 40px;
        height: 40px;
        border-radius: 11px;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        cursor: pointer;
        transition: all 0.2s;
        border: 1.5px solid;
        background: none;
    }
    .nav-transparent .nav-mobile-toggle {
        color: rgba(255,255,255,0.9);
        border-color: rgba(255,255,255,0.35);
    }
    .nav-transparent .nav-mobile-toggle:hover {
        background: rgba(255,255,255,0.1);
    }
    .nav-scrolled .nav-mobile-toggle {
        color: #374151;
        border-color: #E2E8F0;
    }
    .nav-scrolled .nav-mobile-toggle:hover {
        background: #F8FAFC;
    }

    /* Mobile Menu */
    .nav-mobile-menu {
        display: none;
        position: fixed;
        top: 66px;
        left: 0;
        right: 0;
        background: rgba(255,255,255,0.98);
        backdrop-filter: blur(12px);
        border-bottom: 1px solid #E8EDF5;
        padding: 12px 20px 16px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.08);
        z-index: 999;
    }
    .nav-mobile-menu.open { display: block; }

    .nav-mobile-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 14px;
        border-radius: 11px;
        font-size: 14px;
        font-weight: 600;
        color: #374151;
        text-decoration: none;
        transition: all 0.15s;
        margin-bottom: 3px;
    }
    .nav-mobile-item:hover { background: #F8FAFC; color: #0F172A; text-decoration: none; }
    .nav-mobile-item.primary { background: #EFF6FF; color: #2563EB; }
    .nav-mobile-item.danger { color: #DC2626; }
    .nav-mobile-item.danger:hover { background: #FEF2F2; }

    .nav-mobile-divider { height: 1px; background: #F1F5F9; margin: 8px 0; }

    @media (max-width: 768px) {
        .nav-mobile-toggle { display: flex !important; }
        .nav-links, .nav-right { display: none !important; }
        .apss-navbar .navbar-inner { padding: 0 16px; }
    }

    @media (max-width: 768px) {
        .apss-navbar .navbar-inner { padding: 0 16px; } /* ← sudah ada, tidak perlu diubah */
    }
</style>

<nav class="apss-navbar {{ request()->routeIs('welcome') ? 'nav-transparent' : 'nav-scrolled' }}" id="apssNavbar">
    <div class="navbar-inner">
        <!-- Brand -->
        <a href="{{ route('welcome') }}" class="nav-brand">
            <div class="nav-brand-icon">
                <i class="bi bi-shield-check"></i>
            </div>
            <div class="nav-brand-text">
                Apss
                <span class="nav-brand-sub">Sarana Sekolah</span>
            </div>
        </a>

        <!-- Center Nav Links (only shown when logged in) -->
        @auth('siswa')
        <ul class="nav-links">
            <li class="nav-link-item">
                <a href="{{ route('siswa.dashboard') }}">
                    <i class="bi bi-grid-1x2-fill"></i> Dashboard
                </a>
            </li>
            <li class="nav-link-item">
                <a href="{{ route('siswa.laporan.index') }}" class="{{ request()->routeIs('siswa.laporan.*') ? 'active' : '' }}">
                    <i class="bi bi-file-earmark-text-fill"></i> Buat Laporan
                </a>
            </li>
            <li class="nav-link-item">
                <a href="{{ route('siswa.tanggapan.index') }}" class="{{ request()->routeIs('siswa.tanggapan.*') ? 'active' : '' }}">
                    <i class="bi bi-chat-left-text-fill"></i> Tanggapan
                </a>
            </li>
            <li class="nav-link-item">
                <a href="{{ route('siswa.peminjaman-barang.index') }}" class="{{ request()->routeIs('siswa.peminjaman-barang.*') ? 'active' : '' }}">
                    <i class="bi bi-box-seam-fill"></i> Peminjaman
                </a>
            </li>
        </ul>
        @endauth

        <!-- Right -->
        <div class="nav-right">
            @guest('siswa')
                <a href="{{ route('siswa.login') }}" class="nav-btn-ghost">
                    <i class="bi bi-box-arrow-in-right"></i> Masuk
                </a>
            @endguest

            @auth('siswa')
                <!-- Notifikasi Dropdown -->
                <div class="nav-notif-dropdown">
                    <button class="nav-notif-btn" id="navNotifTrigger" title="Notifikasi">
                        <i class="bi bi-bell-fill"></i>
                        @if(isset($notifKomentar) && $notifKomentar > 0)
                            <span class="nav-notif-dot"></span>
                        @endif
                    </button>
                    
                    <div class="nav-notif-menu" id="navNotifMenu">
                        <div class="nav-notif-header">
                            Notifikasi
                            @if(isset($notifKomentar) && $notifKomentar > 0)
                                <span class="nav-notif-badge">{{ $notifKomentar }} Baru</span>
                            @endif
                        </div>
                        
                        <div style="max-height: 300px; overflow-y: auto;">
                            @if(isset($notifKomentarList) && $notifKomentarList->count() > 0)
                                @foreach($notifKomentarList as $notif)
                                    <a href="{{ route('siswa.laporan.show', $notif->laporan_id) }}" class="nav-notif-item">
                                        <div class="nav-notif-icon">
                                            <i class="bi bi-chat-dots-fill"></i>
                                        </div>
                                        <div>
                                            <div class="nav-notif-text">
                                                <strong>Admin</strong> merespon laporan Anda tentang 
                                                <strong>{{ optional($notif->laporan->kategori)->nama_kategori ?? 'Fasilitas' }}</strong>
                                            </div>
                                            <div class="nav-notif-time">
                                                {{ $notif->created_at->diffForHumans() }}
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            @else
                                <div class="nav-notif-empty">
                                    <i class="bi bi-bell-slash"></i>
                                    Belum ada notifikasi baru
                                </div>
                            @endif
                        </div>
                        
                        <div class="nav-notif-footer">
                            <a href="{{ route('siswa.laporan.index') }}">Buka Semua Laporan</a>
                        </div>
                    </div>
                </div>

                <!-- User Dropdown -->
                <div class="nav-user-dropdown">
                    <button class="nav-user-trigger" id="navUserTrigger">
                        <div class="nav-user-avatar">
                            @if(auth('siswa')->user()->foto)
                                <img src="{{ asset('storage/' . auth('siswa')->user()->foto) }}" alt="Avatar" style="width:100%; height:100%; object-fit:cover; border-radius:9px;">
                            @else
                                {{ strtoupper(substr(auth('siswa')->user()->nama, 0, 2)) }}
                            @endif
                        </div>
                        <span class="nav-user-name">{{ auth('siswa')->user()->nama }}</span>
                        <i class="bi bi-chevron-down nav-user-chevron"></i>
                    </button>

                    <div class="nav-dropdown-menu" id="navDropdownMenu">
                        <div class="nav-dropdown-header">
                            <div class="nav-dropdown-header-name">{{ auth('siswa')->user()->nama }}</div>
                            <div class="nav-dropdown-header-role">Siswa</div>
                        </div>
                        <a href="{{ route('siswa.dashboard') }}" class="nav-dropdown-item">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                        <a href="{{ route('siswa.akun.edit') }}" class="nav-dropdown-item">
                            <i class="bi bi-person-circle"></i> Akun Saya
                        </a>
                        <div class="nav-dropdown-divider"></div>
                        <form action="{{ route('siswa.logout') }}" method="POST" style="margin:0;">
                            @csrf
                            <button type="submit" class="nav-dropdown-item danger">
                                <i class="bi bi-box-arrow-right"></i> Keluar
                            </button>
                        </form>
                    </div>
                </div>
            @endauth
        </div>

        <!-- Mobile Toggle -->
        <button class="nav-mobile-toggle" id="navMobileToggle">
            <i class="bi bi-list"></i>
        </button>
    </div>
</nav>

<!-- Mobile Menu -->
<div class="nav-mobile-menu" id="navMobileMenu">
    @guest('siswa')
        <a href="{{ route('siswa.login') }}" class="nav-mobile-item">
            <i class="bi bi-box-arrow-in-right"></i> Masuk Siswa
        </a>
    @endguest
    @auth('siswa')
        <div style="padding: 10px 14px 8px; display:flex; align-items:center; gap:10px;">
            <div style="width:36px;height:36px;border-radius:9px;background:linear-gradient(135deg,#2563EB,#60A5FA);display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:13px;overflow:hidden;">
                @if(auth('siswa')->user()->foto)
                    <img src="{{ asset('storage/' . auth('siswa')->user()->foto) }}" alt="Avatar" style="width:100%; height:100%; object-fit:cover;">
                @else
                    {{ strtoupper(substr(auth('siswa')->user()->nama, 0, 2)) }}
                @endif
            </div>
            <div>
                <div style="font-size:13px;font-weight:700;color:#0F172A;">{{ auth('siswa')->user()->nama }}</div>
                <div style="font-size:11px;color:#94A3B8;">Siswa</div>
            </div>
        </div>
        <div class="nav-mobile-divider"></div>
        <a href="{{ route('siswa.dashboard') }}" class="nav-mobile-item">
            <i class="bi bi-grid-1x2-fill"></i> Dashboard
        </a>
        <a href="{{ route('siswa.laporan.index') }}" class="nav-mobile-item">
            <i class="bi bi-file-earmark-text-fill"></i> Riwayat Laporan
        </a>
        <a href="{{ route('siswa.tanggapan.index') }}" class="nav-mobile-item">
            <i class="bi bi-chat-left-text-fill"></i> Tanggapan Aplikasi
        </a>
        <a href="{{ route('siswa.peminjaman-barang.index') }}" class="nav-mobile-item {{ request()->routeIs('siswa.peminjaman-barang.*') ? 'primary':'' }}">
            <i class="bi bi-box-seam-fill"></i> Peminjaman Barang
        </a>
        <a href="{{ route('siswa.laporan.create') }}" class="nav-mobile-item primary">
            <i class="bi bi-plus-circle-fill"></i> Buat Laporan
        </a>
        <a href="{{ route('siswa.akun.edit') }}" class="nav-mobile-item">
            <i class="bi bi-person-circle"></i> Akun Saya
        </a>
        <div class="nav-mobile-divider"></div>
        <form action="{{ route('siswa.logout') }}" method="POST" style="margin:0;">
            @csrf
            <button type="submit" class="nav-mobile-item danger" style="width:100%;text-align:left;border:none;cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif;">
                <i class="bi bi-box-arrow-right"></i> Keluar
            </button>
        </form>
    @endauth
</div>

<script>
(function() {
    const navbar       = document.getElementById('apssNavbar');
    const userTrigger  = document.getElementById('navUserTrigger');
    const dropdownMenu = document.getElementById('navDropdownMenu');
    const mobileToggle = document.getElementById('navMobileToggle');
    const mobileMenu   = document.getElementById('navMobileMenu');

    // Scroll behavior
    function onScroll() {
        if (window.scrollY > 20) {
            navbar.classList.remove('nav-transparent');
            navbar.classList.add('nav-scrolled');
        } else {
            navbar.classList.remove('nav-scrolled');
            navbar.classList.add('nav-transparent');
        }
    }
    @if(request()->routeIs('welcome'))
    window.addEventListener('scroll', onScroll);
    onScroll();
    @endif

    // User dropdown
    if (userTrigger && dropdownMenu) {
        userTrigger.addEventListener('click', function(e) {
            e.stopPropagation();
            const isOpen = dropdownMenu.classList.contains('open');
            dropdownMenu.classList.toggle('open', !isOpen);
            userTrigger.classList.toggle('open', !isOpen);
            
            // Close notif dropdown if open
            if(notifMenu) notifMenu.classList.remove('open');
        });
        document.addEventListener('click', function() {
            dropdownMenu.classList.remove('open');
            userTrigger.classList.remove('open');
        });
    }

    // Notif dropdown
    const notifTrigger = document.getElementById('navNotifTrigger');
    const notifMenu    = document.getElementById('navNotifMenu');

    if (notifTrigger && notifMenu) {
        notifTrigger.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const isOpen = notifMenu.classList.contains('open');
            notifMenu.classList.toggle('open', !isOpen);
            
            // Close user dropdown if open
            if(dropdownMenu) dropdownMenu.classList.remove('open');
            if(userTrigger) userTrigger.classList.remove('open');
        });
        document.addEventListener('click', function() {
            notifMenu.classList.remove('open');
        });
        notifMenu.addEventListener('click', function(e) {
            e.stopPropagation(); // prevent closing when clicking inside
        });
    }

    // Mobile toggle
    if (mobileToggle && mobileMenu) {
        mobileToggle.addEventListener('click', function() {
            mobileMenu.classList.toggle('open');
            const icon = mobileToggle.querySelector('i');
            if (mobileMenu.classList.contains('open')) {
                icon.className = 'bi bi-x-lg';
            } else {
                icon.className = 'bi bi-list';
            }
        });
    }
})();
</script>
