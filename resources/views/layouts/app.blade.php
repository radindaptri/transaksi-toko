
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'ABC Report')</title>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
        <div class="app-shell">

        <main class="main-content">
            <header class="topbar">
                <span class="breadcrumb">
                    Workspace / <strong>@yield('page', 'Dashboard')</strong>
                </span>
            </header>

            <div class="page-content">
                @yield('content')
            </div>

            <footer class="footer">
                ABC Report &copy; {{ date('Y') }} · Transaction Reporting System
            </footer>
        </main>
    </div>

    @stack('scripts')
</body>
</html>
