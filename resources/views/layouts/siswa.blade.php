@extends('layouts.main')
@section('body')
@include('layouts.navbar.siswa')

<style>
    .content-wrapper {
        max-width: 1280px;
        margin: 0 auto;
        padding: 24px 32px;
    }
    @media (max-width: 768px) {
        .content-wrapper {
            padding: 16px 16px;
        }
    }
</style>

<main style="{{ request()->routeIs('welcome') ? '' : 'padding-top: 66px;' }} min-height: 100vh;">
    @if(request()->routeIs('welcome'))
        @yield('content')
    @else
        <div class="content-wrapper">
            @yield('content')
        </div>
    @endif
</main>
@endsection