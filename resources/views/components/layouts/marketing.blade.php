<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? config('app.name') }}</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen flex-col bg-slate-50 text-slate-900 antialiased">
    <header class="sticky top-0 z-50 border-b border-slate-200/80 bg-white/90 backdrop-blur">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 sm:px-6 lg:px-8">
            <a href="{{ route('home') }}" aria-label="{{ config('app.name') }} — Inicio">
                <img src="{{ asset('images/inventary-logo.svg') }}"
                     alt="{{ config('app.name') }}"
                     class="hidden h-9 w-auto sm:block">
                <img src="{{ asset('images/inventary-mark.svg') }}"
                     alt=""
                     class="h-9 w-9 sm:hidden">
            </a>
            <nav class="flex items-center gap-1 text-sm sm:gap-3">
                <a href="{{ route('workspace.find') }}" class="rounded-lg px-3 py-2 font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-950 sm:px-4">Ingresar</a>
                <a href="{{ route('tenant.register') }}" class="rounded-lg bg-amber-500 px-3 py-2 font-semibold text-slate-950 shadow-sm transition hover:bg-amber-400 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 sm:px-4">Crear cuenta gratis</a>
            </nav>
        </div>
    </header>

    <main class="flex-1">
        {{ $slot }}
    </main>

    <footer class="border-t border-slate-200 bg-white py-8 text-center text-sm text-slate-500">
        &copy; {{ date('Y') }} {{ config('app.name') }} — Inventario claro, negocios bajo control.
    </footer>
</body>
</html>
