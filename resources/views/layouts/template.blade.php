<!doctype html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    @yield('extra_css')
    <title>@yield('title', 'Onkostenportaal')</title>
    @include('shared.favicon')
</head>
<body>

@include('shared.navigation')

<main class="container mt-3">
    @yield('main', 'Page under construction ...')
</main>

{{--@include('shared.footer')--}}

<script src="{{ mix('js/app.js') }}"></script>
@yield('script_after')
</body>
</html>
