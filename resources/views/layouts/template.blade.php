<!doctype html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <!-- Select2 CSS -->
    @yield('extra_css')
    <title>@yield('title', 'Onkostenportaal')</title>
    @include('shared.favicon')

</head>
<body>

@include('shared.navigation')

<main class="container mt-3">
    @yield('main', 'Page under construction ...')
</main>
<footer class="container mt-5">
    @include('shared.footer')
</footer>

<script src="{{ mix('js/app.js') }}"></script>
@include('shared.cdn_linken')
@yield('script_after')

</body>
</html>
