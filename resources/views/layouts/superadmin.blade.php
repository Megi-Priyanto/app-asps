@extends('layouts.main')

@push('css')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap');

    :root {
        --sidebar-width: 265px;
        --sidebar-collapsed-width: 72px;
        --topbar-height: 68px;
        --primary: #2563EB;
        --primary-light: #EFF6FF;
        --primary-dark: #1D4ED8;
        --sidebar-bg: #ffffff;
        --sidebar-border: #F1F5F9;
        --sidebar-text: #64748B;
        --sidebar-text-active: #2563EB;
        --sidebar-hover: #F8FAFC;
        --sidebar-active-bg: #EFF6FF;
        --topbar-bg: #ffffff;
        --body-bg: #F4F6FB;
        --border: #E8EDF5;
        --text-primary: #0F172A;
        --text-secondary: #64748B;
        --text-muted: #94A3B8;
        --danger: #EF4444;
        --success: #10B981;
        --warning: #F59E0B;
        --radius: 14px;
        --radius-sm: 10px;
        --shadow: 0 1px 3px rgba(0,0,0,0.04), 0 4px 16px rgba(0,0,0,0.04);
        --shadow-md: 0 4px 12px rgba(0,0,0,0.08), 0 8px 24px rgba(0,0,0,0.04);
        --transition: 0.28s cubic-bezier(0.4, 0, 0.2, 1);
    }

    * { box-sizing: border-box; }

    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background: var(--body-bg);
        color: var(--text-primary);
        margin: 0;
        padding: 0;
        font-size: 14px;
    }

    /* ===== SIDEBAR ===== */
    .admin-sidebar {
        position: fixed;
        top: 0;
        left: 0;
        width: var(--sidebar-width);
        height: 100vh;
        background: var(--sidebar-bg);
        z-index: 1040;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        transition: width var(--transition);
        border-right: 1px solid var(--sidebar-border);
        box-shadow: 4px 0 24px rgba(0,0,0,0.03);
    }

    .admin-sidebar.collapsed { width: var(--sidebar-collapsed-width); }

    /* Brand */
    .sidebar-brand {
        padding: 0 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        height: var(--topbar-height);
        min-height: var(--topbar-height);
        border-bottom: 1px solid var(--sidebar-border);
        overflow: hidden;
    }

    .sidebar-brand-left {
        display: flex;
        align-items: center;
        gap: 11px;
        overflow: hidden;
        flex: 1;
    }

    .sidebar-brand-icon {
        width: 36px;
        height: 36px;
        min-width: 36px;
        background: linear-gradient(135deg, #2563EB 0%, #60A5FA 100%);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 17px;
        box-shadow: 0 4px 10px rgba(37,99,235,0.25);
    }

    .sidebar-brand-text {
        font-size: 16px;
        font-weight: 800;
        color: var(--text-primary);
        letter-spacing: -0.4px;
        white-space: nowrap;
        opacity: 1;
        transition: opacity var(--transition);
        line-height: 1.2;
    }

    .sidebar-brand-sub {
        font-size: 10.5px;
        font-weight: 500;
        color: var(--text-muted);
        display: block;
        margin-top: 1px;
        letter-spacing: 0;
    }

    .admin-sidebar.collapsed .sidebar-brand-text { opacity: 0; width: 0; pointer-events: none; }
    .admin-sidebar.collapsed .sidebar-brand { justify-content: center; padding: 0; }
    .admin-sidebar.collapsed .sidebar-brand-left { width: 100%; justify-content: center; gap: 0; }
    .admin-sidebar.collapsed .sidebar-toggle-btn { display: none; } 
    .admin-sidebar.collapsed .sidebar-brand-icon { cursor: pointer; transition: all var(--transition); }
    .admin-sidebar.collapsed .sidebar-brand-icon:hover { transform: scale(1.05); box-shadow: 0 6px 14px rgba(37,99,235,0.3); } 

    /* Toggle Btn */
    .sidebar-toggle-btn {
        width: 34px;
        height: 34px;
        min-width: 34px;
        background: var(--sidebar-hover);
        border: 1px solid var(--border);
        border-radius: 9px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 4.5px;
        cursor: pointer;
        transition: all 0.2s;
        padding: 0;
    }
    .sidebar-toggle-btn:hover { background: var(--primary-light); border-color: #BFDBFE; }
    .sidebar-toggle-btn span {
        display: block;
        width: 16px;
        height: 1.8px;
        background: var(--text-secondary);
        border-radius: 2px;
        transition: all 0.3s ease;
        transform-origin: center;
    }
    .sidebar-toggle-btn:hover span { background: var(--primary); }
    .admin-sidebar.collapsed .sidebar-toggle-btn span:nth-child(1) { transform: translateY(6.3px) rotate(45deg); background: var(--primary); }
    .admin-sidebar.collapsed .sidebar-toggle-btn span:nth-child(2) { opacity: 0; transform: scaleX(0); }
    .admin-sidebar.collapsed .sidebar-toggle-btn span:nth-child(3) { transform: translateY(-6.3px) rotate(-45deg); background: var(--primary); }

    /* User Block */
    .sidebar-user-block {
        padding: 12px 14px;
        margin: 12px 12px 6px;
        background: var(--primary-light);
        border-radius: 12px;
        display: flex;
        align-items: center;
        gap: 11px;
        overflow: hidden;
        transition: all var(--transition);
        border: 1px solid #DBEAFE;
    }
    .admin-sidebar.collapsed .sidebar-user-block {
        padding: 0;
        background: transparent;
        border: none;
        justify-content: center;
        margin: 20px 0 10px;
        box-shadow: none;
        gap: 0;
    }
    .admin-sidebar.collapsed .sidebar-user-info { display: none; }

    .sidebar-user-avatar {
        width: 36px;
        height: 36px;
        min-width: 36px;
        border-radius: 10px;
        background: linear-gradient(135deg, #2563EB 0%, #60A5FA 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 13px;
    }
    .sidebar-user-info { flex: 1; min-width: 0; overflow: hidden; opacity: 1; transition: opacity var(--transition); }
    .admin-sidebar.collapsed .sidebar-user-info { opacity: 0; width: 0; pointer-events: none; }
    .sidebar-user-name { font-size: 13px; font-weight: 700; color: var(--primary-dark); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .sidebar-user-role { font-size: 11px; color: #3B82F6; font-weight: 500; }

    /* Nav */
    .sidebar-nav {
        flex: 1;
        overflow-y: auto;
        overflow-x: hidden;
        padding: 6px 12px 12px;
    }
    .sidebar-nav::-webkit-scrollbar { width: 3px; }
    .sidebar-nav::-webkit-scrollbar-track { background: transparent; }
    .sidebar-nav::-webkit-scrollbar-thumb { background: var(--border); border-radius: 4px; }

    .sidebar-section-label {
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
        color: var(--text-muted);
        padding: 14px 10px 7px;
        white-space: nowrap;
        overflow: hidden;
        transition: opacity var(--transition);
    }
    .admin-sidebar.collapsed .sidebar-section-label { opacity: 0; padding: 14px 0 7px; }

    .sidebar-item {
        display: flex;
        align-items: center;
        gap: 11px;
        padding: 11px 13px;
        border-radius: 11px;
        color: var(--sidebar-text);
        text-decoration: none;
        font-size: 13.5px;
        font-weight: 500;
        transition: all 0.2s;
        margin-bottom: 2px;
        white-space: nowrap;
        overflow: hidden;
        position: relative;
    }
    .admin-sidebar.collapsed .sidebar-item { padding: 11px; justify-content: center; }
    .sidebar-item:hover { background: var(--sidebar-hover); color: var(--text-primary); text-decoration: none; }
    .sidebar-item:hover i { color: var(--text-primary); }
    .sidebar-item.active { background: var(--sidebar-active-bg); color: var(--sidebar-text-active); font-weight: 700; }
    .sidebar-item.active i { color: var(--primary); }
    .sidebar-item.active::before {
        content: '';
        position: absolute;
        left: -12px;
        top: 50%;
        transform: translateY(-50%);
        width: 3.5px;
        height: 55%;
        background: var(--primary);
        border-radius: 0 4px 4px 0;
        transition: all 0.2s;
    }
    .admin-sidebar.collapsed .sidebar-item.active::before { left: -12px; height: 60%; }
    .sidebar-item i { font-size: 17px; min-width: 20px; text-align: center; flex-shrink: 0; color: var(--text-muted); transition: color 0.2s; }
    .sidebar-item-text { opacity: 1; transition: opacity var(--transition); overflow: hidden; }
    .admin-sidebar.collapsed .sidebar-item-text { opacity: 0; width: 0; pointer-events: none; }

    .sidebar-badge {
        margin-left: auto;
        background: var(--danger);
        color: white;
        font-size: 10px;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 10px;
        white-space: nowrap;
        flex-shrink: 0;
        transition: opacity var(--transition);
    }
    .admin-sidebar.collapsed .sidebar-badge { position: absolute; top: 5px; right: 5px; padding: 2px 5px; font-size: 9px; }

    /* Tooltip */
    .admin-sidebar.collapsed .sidebar-item::after {
        content: attr(data-tooltip);
        position: absolute;
        left: calc(100% + 14px);
        top: 50%;
        transform: translateY(-50%);
        background: #1E293B;
        color: #F1F5F9;
        font-size: 12px;
        font-weight: 600;
        padding: 6px 12px;
        border-radius: 8px;
        white-space: nowrap;
        pointer-events: none;
        opacity: 0;
        transition: opacity 0.2s;
        z-index: 9999;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }
    .admin-sidebar.collapsed .sidebar-item:hover::after { opacity: 1; }

    /* Sidebar Footer */
    .sidebar-footer {
        padding: 12px;
        border-top: 1px solid var(--sidebar-border);
    }
    .sidebar-logout-btn {
        display: flex;
        align-items: center;
        gap: 11px;
        padding: 11px 13px;
        border-radius: 11px;
        color: #DC2626;
        text-decoration: none;
        font-size: 13.5px;
        font-weight: 600;
        transition: all 0.2s;
        white-space: nowrap;
        overflow: hidden;
        background: none;
        border: none;
        width: 100%;
        cursor: pointer;
        font-family: 'Plus Jakarta Sans', sans-serif;
        position: relative;
    }
    .admin-sidebar.collapsed .sidebar-logout-btn { justify-content: center; padding: 11px; }
    .sidebar-logout-btn:hover { background: #FEF2F2; }
    .sidebar-logout-btn i { font-size: 17px; min-width: 20px; text-align: center; flex-shrink: 0; }
    .sidebar-logout-text { opacity: 1; transition: opacity var(--transition); }
    .admin-sidebar.collapsed .sidebar-logout-text { opacity: 0; width: 0; pointer-events: none; }
    .admin-sidebar.collapsed .sidebar-logout-btn::after {
        content: 'Keluar';
        position: absolute;
        left: calc(100% + 14px);
        top: 50%;
        transform: translateY(-50%);
        background: #1E293B;
        color: #F1F5F9;
        font-size: 12px;
        font-weight: 600;
        padding: 6px 12px;
        border-radius: 8px;
        white-space: nowrap;
        pointer-events: none;
        opacity: 0;
        transition: opacity 0.2s;
        z-index: 9999;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }
    .admin-sidebar.collapsed .sidebar-logout-btn:hover::after { opacity: 1; }

    /* ===== TOPBAR ===== */
    .admin-topbar {
        position: fixed;
        top: 0;
        left: var(--sidebar-width);
        right: 0;
        height: var(--topbar-height);
        background: var(--topbar-bg);
        z-index: 1030;
        display: flex;
        align-items: center;
        padding: 0 28px;
        gap: 16px;
        border-bottom: 1px solid var(--border);
        transition: left var(--transition);
    }
    body.sidebar-collapsed .admin-topbar { left: var(--sidebar-collapsed-width); }

    .topbar-title-area { flex: 1; min-width: 0; }
    .topbar-page-label { font-size: 11px; color: var(--text-muted); font-weight: 500; margin-bottom: 2px; }
    .topbar-page-title {
        font-size: 1.15rem;
        font-weight: 700;
        color: var(--text-primary);
        letter-spacing: -0.2px;
        line-height: 1.4;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .topbar-right { display: flex; align-items: center; gap: 6px; }

    .topbar-icon-btn {
        width: 40px;
        height: 40px;
        border-radius: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-secondary);
        font-size: 19px;
        text-decoration: none;
        position: relative;
        transition: all 0.2s;
        background: transparent;
        border: 1px solid transparent;
        cursor: pointer;
    }
    .topbar-icon-btn:hover { background: var(--sidebar-hover); border-color: var(--border); color: var(--primary); }
    .topbar-notif-badge { position: absolute; top: 8px; right: 8px; width: 8px; height: 8px; background: var(--danger); border-radius: 50%; border: 2px solid white; }
    .topbar-divider { width: 1px; height: 28px; background: var(--border); margin: 0 4px; }

    .topbar-user-pill {
        display: flex;
        align-items: center;
        gap: 9px;
        padding: 6px 14px 6px 8px;
        border-radius: 12px;
        background: var(--sidebar-hover);
        border: 1px solid var(--border);
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
    }
    .topbar-user-pill:hover { border-color: #BFDBFE; background: var(--primary-light); text-decoration: none; }
    .topbar-user-avatar {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: linear-gradient(135deg, #2563EB 0%, #60A5FA 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 12px;
    }
    .topbar-user-info-name { font-size: 13px; font-weight: 700; color: var(--text-primary); line-height: 1.2; }
    
    .topbar-user-dropdown { position: relative; }
    .topbar-user-dropdown-menu {
        position: absolute; top: calc(100% + 10px); right: 0; min-width: 200px;
        background: white; border: 1px solid #E8EDF5; border-radius: 14px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.12), 0 2px 8px rgba(0,0,0,0.06);
        padding: 8px; opacity: 0; visibility: hidden; transform: translateY(-8px);
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); z-index: 1050; text-align: left;
    }
    .topbar-user-dropdown-menu.open { opacity: 1; visibility: visible; transform: translateY(0); }
    .topbar-dropdown-header { padding: 10px 12px 8px; border-bottom: 1px solid #F1F5F9; margin-bottom: 6px; }
    .topbar-dropdown-name { font-size: 13px; font-weight: 700; color: #0F172A; }
    .topbar-dropdown-role { font-size: 11px; color: #94A3B8; font-weight: 500; margin-top: 1px; }
    .topbar-dropdown-item {
        display: flex; align-items: center; gap: 10px; padding: 10px 12px; border-radius: 9px;
        font-size: 13.5px; font-weight: 600; color: #374151; text-decoration: none; transition: all 0.15s;
        background: none; border: none; width: 100%; cursor: pointer; text-align: left; font-family: 'Plus Jakarta Sans', sans-serif;
    }
    .topbar-dropdown-item:hover { background: #F8FAFC; color: #0F172A; text-decoration: none; }
    .topbar-dropdown-item i { font-size: 15px; width: 18px; text-align: center; color: #94A3B8; flex-shrink: 0; }
    .topbar-dropdown-item:hover i { color: var(--primary); }
    .topbar-dropdown-item.danger { color: #DC2626; }
    .topbar-dropdown-item.danger:hover { background: #FEF2F2; color: #DC2626; }
    .topbar-dropdown-item.danger i { color: #FCA5A5; }
    .topbar-dropdown-item.danger:hover i { color: #DC2626; }
    .topbar-dropdown-divider { height: 1px; background: #F1F5F9; margin: 6px 0; }

    /* ===== CONTENT ===== */
    .admin-content {
        margin-left: var(--sidebar-width);
        padding-top: var(--topbar-height);
        min-height: 100vh;
        transition: margin-left var(--transition);
    }
    body.sidebar-collapsed .admin-content { margin-left: var(--sidebar-collapsed-width); }
    .admin-content-inner { padding: 28px 32px; }

    /* ===== CARDS ===== */
    .card {
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        background: #fff;
        transition: box-shadow 0.3s;
    }
    .card-header {
        border-bottom: 1px solid var(--border);
        padding: 18px 22px;
        font-weight: 700;
        font-size: 14.5px;
        background: transparent;
        color: var(--text-primary);
        border-radius: var(--radius) var(--radius) 0 0;
    }
    .card-footer {
        border-top: 1px solid var(--border);
        padding: 14px 22px;
        background: transparent;
        border-radius: 0 0 var(--radius) var(--radius);
    }

    /* ===== STAT CARDS ===== */
    .stat-card {
        background: white;
        border-radius: var(--radius);
        padding: 22px 24px;
        box-shadow: var(--shadow);
        border: 1px solid var(--border);
        display: flex;
        align-items: center;
        gap: 18px;
        transition: all 0.25s;
    }
    .stat-card:hover { box-shadow: var(--shadow-md); transform: translateY(-2px); }
    .stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        flex-shrink: 0;
    }
    .stat-icon.blue   { background: #EFF6FF; color: #2563EB; }
    .stat-icon.green  { background: #ECFDF5; color: #10B981; }
    .stat-icon.yellow { background: #FFFBEB; color: #F59E0B; }
    .stat-icon.red    { background: #FEF2F2; color: #EF4444; }
    .stat-icon.purple { background: #F5F3FF; color: #7C3AED; }
    .stat-label { font-size: 12.5px; color: var(--text-muted); font-weight: 500; margin-bottom: 6px; }
    .stat-value { font-size: 26px; font-weight: 800; color: var(--text-primary); letter-spacing: -0.5px; line-height: 1; }

    /* ===== TABLES ===== */
    .table { font-size: 13.5px; margin: 0; }
    .table thead tr th {
        background: #F8FAFC;
        color: var(--text-muted);
        font-size: 11.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 12px 20px;
        border-bottom: 1px solid var(--border);
        border-top: none;
        white-space: nowrap;
    }
    .table tbody tr td {
        padding: 14px 20px;
        border-bottom: 1px solid #F8FAFC;
        color: var(--text-primary);
        vertical-align: middle;
    }
    .table tbody tr:last-child td { border-bottom: none; }
    .table tbody tr:hover td { background: #FAFCFF; }

    /* ===== BUTTONS ===== */
    .btn { font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 600; border-radius: var(--radius-sm); transition: all 0.2s; }
    .btn-primary { background: var(--primary); border-color: var(--primary); }
    .btn-primary:hover { background: var(--primary-dark); border-color: var(--primary-dark); box-shadow: 0 4px 12px rgba(37,99,235,0.3); }
    .btn-sm { font-size: 12.5px; padding: 6px 14px; border-radius: 8px; }
    .btn-danger { background: var(--danger); border-color: var(--danger); }
    .btn-danger:hover { background: #DC2626; border-color: #DC2626; box-shadow: 0 4px 12px rgba(239,68,68,0.25); }

    /* ===== FORMS ===== */
    .form-control, .form-select {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 13.5px;
        border-radius: 10px;
        border: 1px solid var(--border);
        color: var(--text-primary);
        padding: 9px 14px;
        transition: all 0.2s;
    }
    .form-control:focus, .form-select:focus { border-color: #93C5FD; box-shadow: 0 0 0 3px rgba(37,99,235,0.1); }
    .form-label { font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px; }

    /* ===== ALERTS ===== */
    .alert { border-radius: var(--radius-sm); font-size: 13.5px; font-weight: 500; border: none; padding: 14px 18px; }
    .alert-success { background: #ECFDF5; color: #065F46; }
    .alert-danger { background: #FEF2F2; color: #991B1B; }

    /* ===== SUBMENU ===== */
    .sidebar-submenu {
        padding-left: 10px;
        margin-top: 2px;
        overflow: hidden;
    }
    .admin-sidebar.collapsed .sidebar-submenu { display: none !important; }

    .sidebar-subitem {
        display: flex;
        align-items: center;
        gap: 9px;
        padding: 9px 13px;
        border-radius: 10px;
        color: var(--sidebar-text);
        text-decoration: none;
        font-size: 13px;
        font-weight: 500;
        transition: all 0.2s;
        margin-bottom: 2px;
        white-space: nowrap;
        overflow: hidden;
        border-left: 2px solid var(--border);
        margin-left: 8px;
    }
    .sidebar-subitem:hover { background: var(--sidebar-hover); color: var(--text-primary); border-left-color: #93C5FD; text-decoration: none; }
    .sidebar-subitem.active { background: var(--sidebar-active-bg); color: var(--sidebar-text-active); font-weight: 700; border-left-color: var(--primary); }
    .sidebar-subitem i { font-size: 15px; min-width: 18px; text-align: center; flex-shrink: 0; color: var(--text-muted); }
    .sidebar-subitem.active i { color: var(--primary); }

    .sidebar-arrow { color: var(--text-muted); }
    .admin-sidebar.collapsed .sidebar-arrow { display: none; }

    /* Overlay */
    .sidebar-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.4);
        backdrop-filter: blur(3px);
        z-index: 1039;
    }

    /* Mobile toggle */
    .topbar-mobile-toggle {
        display: none;
        width: 40px;
        height: 40px;
        background: none;
        border: 1px solid var(--border);
        border-radius: 11px;
        align-items: center;
        justify-content: center;
        color: var(--text-secondary);
        font-size: 20px;
        cursor: pointer;
        transition: all 0.2s;
    }
    .topbar-mobile-toggle:hover { background: var(--sidebar-hover); color: var(--primary); }

    @media (max-width: 992px) {
        .admin-sidebar { transform: translateX(-100%); width: var(--sidebar-width) !important; }
        .admin-sidebar.mobile-open { transform: translateX(0); }
        .admin-content { margin-left: 0 !important; }
        .admin-topbar { left: 0 !important; padding: 0 16px; }
        .sidebar-overlay.open { display: block; }
        .topbar-mobile-toggle { display: flex !important; }
        .admin-content-inner { padding: 20px 16px; }
        .topbar-user-info-name, .topbar-user-info-role { display: none; }
        .topbar-user-pill { padding: 6px; }
    }
</style>
@endpush

@section('body')

<aside class="admin-sidebar" id="adminSidebar">
    <!-- Brand -->
    <div class="sidebar-brand">
        <div class="sidebar-brand-left">
            <div class="sidebar-brand-icon">
                <i class="bi bi-shield-lock-fill"></i>
            </div>
            <div class="sidebar-brand-text">
                Asps
                <span class="sidebar-brand-sub">Super Manager</span>
            </div>
        </div>
        <button class="sidebar-toggle-btn" id="sidebarToggleBtn" title="Toggle Sidebar">
            <span></span><span></span><span></span>
        </button>
    </div>


    <!-- Navigation -->
    <nav class="sidebar-nav">
        <div class="sidebar-section-label">Main Menu</div>

        <a href="{{ route('superadmin.dashboard') }}"
            data-tooltip="Dashboard"
            data-page-title="Dashboard"
            class="sidebar-item {{ request()->is('superadmin/dasboard') || request()->is('superadmin/dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2-fill"></i>
            <span class="sidebar-item-text">Dashboard</span>
        </a>


        <a href="{{ route('superadmin.lokasi.index') }}"
            data-tooltip="Lokasi Penugasan"
            data-page-title="Lokasi Penugasan"
            class="sidebar-item {{ request()->routeIs('superadmin.lokasi.*') ? 'active' : '' }}">
            <i class="bi bi-geo-alt-fill"></i>
            <span class="sidebar-item-text">Lokasi Penugasan</span>
        </a>

        <a href="{{ route('superadmin.tanggapan-pengguna.index') }}"
            data-tooltip="Tanggapan Pengguna"
            data-page-title="Tanggapan Pengguna"
            class="sidebar-item {{ request()->routeIs('superadmin.tanggapan-pengguna.*') ? 'active' : '' }}">
            <i class="bi bi-chat-left-quote-fill"></i>
            <span class="sidebar-item-text">Tanggapan Pengguna</span>
        </a>

        <div class="sidebar-section-label">Sarpras</div>

        <a href="{{ route('superadmin.laporan-barang.index') }}"
            data-tooltip="Laporan Barang"
            data-page-title="Laporan Data Barang"
            class="sidebar-item {{ request()->routeIs('superadmin.laporan-barang.*') ? 'active' : '' }}">
            <i class="bi bi-clipboard-data-fill"></i>
            <span class="sidebar-item-text">Laporan Barang</span>
        </a>

        <a href="{{ route('superadmin.perbaikan-barang.index') }}"
            data-tooltip="Perbaikan Barang"
            data-page-title="Perbaikan & Biaya"
            class="sidebar-item {{ request()->routeIs('superadmin.perbaikan-barang.*') ? 'active' : '' }}">
            <i class="bi bi-tools"></i>
            <span class="sidebar-item-text">Perbaikan & Biaya</span>
        </a>

        {{-- Pengguna Dropdown --}}
        @php
            $penggunaActive = request()->routeIs('superadmin.pengguna.*');
        @endphp
        <div class="sidebar-dropdown-wrap" id="penggunaWrap">
            <a href="#" class="sidebar-item {{ $penggunaActive ? 'active' : '' }}"
                id="penggunaToggle" data-tooltip="Pengguna" onclick="toggleDropdown(event, 'penggunaMenu', 'penggunaArrow')">
                <i class="bi bi-people-fill"></i>
                <span class="sidebar-item-text">Pengguna</span>
                <i class="bi bi-chevron-down ms-auto sidebar-arrow" id="penggunaArrow" style="font-size:12px;transition:transform 0.2s;{{ $penggunaActive ? 'transform:rotate(180deg);' : '' }}"></i>
            </a>
            <div id="penggunaMenu" class="sidebar-submenu" style="{{ $penggunaActive ? '' : 'display:none;' }}">
                <a href="{{ route('superadmin.admin.index') }}"
                    data-page-title="Pengguna - Admin"
                    class="sidebar-subitem {{ request()->routeIs('superadmin.admin.*') ? 'active' : '' }}">
                    <i class="bi bi-person-gear"></i>
                    <span>Admin</span>
                </a>
                <a href="{{ route('superadmin.pengguna.siswa.index') }}"
                    data-page-title="Pengguna - Siswa"
                    class="sidebar-subitem {{ request()->routeIs('superadmin.pengguna.siswa.*') ? 'active' : '' }}">
                    <i class="bi bi-mortarboard"></i>
                    <span>Siswa</span>
                </a>
                <a href="{{ route('superadmin.pengguna.pegawai.index') }}"
                    data-page-title="Pengguna - Pegawai"
                    class="sidebar-subitem {{ request()->routeIs('superadmin.pengguna.pegawai.*') ? 'active' : '' }}">
                    <i class="bi bi-person-badge"></i>
                    <span>Pegawai</span>
                </a>
                <a href="{{ route('superadmin.pengguna.guru.index') }}"
                    data-page-title="Pengguna - Guru"
                    class="sidebar-subitem {{ request()->routeIs('superadmin.pengguna.guru.*') ? 'active' : '' }}">
                    <i class="bi bi-person-video3"></i>
                    <span>Guru</span>
                </a>
            </div>
        </div>

        <div class="sidebar-section-label">Pengaturan</div>

        <a href="{{ route('superadmin.akun') }}"
            data-tooltip="Profile"
            data-page-title="Profile Setting"
            class="sidebar-item {{ request()->routeIs('superadmin.akun') ? 'active' : '' }}">
            <i class="bi bi-person-circle"></i>
            <span class="sidebar-item-text">Profile Setting</span>
        </a>
    </nav>


</aside>

<!-- Mobile Overlay -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- Topbar -->
<header class="admin-topbar" id="adminTopbar">
    <!-- Mobile Toggle -->
    <button class="topbar-mobile-toggle" id="mobileSidebarToggle">
        <i class="bi bi-list"></i>
    </button>

    <!-- Dynamic Page Title -->
    <div class="topbar-title-area">
        <div class="topbar-page-label">Super Admin Panel</div>
        <div class="topbar-page-title" id="topbarPageTitle">
            @yield('title', 'Dashboard')
        </div>
    </div>

    <!-- Right -->
    <div class="topbar-right">
        <div class="topbar-divider"></div>
        @auth('superadmin')
        <div class="topbar-user-dropdown" id="superadminUserDropdown">
            <button class="topbar-user-pill" id="superadminUserTrigger" style="border:1px solid var(--border); outline:none; font-family:'Plus Jakarta Sans',sans-serif;">
                <div class="topbar-user-avatar">
                    @if(auth('superadmin')->user()->foto)
                        <img src="{{ asset('storage/' . auth('superadmin')->user()->foto) }}" alt="Avatar" style="width:100%; height:100%; object-fit:cover; border:none;">
                    @else
                        {{ strtoupper(substr(auth('superadmin')->user()->nama, 0, 2)) }}
                    @endif
                </div>
                <span class="topbar-user-info-name">{{ auth('superadmin')->user()->nama }}</span>
                <i class="bi bi-chevron-down" style="font-size:12px; color:var(--text-muted); margin-left:2px; transition: 0.2s;"></i>
            </button>
            <div class="topbar-user-dropdown-menu" id="superadminUserMenu">
                <div class="topbar-dropdown-header">
                    <div class="topbar-dropdown-name">{{ auth('superadmin')->user()->nama }}</div>
                    <div class="topbar-dropdown-role">Super Admin</div>
                </div>
                <a href="{{ route('superadmin.dashboard') }}" class="topbar-dropdown-item">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
                <a href="{{ route('superadmin.akun') }}" class="topbar-dropdown-item">
                    <i class="bi bi-person-circle"></i> Akun Saya
                </a>
                <div class="topbar-dropdown-divider"></div>
                <form action="{{ route('superadmin.logout') }}" method="POST" style="margin:0;">
                    @csrf
                    <button type="submit" class="topbar-dropdown-item danger">
                        <i class="bi bi-box-arrow-right"></i> Keluar
                    </button>
                </form>
            </div>
        </div>
        @endauth
    </div>
</header>

<!-- Main Content -->
<main class="admin-content" id="adminContent">
    <div class="admin-content-inner">
        @yield('content')
    </div>
</main>

@endsection

@push('scripts')
<script>
    // Sidebar Toggle
    const adminSidebar = document.getElementById('adminSidebar');
    const sidebarToggleBtn = document.getElementById('sidebarToggleBtn');
    const mobileSidebarToggle = document.getElementById('mobileSidebarToggle');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    const body = document.body;

    function toggleSidebar() {
        adminSidebar.classList.toggle('collapsed');
        body.classList.toggle('sidebar-collapsed');
        
        // Save state to localStorage
        localStorage.setItem('admin_sidebar_collapsed', adminSidebar.classList.contains('collapsed'));
    }

    function toggleMobileSidebar() {
        adminSidebar.classList.toggle('mobile-open');
        sidebarOverlay.classList.toggle('open');
    }

    // Restore state
    if (localStorage.getItem('admin_sidebar_collapsed') === 'true') {
        adminSidebar.classList.add('collapsed');
        body.classList.add('sidebar-collapsed');
    }

    // Toggle saat collapsed menggunakan logo
    const brandIcon = document.querySelector('.sidebar-brand-icon');
    if (brandIcon) {
        brandIcon.addEventListener('click', () => {
            if (adminSidebar.classList.contains('collapsed')) toggleSidebar();
        });
    }

    if(sidebarToggleBtn) sidebarToggleBtn.addEventListener('click', toggleSidebar);
    if(mobileSidebarToggle) mobileSidebarToggle.addEventListener('click', toggleMobileSidebar);
    if(sidebarOverlay) sidebarOverlay.addEventListener('click', toggleMobileSidebar);

    // Dropdown logic
    function toggleDropdown(e, menuId, arrowId) {
        e.preventDefault();
        const menu = document.getElementById(menuId);
        const arrow = document.getElementById(arrowId);
        
        if (menu.style.display === 'none') {
            menu.style.display = 'block';
            arrow.style.transform = 'rotate(180deg)';
        } else {
            menu.style.display = 'none';
            arrow.style.transform = 'rotate(0deg)';
        }
    }

    // User dropdown Topbar
    const superadminUserTrigger = document.getElementById('superadminUserTrigger');
    const superadminUserMenu = document.getElementById('superadminUserMenu');
    if (superadminUserTrigger && superadminUserMenu) {
        superadminUserTrigger.addEventListener('click', (e) => {
            e.stopPropagation();
            const isOpen = superadminUserMenu.classList.contains('open');
            superadminUserMenu.classList.toggle('open', !isOpen);
            superadminUserTrigger.querySelector('.bi-chevron-down').style.transform = isOpen ? '' : 'rotate(180deg)';
        });
        document.addEventListener('click', (e) => {
            if (!superadminUserTrigger.contains(e.target) && !superadminUserMenu.contains(e.target)) {
                superadminUserMenu.classList.remove('open');
                superadminUserTrigger.querySelector('.bi-chevron-down').style.transform = '';
            }
        });
    }
</script>

<script>
    // Auto update page title dari menu sidebar
    document.addEventListener('DOMContentLoaded', function() {
        const activeItem = document.querySelector('.sidebar-item.active:not(#penggunaToggle)');
        const activeSubItem = document.querySelector('.sidebar-subitem.active');
        const titleEl = document.getElementById('topbarPageTitle');
        
        if (titleEl && (titleEl.textContent.trim() === 'Dashboard' || titleEl.textContent.trim() === '')) {
            if (activeSubItem) {
                titleEl.textContent = activeSubItem.getAttribute('data-page-title') || activeSubItem.textContent.trim();
            } else if (activeItem && !window.location.pathname.includes('dashboard') && !window.location.pathname.includes('dasboard')) {
                const itemText = activeItem.querySelector('.sidebar-item-text');
                titleEl.textContent = activeItem.getAttribute('data-page-title') || (itemText ? itemText.textContent.trim() : '');
            }
        }
    });
</script>
@endpush
