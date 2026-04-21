<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    .apss-navbar {
        position: fixed;
        top: 0; left: 0; right: 0;
        z-index: 1000;
        padding: 0;
        font-family: 'Plus Jakarta Sans', sans-serif;
        background: rgba(255, 255, 255, 0.97);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        box-shadow: 0 1px 0 rgba(0,0,0,0.06), 0 4px 20px rgba(0,0,0,0.06);
    }
    .apss-navbar .navbar-inner {
        display: flex; align-items: center; justify-content: space-between;
        height: 66px; padding: 0 32px;
        max-width: 1280px; margin: 0 auto; gap: 20px;
    }
    .apss-navbar .nav-brand {
        display: flex; align-items: center; gap: 10px;
        text-decoration: none; flex-shrink: 0;
    }
    .apss-navbar .nav-brand-icon {
        width: 36px; height: 36px;
        background: linear-gradient(135deg, #2563EB, #60A5FA);
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        color: white; font-size: 16px;
        box-shadow: 0 4px 10px rgba(37,99,235,0.3);
        flex-shrink: 0;
    }
    .apss-navbar .nav-brand-text {
        font-size: 16px; font-weight: 800;
        letter-spacing: -0.4px; line-height: 1.1;
        color: #0F172A;
    }
    .apss-navbar .nav-brand-sub {
        font-size: 10px; font-weight: 500;
        display: block; opacity: 0.6; letter-spacing: 0;
    }
    .apss-navbar .nav-links {
        display: flex; align-items: center;
        gap: 4px; list-style: none; margin: 0; padding: 0;
    }
    .apss-navbar .nav-links .nav-link-item a {
        display: flex; align-items: center; gap: 7px;
        padding: 8px 14px; border-radius: 10px;
        font-size: 13.5px; font-weight: 600;
        text-decoration: none; transition: all 0.2s;
        color: #475569;
    }
    .apss-navbar .nav-links .nav-link-item a:hover,
    .apss-navbar .nav-links .nav-link-item a.active {
        color: #2563EB; background: #EFF6FF;
    }
    .nav-notif-btn {
        width: 40px; height: 40px; border-radius: 11px;
        display: flex; align-items: center; justify-content: center;
        font-size: 18px; text-decoration: none; position: relative;
        transition: all 0.2s; border: none; cursor: pointer; background: none;
        color: #64748B;
    }
    .nav-notif-btn:hover { background: #F1F5F9; color: #0F172A; }
    .nav-notif-dot {
        position: absolute; top: 7px; right: 7px;
        width: 8px; height: 8px;
        background: #EF4444; border-radius: 50%; border: 2px solid white;
    }
    .apss-navbar .nav-right {
        display: flex; align-items: center; gap: 8px; flex-shrink: 0;
    }
    .nav-user-dropdown { position: relative; }
    .nav-user-trigger {
        display: flex; align-items: center; gap: 9px;
        padding: 6px 12px 6px 7px; border-radius: 12px;
        cursor: pointer; transition: all 0.2s;
        border: 1.5px solid #E2E8F0;
        background: #F8FAFC;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
    .nav-user-trigger:hover { border-color: #CBD5E1; background: #F1F5F9; }
    .nav-user-avatar {
        width: 32px; height: 32px; border-radius: 9px;
        background: linear-gradient(135deg, #2563EB, #60A5FA);
        display: flex; align-items: center; justify-content: center;
        color: white; font-weight: 700; font-size: 12px; flex-shrink: 0;
    }
    .nav-user-name {
        font-size: 13px; font-weight: 700; color: #0F172A;
        white-space: nowrap; max-width: 120px;
        overflow: hidden; text-overflow: ellipsis;
    }
    .nav-user-chevron { font-size: 12px; color: #94A3B8; transition: all 0.2s; }
    .nav-user-trigger.open .nav-user-chevron { transform: rotate(180deg); }
    .nav-dropdown-menu {
        position: absolute; top: calc(100% + 10px); right: 0;
        min-width: 200px; background: white;
        border: 1px solid #E8EDF5; border-radius: 14px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.12), 0 2px 8px rgba(0,0,0,0.06);
        padding: 8px;
        opacity: 0; visibility: hidden;
        transform: translateY(-8px);
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); z-index: 200;
    }
    .nav-dropdown-menu.open { opacity: 1; visibility: visible; transform: translateY(0); }
    .nav-dropdown-header { padding: 10px 12px 8px; border-bottom: 1px solid #F1F5F9; margin-bottom: 6px; }
    .nav-dropdown-header-name { font-size: 13px; font-weight: 700; color: #0F172A; }
    .nav-dropdown-header-role { font-size: 11px; color: #94A3B8; font-weight: 500; margin-top: 1px; }
    .nav-dropdown-item {
        display: flex; align-items: center; gap: 10px;
        padding: 10px 12px; border-radius: 9px;
        font-size: 13.5px; font-weight: 600; color: #374151;
        text-decoration: none; transition: all 0.15s;
        background: none; border: none; width: 100%;
        cursor: pointer; font-family: 'Plus Jakarta Sans', sans-serif; text-align: left;
    }
    .nav-dropdown-item:hover { background: #F8FAFC; color: #0F172A; text-decoration: none; }
    .nav-dropdown-item i { font-size: 15px; width: 18px; text-align: center; color: #94A3B8; flex-shrink: 0; }
    .nav-dropdown-item:hover i { color: #2563EB; }
    .nav-dropdown-item.danger { color: #DC2626; }
    .nav-dropdown-item.danger:hover { background: #FEF2F2; color: #DC2626; }
    .nav-dropdown-item.danger i { color: #FCA5A5; }
    .nav-dropdown-item.danger:hover i { color: #DC2626; }
    .nav-dropdown-divider { height: 1px; background: #F1F5F9; margin: 6px 0; }
    .nav-mobile-toggle {
        display: none; width: 40px; height: 40px; border-radius: 11px;
        align-items: center; justify-content: center; font-size: 20px;
        cursor: pointer; transition: all 0.2s;
        border: 1.5px solid #E2E8F0; background: none; color: #374151;
    }
    .nav-mobile-toggle:hover { background: #F8FAFC; }
    .nav-mobile-menu {
        display: none; position: fixed; top: 66px; left: 0; right: 0;
        background: rgba(255,255,255,0.98); backdrop-filter: blur(12px);
        border-bottom: 1px solid #E8EDF5;
        padding: 12px 20px 16px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.08); z-index: 999;
    }
    .nav-mobile-menu.open { display: block; }
    .nav-mobile-item {
        display: flex; align-items: center; gap: 10px;
        padding: 12px 14px; border-radius: 11px;
        font-size: 14px; font-weight: 600; color: #374151;
        text-decoration: none; transition: all 0.15s; margin-bottom: 3px;
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
</style>

<nav class="apss-navbar" id="apssNavbar">
    <div class="navbar-inner">
        <a href="{{ route('guru.dashboard') }}" class="nav-brand">
            <div class="nav-brand-icon" style="background: transparent; box-shadow: none;">
                <img src="{{ asset('images/logosmk_transparent.png') }}" alt="Logo" style="width: 100%; height: 100%; object-fit: contain;">
            </div>
            <div class="nav-brand-text">
                Asps
                <span class="nav-brand-sub">Portal Guru</span>
            </div>
        </a>

        <ul class="nav-links">
            <li class="nav-link-item">
                <a href="{{ route('guru.dashboard') }}" class="{{ request()->routeIs('guru.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2-fill"></i> Dashboard
                </a>
            </li>
            <li class="nav-link-item">
                <a href="{{ route('guru.laporan.index') }}" class="{{ request()->routeIs('guru.laporan.*') ? 'active' : '' }}">
                    <i class="bi bi-file-earmark-text-fill"></i> Buat Laporan
                </a>
            </li>
            <li class="nav-link-item">
                <a href="{{ route('guru.peminjaman-barang.index') }}" class="{{ request()->routeIs('guru.peminjaman-barang.*') ? 'active' : '' }}">
                    <i class="bi bi-box-seam-fill"></i> Buat Peminjaman
                </a>
            </li> 
            <li class="nav-link-item">
                <a href="{{ route('guru.tanggapan.index') }}" class="{{ request()->routeIs('guru.tanggapan.*') ? 'active' : '' }}">
                    <i class="bi bi-chat-left-text-fill"></i> Buat Tanggapan
                </a>
            </li>
        </ul>

        <div class="nav-right">
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
                                <a href="{{ route('guru.laporan.show', $notif->laporan_id) }}" class="nav-notif-item">
                                    <div class="nav-notif-icon">
                                        <i class="bi bi-chat-dots-fill"></i>
                                    </div>
                                    <div>
                                        <div class="nav-notif-text">
                                            <strong>Admin</strong> merespon laporan Anda tentang 
                                            <strong>{{ optional($notif->laporan->kategoriAspirasi)->nama_kategori ?? 'Fasilitas' }}</strong>
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
                        <a href="{{ route('guru.laporan.index') }}">Buka Semua Laporan</a>
                    </div>
                </div>
            </div>

            <div class="nav-user-dropdown">
                <button class="nav-user-trigger" id="navUserTrigger">
                    <div class="nav-user-avatar">
                        @if(auth('guru')->user()->foto)
                            <img src="{{ asset('storage/' . auth('guru')->user()->foto) }}" alt="Avatar" style="width:100%; height:100%; object-fit:cover; border-radius:9px;">
                        @else
                            {{ strtoupper(substr(auth('guru')->user()->nama, 0, 2)) }}
                        @endif
                    </div>
                    <span class="nav-user-name">{{ auth('guru')->user()->nama }}</span>
                    <i class="bi bi-chevron-down nav-user-chevron"></i>
                </button>
                <div class="nav-dropdown-menu" id="navDropdownMenu">
                    <div class="nav-dropdown-header">
                        <div class="nav-dropdown-header-name">{{ auth('guru')->user()->nama }}</div>
                        <div class="nav-dropdown-header-role">Guru · {{ auth('guru')->user()->jabatan ?? 'Tenaga Pengajar' }}</div>
                    </div>
                    <a href="{{ route('guru.dashboard') }}" class="nav-dropdown-item">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                    <a href="{{ route('guru.akun') }}" class="nav-dropdown-item">
                        <i class="bi bi-person-circle"></i> Akun Saya
                    </a>
                    <div class="nav-dropdown-divider"></div>
                    <form action="{{ route('guru.logout') }}" method="POST" style="margin:0;">
                        @csrf
                        <button type="submit" class="nav-dropdown-item danger">
                            <i class="bi bi-box-arrow-right"></i> Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <button class="nav-mobile-toggle" id="navMobileToggle">
            <i class="bi bi-list"></i>
        </button>
    </div>
</nav>

<div class="nav-mobile-menu" id="navMobileMenu">
    <div style="padding: 10px 14px 8px; display:flex; align-items:center; gap:10px;">
        <div style="width:36px;height:36px;border-radius:9px;background:linear-gradient(135deg,#2563EB,#60A5FA);display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:13px;overflow:hidden;">
            @if(auth('guru')->user()->foto)
                <img src="{{ asset('storage/' . auth('guru')->user()->foto) }}" alt="Avatar" style="width:100%; height:100%; object-fit:cover;">
            @else
                {{ strtoupper(substr(auth('guru')->user()->nama, 0, 2)) }}
            @endif
        </div>
        <div>
            <div style="font-size:13px;font-weight:700;color:#0F172A;">{{ auth('guru')->user()->nama }}</div>
            <div style="font-size:11px;color:#94A3B8;">Guru</div>
        </div>
    </div>
    <div class="nav-mobile-divider"></div>
    <a href="{{ route('guru.dashboard') }}" class="nav-mobile-item">
        <i class="bi bi-grid-1x2-fill"></i> Dashboard
    </a>
    <a href="{{ route('guru.laporan.index') }}" class="nav-mobile-item">
        <i class="bi bi-file-earmark-text-fill"></i> Laporan
    </a>
    <a href="{{ route('guru.tanggapan.index') }}" class="nav-mobile-item">
        <i class="bi bi-chat-left-text-fill"></i> Tanggapan Aplikasi
    </a>
    <a href="{{ route('guru.peminjaman-barang.index') }}" class="nav-mobile-item {{ request()->routeIs('guru.peminjaman-barang.*') ? 'primary':'' }}">
        <i class="bi bi-box-seam-fill"></i> Peminjaman Barang
    </a>
    <a href="{{ route('guru.akun') }}" class="nav-mobile-item">
        <i class="bi bi-person-circle"></i> Akun Saya
    </a>
    <div class="nav-mobile-divider"></div>
    <form action="{{ route('guru.logout') }}" method="POST" style="margin:0;">
        @csrf
        <button type="submit" class="nav-mobile-item danger" style="width:100%;text-align:left;border:none;cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif;">
            <i class="bi bi-box-arrow-right"></i> Keluar
        </button>
    </form>
</div>

<script>
(function() {
    const userTrigger  = document.getElementById('navUserTrigger');
    const dropdownMenu = document.getElementById('navDropdownMenu');
    const mobileToggle = document.getElementById('navMobileToggle');
    const mobileMenu   = document.getElementById('navMobileMenu');

    const notifTrigger = document.getElementById('navNotifTrigger');
    const notifMenu    = document.getElementById('navNotifMenu');

    if (userTrigger && dropdownMenu) {
        userTrigger.addEventListener('click', function(e) {
            e.stopPropagation();
            if (notifMenu && notifMenu.classList.contains('open')) {
                notifMenu.classList.remove('open');
            }
            const isOpen = dropdownMenu.classList.contains('open');
            dropdownMenu.classList.toggle('open', !isOpen);
            userTrigger.classList.toggle('open', !isOpen);
        });
    }

    if (notifTrigger && notifMenu) {
        notifTrigger.addEventListener('click', function(e) {
            e.stopPropagation();
            if (dropdownMenu && dropdownMenu.classList.contains('open')) {
                dropdownMenu.classList.remove('open');
                userTrigger.classList.remove('open');
            }
            notifMenu.classList.toggle('open');
        });
    }

    document.addEventListener('click', function() {
        if(dropdownMenu) {
            dropdownMenu.classList.remove('open');
            userTrigger.classList.remove('open');
        }
        if(notifMenu) {
            notifMenu.classList.remove('open');
        }
    });

    if (mobileToggle && mobileMenu) {
        mobileToggle.addEventListener('click', function() {
            mobileMenu.classList.toggle('open');
            const icon = mobileToggle.querySelector('i');
            icon.className = mobileMenu.classList.contains('open') ? 'bi bi-x-lg' : 'bi bi-list';
        });
    }
})();
</script>
