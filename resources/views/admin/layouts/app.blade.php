<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="zoho-admin-html">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'Admin') — Panchi Gallery ERP</title>
    {{-- Ensure base URL doesn't contain duplicated /public segments when served from XAMPP folder URLs --}}
    <base href="{{ rtrim(str_replace('/public/public', '/public', url('/')), '/') }}/">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css">
    @vite(['resources/css/admin-zoho.css', 'resources/css/app.css', 'resources/css/loader.css', 'resources/js/admin-zoho.js'])

    {{-- Fallback: when served via XAMPP legacy paths (/public/public/...), Vite dev server may not be reachable
         and styles can appear missing. If a production build manifest exists and the current request
         URI contains the duplicated /public segment, include built assets directly from /build. --}}
    @if(str_contains(request()->getRequestUri(), '/public/public') && file_exists(public_path('build/manifest.json')))
        @php $manifest = json_decode(file_get_contents(public_path('build/manifest.json')), true); @endphp
        @if(isset($manifest['resources/css/admin-zoho.css']))
            <link rel="stylesheet" href="{{ asset('build/'.$manifest['resources/css/admin-zoho.css']['file']) }}">
        @endif
        @if(isset($manifest['resources/css/app.css']))
            <link rel="stylesheet" href="{{ asset('build/'.$manifest['resources/css/app.css']['file']) }}">
        @endif
        @if(isset($manifest['resources/js/admin-zoho.js']))
            <script src="{{ asset('build/'.$manifest['resources/js/admin-zoho.js']['file']) }}" defer></script>
        @endif
    @endif
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('head')
</head>
<body class="zoho-admin-body">
    <div class="zoho-app">
        @include('admin.partials.sidebar')

        <div class="zoho-main">
            @include('admin.partials.topbar', [
                'moduleTitle' => trim($__env->yieldContent('header')) ?: ($moduleTitle ?? 'Home'),
            ])

            <div class="zoho-body {{ ($showWidgets ?? request()->routeIs('admin.dashboard*')) ? 'has-widgets' : '' }}">
                <main class="zoho-content">
                    @if(session('success'))
                        <div class="zoho-alert zoho-alert-success">
                            <i class="fas fa-check-circle"></i> {{ session('success') }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="zoho-alert zoho-alert-error">
                            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                        </div>
                    @endif

                    @yield('admin_content')
                    @yield('content')
                </main>

                @if($showWidgets ?? request()->routeIs('admin.dashboard*'))
                    @include('admin.partials.widgets-pane')
                @endif
            </div>
        </div>
    </div>

    @stack('scripts')
    @yield('scripts')
</body>
</html>
