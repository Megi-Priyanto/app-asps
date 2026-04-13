<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>
        @hasSection('title')
            @yield('title') - {{ config('app.name') }}
        @else
            {{ config('app.name') }}
        @endif
    </title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('bs/font/bootstrap-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('bs/css/bootstrap.min.css') }}">

    @stack('css')
</head>

<body>

    @yield('body')

    <script src="{{ asset('bs/js/bootstrap.bundle.min.js') }}"></script>

    @stack('js')
    @stack('scripts')

</body>

</html>
