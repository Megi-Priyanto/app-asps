<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::useBootstrapFive();

        \Illuminate\Database\Eloquent\Relations\Relation::enforceMorphMap([
            'admin'      => 'App\Models\Admin',
            'superadmin' => 'App\Models\SuperAdmin',
            'siswa'      => 'App\Models\Siswa',
            'pegawai'    => 'App\Models\Pegawai',
            'guru'       => 'App\Models\Guru',
        ]);

        // Global Notifikasi Siswa
        \Illuminate\Support\Facades\View::composer(['layouts.siswa', 'layouts.navbar.siswa'], function ($view) {
            if (\Illuminate\Support\Facades\Auth::guard('siswa')->check()) {
                $siswaId = \Illuminate\Support\Facades\Auth::guard('siswa')->id();
                
                $query = \App\Models\KomentarLaporan::whereHas('laporan', function ($q) use ($siswaId) {
                    $q->where('siswa_id', $siswaId);
                })
                ->whereIn('sender_type', ['admin', 'superadmin'])
                ->where('is_read', false);

                $unread = $query->count();
                $recentNotifs = $query->with('laporan.kategori')->latest()->take(5)->get();

                $view->with('notifKomentar', $unread);
                $view->with('notifKomentarList', $recentNotifs);
            }
        });

        // Global Notifikasi Admin
        \Illuminate\Support\Facades\View::composer('layouts.admin', function ($view) {
            if (\Illuminate\Support\Facades\Auth::guard('admin')->check()) {
                $laporanBaru = \App\Models\LaporanPengaduan::whereDoesntHave('aspirasi')
                    ->orWhereHas('aspirasi', function ($q) {
                        $q->where('status', 'menunggu');
                    })->count();
                $unreadKomentar = \App\Models\KomentarLaporan::where('sender_type', 'siswa')
                    ->where('is_read', false)
                    ->count();
                $view->with('notifAdmin', $laporanBaru + $unreadKomentar);
            }
        });

        // Global Notifikasi Super Admin
        \Illuminate\Support\Facades\View::composer('layouts.superadmin', function ($view) {
            if (\Illuminate\Support\Facades\Auth::guard('superadmin')->check()) {
                $laporanBaru = \App\Models\LaporanPengaduan::whereDoesntHave('aspirasi')
                    ->orWhereHas('aspirasi', function ($q) {
                        $q->where('status', 'menunggu');
                    })->count();
                $unreadKomentar = \App\Models\KomentarLaporan::where('sender_type', 'siswa')
                    ->where('is_read', false)
                    ->count();
                $view->with('notifSuperAdmin', $laporanBaru + $unreadKomentar);
            }
        });

        // Global Notifikasi Pegawai
        \Illuminate\Support\Facades\View::composer('layouts.pegawai', function ($view) {
            if (\Illuminate\Support\Facades\Auth::guard('pegawai')->check()) {
                $view->with('notifPegawai', 0);
            }
        });

        // Global Notifikasi Guru
        \Illuminate\Support\Facades\View::composer('layouts.guru', function ($view) {
            if (\Illuminate\Support\Facades\Auth::guard('guru')->check()) {
                $view->with('notifGuru', 0);
            }
        });
    }
}