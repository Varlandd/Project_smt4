<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin — RumahKu')</title>

    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>

    {{-- Main CSS + Admin CSS --}}
    <link rel="stylesheet" href="{{ asset('css/rumah.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

    @stack('styles')
</head>
<body class="admin-body">

    {{-- Admin Sidebar --}}
    @include('admin.components.sidebar')

    <div class="admin-main">
        {{-- Admin Topbar --}}
        @include('admin.components.topbar')

        <main class="admin-content">
            @if(session('success'))
                <div class="form-success">{{ session('success') }}</div>
            @endif

            @yield('content')
        </main>
    </div>

    <script src="{{ asset('js/rumah.js') }}"></script>
    <script src="{{ asset('js/admin.js') }}"></script>

    @stack('scripts')

</body>
</html>
