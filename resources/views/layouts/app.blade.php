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

        <main class="app-main p-4">
            <div class="app-content-header mb-3">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <h3 class="mb-0">{{ $title ?? 'Dashboard' }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="app-content">
                <div class="container-fluid">
                    {{ $slot }}
                </div>
            </div>
        </main>

        @include('layouts.footer')
    </div>
</body>

</html>
