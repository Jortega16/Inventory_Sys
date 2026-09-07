<x-layouts.marketing title="Iniciar sesión">
    <section class="max-w-md mx-auto px-6 py-16">
        <h1 class="text-2xl font-bold text-slate-900">Iniciar sesión</h1>
        <p class="mt-2 text-sm text-slate-600">
            Escribe tu correo y te llevamos directo a tu empresa.
        </p>

        @if (session('status'))
            <div class="mt-6 rounded-md bg-green-50 border border-green-200 p-4 text-sm text-green-700">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mt-6 rounded-md bg-red-50 border border-red-200 p-4 text-sm text-red-700">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('workspace.find.store') }}" class="mt-6 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-slate-700">Correo</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                       class="mt-1 w-full rounded-md border-slate-300 shadow-sm focus:border-amber-500 focus:ring-amber-500">
            </div>
            <button type="submit"
                    class="w-full py-2.5 rounded-md bg-amber-500 text-white font-semibold hover:bg-amber-600">
                Continuar
            </button>
        </form>

        <p class="mt-6 text-sm text-center text-slate-500">
            ¿No tienes cuenta?
            <a href="{{ route('tenant.register') }}" class="text-amber-600 font-medium">Créala gratis</a>
        </p>
    </section>
</x-layouts.marketing>
