<nav class="navbar navbar-expand-lg bg-light shadow-sm" data-bs-theme="light">
    <div class="container">

        <a class="navbar-brand" href="{{ route('welcome') }}">
            {{ config('app.name') }}
        </a>

        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarNav"
            aria-controls="navbarNav"
            aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">

                @include('layouts.nav-items.admin-auth')

            </ul>
        </div>

    </div>
</nav>