<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">

<head>
    <title>
        {{ config('app.title') }}{{ $title ?? '' ? " : $title" : '' }}
    </title>

    @stack('meta')

    @include('layouts.css')
    @stack('styles')

    @include('layouts.javascript')
    @stack('scripts')
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
        @include('layouts.navbar')

        @include('layouts.sidebar')

        <main class="app-main">
            {{ $slot }}
        </main>

        @include('layouts.footer')
    </div>
</body>

</html>
