<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectGuestsTo(fn(Request $request) => route('welcome'));
        $middleware->redirectUsersTo(function (Request $request) {
            if (\Illuminate\Support\Facades\Auth::guard('siswa')->check()) {
                return route('siswa.dashboard');
            }
            if (\Illuminate\Support\Facades\Auth::guard('guru')->check()) {
                return route('guru.dashboard');
            }
            if (\Illuminate\Support\Facades\Auth::guard('pegawai')->check()) {
                return route('pegawai.dashboard');
            }
            if (\Illuminate\Support\Facades\Auth::guard('admin')->check()) {
                return route('admin.dashboard');
            }
            if (\Illuminate\Support\Facades\Auth::guard('superadmin')->check()) {
                return route('superadmin.dashboard');
            }
            return route('welcome');
        });
        $middleware->alias([
            'superadmin' => \App\Http\Middleware\IsSuperAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
