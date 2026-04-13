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
            'admin'   => 'App\Models\Admin',
            'siswa'   => 'App\Models\Siswa',
            'pegawai' => 'App\Models\Pegawai',
            'guru'    => 'App\Models\Guru', // ← tambah ini
        ]);

        // Global Notifikasi Siswa
        \Illuminate\Support\Facades\View::composer('layouts.siswa', function ($view) {
            if (\Illuminate\Support\Facades\Auth::guard('siswa')->check()) {
                $siswaId = \Illuminate\Support\Facades\Auth::guard('siswa')->id();
                $unread = \App\Models\KomentarLaporan::whereHas('laporan', function ($q) use ($siswaId) {
                    $q->where('siswa_id', $siswaId);
                })
                    ->where('sender_type', 'admin')
                    ->where('is_read', false)
                    ->count();
                $view->with('notifKomentar', $unread);
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