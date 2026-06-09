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

<body class="login-page d-flex align-items-center justify-content-center min-vh-100 position-relative">
    {{ $slot }}
</body>

</html>
