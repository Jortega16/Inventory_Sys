<x-layouts.marketing title="Crear cuenta">
    <section class="max-w-md mx-auto px-6 py-16">
        <h1 class="text-2xl font-bold text-slate-900">Crea tu cuenta</h1>
        <p class="mt-2 text-sm text-slate-600">
            Se creará un espacio de trabajo propio y aislado para tu empresa.
        </p>

        @if ($errors->any())
            <div class="mt-6 rounded-md bg-red-50 border border-red-200 p-4 text-sm text-red-700">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('tenant.register.store') }}" class="mt-6 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-slate-700">Nombre de la empresa</label>
                <input type="text" name="company" value="{{ old('company') }}" required
                       class="mt-1 w-full rounded-md border-slate-300 shadow-sm focus:border-amber-500 focus:ring-amber-500">
                <p class="mt-1 text-xs text-slate-500">Será tu subdominio, p. ej. "Mi Ferretería" → mi-ferreteria.localhost</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Tu nombre</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       class="mt-1 w-full rounded-md border-slate-300 shadow-sm focus:border-amber-500 focus:ring-amber-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Correo</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       class="mt-1 w-full rounded-md border-slate-300 shadow-sm focus:border-amber-500 focus:ring-amber-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Contraseña</label>
                <input type="password" name="password" required minlength="8"
                       class="mt-1 w-full rounded-md border-slate-300 shadow-sm focus:border-amber-500 focus:ring-amber-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Confirmar contraseña</label>
                <input type="password" name="password_confirmation" required minlength="8"
                       class="mt-1 w-full rounded-md border-slate-300 shadow-sm focus:border-amber-500 focus:ring-amber-500">
            </div>
            <button type="submit"
                    class="w-full py-2.5 rounded-md bg-amber-500 text-white font-semibold hover:bg-amber-600">
                Crear cuenta
            </button>
        </form>

        <p class="mt-6 text-sm text-center text-slate-500">
            ¿Ya tienes cuenta?
            <a href="{{ route('workspace.find') }}" class="text-amber-600 font-medium">Inicia sesión</a>
        </p>
    </section>
</x-layouts.marketing>
