<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? config('app.name') }}</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 text-slate-900 min-h-screen flex flex-col">
    <header class="border-b border-slate-200 bg-white">
        <div class="max-w-5xl mx-auto px-6 py-4 flex items-center justify-between">
            <a href="{{ route('home') }}" aria-label="{{ config('app.name') }} — Inicio">
                <img src="{{ asset('images/inventary-logo.svg') }}"
                     alt="{{ config('app.name') }}"
                     class="hidden h-9 w-auto sm:block">
                <img src="{{ asset('images/inventary-mark.svg') }}"
                     alt=""
                     class="h-9 w-9 sm:hidden">
            </a>
            <nav class="flex gap-3 text-sm">
                <a href="{{ route('workspace.find') }}" class="px-4 py-2 rounded-md hover:bg-slate-100">Iniciar sesión</a>
                <a href="{{ route('tenant.register') }}" class="px-4 py-2 rounded-md bg-amber-500 text-white hover:bg-amber-600">Crear cuenta gratis</a>
            </nav>
        </div>
    </header>

    <main class="flex-1">
        {{ $slot }}
    </main>

    <footer class="border-t border-slate-200 py-6 text-center text-sm text-slate-500">
        &copy; {{ date('Y') }} {{ config('app.name') }} — Gestión de inventario multi-empresa.
    </footer>
</body>
</html>
