<x-auth-shell
    title="Encuentra tu empresa — InventarySys"
    eyebrow="Acceso seguro"
    heading="Encuentra tu espacio de trabajo"
    description="Escribe el correo asociado a tu cuenta y te llevaremos al acceso privado de tu empresa."
>
    <x-slot:aside>
        <p class="text-sm font-semibold uppercase tracking-widest text-amber-400">Un acceso para cada empresa</p>
        <h2 class="mt-4 text-3xl font-bold tracking-tight">Tus datos viven donde deben estar.</h2>
        <p class="mt-5 leading-7 text-slate-300">Cada negocio tiene una dirección y una base de datos independientes. Usamos tu correo únicamente para encontrar el espacio correcto.</p>

        <div class="mt-8 rounded-2xl border border-white/10 bg-white/5 p-5">
            <div class="flex items-start gap-3">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-400/15 text-xl text-amber-400">↗</span>
                <div>
                    <p class="font-semibold text-white">Dos pasos sencillos</p>
                    <p class="mt-1 text-sm leading-6 text-slate-400">Primero encontramos tu empresa. Después ingresas tu contraseña en su espacio privado.</p>
                </div>
            </div>
        </div>
    </x-slot:aside>

    @if (session('status'))
        <div role="status" class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-700">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div role="alert" class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
            <p class="font-semibold">No pudimos encontrar tu cuenta.</p>
            <ul class="mt-1 list-disc space-y-1 pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('workspace.find.store') }}" class="grid gap-5">
        @csrf

        <div>
            <label for="email" class="block text-sm font-semibold text-slate-800">Correo electrónico</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email"
                   placeholder="tu@empresa.com"
                   aria-describedby="email-help @error('email') email-error @enderror"
                   @error('email') aria-invalid="true" @enderror
                   class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-950 shadow-sm outline-none transition placeholder:text-slate-400 hover:border-slate-400 focus:border-amber-500 focus:ring-4 focus:ring-amber-500/15 @error('email') border-red-400 focus:border-red-500 focus:ring-red-500/15 @enderror">
            <p id="email-help" class="mt-2 text-xs leading-5 text-slate-500">Debe ser el correo con el que te registraste o fuiste invitado.</p>
            @error('email')
                <p id="email-error" class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-amber-400 px-5 py-3.5 font-bold text-slate-950 shadow-lg shadow-amber-500/20 transition hover:-translate-y-0.5 hover:bg-amber-300 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">
            Continuar a mi empresa
            <span aria-hidden="true">→</span>
        </button>
    </form>

    <div class="mt-7 flex items-center gap-4" aria-hidden="true">
        <div class="h-px flex-1 bg-slate-200"></div>
        <span class="text-xs font-medium uppercase tracking-wider text-slate-400">¿Primera vez?</span>
        <div class="h-px flex-1 bg-slate-200"></div>
    </div>

    <a href="{{ route('tenant.register') }}" class="mt-5 inline-flex w-full items-center justify-center rounded-xl border border-slate-300 px-5 py-3 font-semibold text-slate-700 transition hover:border-slate-400 hover:bg-slate-50">
        Crear una cuenta gratis
    </a>
</x-auth-shell>
