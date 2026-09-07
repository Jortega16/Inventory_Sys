<x-auth-shell
    title="Crear cuenta — InventarySys"
    eyebrow="Empieza gratis"
    heading="Crea el espacio de tu empresa"
    description="Configura tu cuenta principal y empieza a organizar el inventario en pocos minutos."
>
    <x-slot:aside>
        <p class="text-sm font-semibold uppercase tracking-widest text-amber-400">Plan Gratis incluido</p>
        <h2 class="mt-4 text-3xl font-bold tracking-tight">Todo listo para comenzar a operar.</h2>
        <ul class="mt-8 grid gap-5 text-sm text-slate-300">
            <li class="flex gap-3">
                <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-amber-400/15 text-amber-400">✓</span>
                <span><strong class="block text-white">Hasta 100 productos</strong>Organiza tu catálogo con SKU, costos y precios.</span>
            </li>
            <li class="flex gap-3">
                <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-amber-400/15 text-amber-400">✓</span>
                <span><strong class="block text-white">2 almacenes y 5 usuarios</strong>Coordina existencias y responsabilidades.</span>
            </li>
            <li class="flex gap-3">
                <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-amber-400/15 text-amber-400">✓</span>
                <span><strong class="block text-white">Sin tarjeta</strong>Prueba el flujo completo sin compromisos.</span>
            </li>
        </ul>
    </x-slot:aside>

    @if ($errors->any())
        <div role="alert" class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
            <p class="font-semibold">Revisa la información marcada.</p>
            <ul class="mt-1 list-disc space-y-1 pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('tenant.register.store') }}" class="grid gap-5">
        @csrf

        <div>
            <label for="company" class="block text-sm font-semibold text-slate-800">Nombre de la empresa</label>
            <input id="company" type="text" name="company" value="{{ old('company') }}" required autofocus autocomplete="organization"
                   placeholder="Ej. Mi Ferretería"
                   aria-describedby="company-help @error('company') company-error @enderror"
                   @error('company') aria-invalid="true" @enderror
                   class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-950 shadow-sm outline-none transition placeholder:text-slate-400 hover:border-slate-400 focus:border-amber-500 focus:ring-4 focus:ring-amber-500/15 @error('company') border-red-400 focus:border-red-500 focus:ring-red-500/15 @enderror">
            <p id="company-help" class="mt-2 text-xs leading-5 text-slate-500">Crearemos una dirección como <strong>mi-ferreteria.localhost</strong>.</p>
            @error('company')
                <p id="company-error" class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <label for="name" class="block text-sm font-semibold text-slate-800">Tu nombre</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autocomplete="name"
                       placeholder="Nombre completo"
                       @error('name') aria-invalid="true" aria-describedby="name-error" @enderror
                       class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-950 shadow-sm outline-none transition placeholder:text-slate-400 hover:border-slate-400 focus:border-amber-500 focus:ring-4 focus:ring-amber-500/15 @error('name') border-red-400 @enderror">
                @error('name')
                    <p id="name-error" class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="email" class="block text-sm font-semibold text-slate-800">Correo</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email"
                       placeholder="tu@empresa.com"
                       @error('email') aria-invalid="true" aria-describedby="email-error" @enderror
                       class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-950 shadow-sm outline-none transition placeholder:text-slate-400 hover:border-slate-400 focus:border-amber-500 focus:ring-4 focus:ring-amber-500/15 @error('email') border-red-400 @enderror">
                @error('email')
                    <p id="email-error" class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <label for="password" class="block text-sm font-semibold text-slate-800">Contraseña</label>
                <input id="password" type="password" name="password" required minlength="8" autocomplete="new-password"
                       placeholder="Mínimo 8 caracteres"
                       @error('password') aria-invalid="true" aria-describedby="password-error" @enderror
                       class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-950 shadow-sm outline-none transition placeholder:text-slate-400 hover:border-slate-400 focus:border-amber-500 focus:ring-4 focus:ring-amber-500/15 @error('password') border-red-400 @enderror">
                @error('password')
                    <p id="password-error" class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="password_confirmation" class="block text-sm font-semibold text-slate-800">Confirmar contraseña</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required minlength="8" autocomplete="new-password"
                       placeholder="Repítela"
                       class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-950 shadow-sm outline-none transition placeholder:text-slate-400 hover:border-slate-400 focus:border-amber-500 focus:ring-4 focus:ring-amber-500/15">
            </div>
        </div>

        <button type="submit" class="mt-1 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-amber-400 px-5 py-3.5 font-bold text-slate-950 shadow-lg shadow-amber-500/20 transition hover:-translate-y-0.5 hover:bg-amber-300 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">
            Crear mi cuenta gratis
            <span aria-hidden="true">→</span>
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-slate-500">
        ¿Ya tienes una cuenta?
        <a href="{{ route('workspace.find') }}" class="font-semibold text-amber-700 hover:text-amber-600">Encuentra tu empresa</a>
    </p>
</x-auth-shell>
