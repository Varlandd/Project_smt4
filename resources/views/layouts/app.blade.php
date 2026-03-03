<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'RumahKu — Sistem Rekomendasi Rumah Impian')</title>

    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>

    {{-- Main CSS --}}
    <link rel="stylesheet" href="{{ asset('css/rumah.css') }}">

    @stack('styles')
</head>
<body>

    @include('components.navbar')

    <main>
        @yield('content')
    </main>

    @include('components.footer')

    <script src="{{ asset('js/rumah.js') }}"></script>

    @stack('scripts')

</body>
</html>
