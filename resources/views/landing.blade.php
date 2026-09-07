<x-layouts.marketing :title="config('app.name') . ' — Inventario multi-empresa'">
    <section class="max-w-5xl mx-auto px-6 py-20 text-center">
        <h1 class="text-4xl sm:text-5xl font-extrabold tracking-tight text-slate-900">
            Controla tu inventario, sin importar cuántas empresas administres
        </h1>
        <p class="mt-5 text-lg text-slate-600 max-w-2xl mx-auto">
            {{ config('app.name') }} es un sistema de gestión de inventario multi-empresa (SaaS):
            cada negocio tiene su propio espacio de trabajo aislado, con catálogo de productos,
            control de stock y reportes — todo desde un mismo panel.
        </p>
        <div class="mt-8 flex justify-center gap-4">
            <a href="{{ route('tenant.register') }}"
               class="px-6 py-3 rounded-md bg-amber-500 text-white font-semibold hover:bg-amber-600">
                Crear mi cuenta
            </a>
            <a href="{{ route('workspace.find') }}"
               class="px-6 py-3 rounded-md border border-slate-300 font-semibold hover:bg-slate-100">
                Ya tengo cuenta — Iniciar sesión
            </a>
        </div>
    </section>

    <section class="max-w-5xl mx-auto px-6 pb-20 grid gap-6 sm:grid-cols-3">
        <div class="bg-white rounded-lg border border-slate-200 p-6">
            <h3 class="font-semibold text-slate-900">Multi-empresa real</h3>
            <p class="mt-2 text-sm text-slate-600">
                Cada cuenta tiene su propia base de datos, completamente aislada del resto.
            </p>
        </div>
        <div class="bg-white rounded-lg border border-slate-200 p-6">
            <h3 class="font-semibold text-slate-900">Catálogo de productos</h3>
            <p class="mt-2 text-sm text-slate-600">
                SKU, precios, costos y punto de reorden, listo desde el primer día.
            </p>
        </div>
        <div class="bg-white rounded-lg border border-slate-200 p-6">
            <h3 class="font-semibold text-slate-900">Arquitectura modular</h3>
            <p class="mt-2 text-sm text-slate-600">
                Catálogo, almacenes, compras y ventas como módulos independientes.
            </p>
        </div>
    </section>
</x-layouts.marketing>
