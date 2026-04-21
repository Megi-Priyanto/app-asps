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
                $recentNotifs = $query->with('laporan.kategoriAspirasi')->latest()->take(5)->get();

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
                
                $peminjamanBaru = \App\Models\PeminjamanBarang::where('status', 'Menunggu')->count();

                $view->with('notifAdmin', $laporanBaru + $unreadKomentar);
                $view->with('notifPeminjamanAdmin', $peminjamanBaru);
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

                $biayaPerbaikanPending = \App\Models\PerbaikanBarang::where('status', 'Selesai')
                    ->whereNull('biaya_perbaikan')
                    ->count();

                $view->with('notifSuperAdmin', $laporanBaru + $unreadKomentar);
                $view->with('notifBiayaPerbaikan', $biayaPerbaikanPending);
            }
        });

        // Global Notifikasi Pegawai
        \Illuminate\Support\Facades\View::composer(['layouts.pegawai', 'layouts.navbar.pegawai'], function ($view) {
            if (\Illuminate\Support\Facades\Auth::guard('pegawai')->check()) {
                $pegawaiId = \Illuminate\Support\Facades\Auth::guard('pegawai')->id();
                
                $query = \App\Models\KomentarLaporan::whereHas('laporan', function ($q) use ($pegawaiId) {
                    $q->where('reporter_id', $pegawaiId)->where('reporter_type', 'pegawai');
                })
                ->whereIn('sender_type', ['admin', 'superadmin'])
                ->where('is_read', false);

                $unread = $query->count();
                $recentNotifs = $query->with('laporan.kategoriAspirasi')->latest()->take(5)->get();

                $view->with('notifKomentar', $unread);
                $view->with('notifKomentarList', $recentNotifs);
            }
        });

        // Global Notifikasi Guru
        \Illuminate\Support\Facades\View::composer(['layouts.guru', 'layouts.navbar.guru'], function ($view) {
            if (\Illuminate\Support\Facades\Auth::guard('guru')->check()) {
                $guruId = \Illuminate\Support\Facades\Auth::guard('guru')->id();
                
                $query = \App\Models\KomentarLaporan::whereHas('laporan', function ($q) use ($guruId) {
                    $q->where('reporter_id', $guruId)->where('reporter_type', 'guru');
                })
                ->whereIn('sender_type', ['admin', 'superadmin'])
                ->where('is_read', false);

                $unread = $query->count();
                $recentNotifs = $query->with('laporan.kategoriAspirasi')->latest()->take(5)->get();

                $view->with('notifKomentar', $unread);
                $view->with('notifKomentarList', $recentNotifs);
            }
        });
    }
}