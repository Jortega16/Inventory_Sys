@php
    $freePlan = config('plans.free');
@endphp

<x-layouts.marketing :title="config('app.name') . ' — Control de inventario para tu negocio'">
    <section class="relative overflow-hidden bg-slate-950">
        <div class="absolute inset-0 overflow-hidden" aria-hidden="true">
            <div class="absolute -right-24 -top-32 h-96 w-96 rounded-full bg-amber-500/20 blur-3xl"></div>
            <div class="absolute -bottom-48 -left-24 h-96 w-96 rounded-full bg-sky-500/10 blur-3xl"></div>
            <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff08_1px,transparent_1px),linear-gradient(to_bottom,#ffffff08_1px,transparent_1px)] bg-[size:48px_48px]"></div>
        </div>

        <div class="relative mx-auto grid max-w-7xl items-center gap-14 px-4 py-16 sm:px-6 sm:py-24 lg:grid-cols-[1.02fr_0.98fr] lg:px-8 lg:py-28">
            <div>
                <div class="inline-flex items-center gap-2 rounded-full border border-amber-400/25 bg-amber-400/10 px-3 py-1.5 text-sm font-medium text-amber-300">
                    <span class="h-2 w-2 rounded-full bg-amber-400"></span>
                    Empieza gratis · Sin tarjeta
                </div>

                <h1 class="mt-6 max-w-3xl text-4xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-6xl">
                    Tu inventario deja de ser una duda y se convierte en una decisión.
                </h1>

                <p class="mt-6 max-w-2xl text-lg leading-8 text-slate-300 sm:text-xl">
                    Controla productos, existencias, compras y ventas desde un solo lugar. {{ config('app.name') }} te muestra qué tienes, dónde está y qué necesita atención.
                </p>

                <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                    <a href="{{ route('tenant.register') }}"
                       class="inline-flex items-center justify-center gap-2 rounded-xl bg-amber-400 px-6 py-3.5 text-base font-bold text-slate-950 shadow-lg shadow-amber-500/20 transition hover:-translate-y-0.5 hover:bg-amber-300 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:ring-offset-2 focus:ring-offset-slate-950">
                        Crear mi cuenta gratis
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M3 10a.75.75 0 0 1 .75-.75h10.69L10.22 5.03a.75.75 0 0 1 1.06-1.06l5.5 5.5a.75.75 0 0 1 0 1.06l-5.5 5.5a.75.75 0 1 1-1.06-1.06l4.22-4.22H3.75A.75.75 0 0 1 3 10Z" clip-rule="evenodd"/>
                        </svg>
                    </a>
                    <a href="#como-funciona"
                       class="inline-flex items-center justify-center rounded-xl border border-white/15 bg-white/5 px-6 py-3.5 text-base font-semibold text-white transition hover:bg-white/10">
                        Ver cómo funciona
                    </a>
                </div>

                <ul class="mt-7 flex flex-wrap gap-x-6 gap-y-3 text-sm text-slate-300" aria-label="Beneficios del registro">
                    <li class="flex items-center gap-2">
                        <svg class="h-5 w-5 text-amber-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M16.7 5.29a1 1 0 0 1 .01 1.42l-8 8a1 1 0 0 1-1.42 0l-4-4a1 1 0 0 1 1.42-1.42L8 12.59l7.29-7.3a1 1 0 0 1 1.41 0Z" clip-rule="evenodd"/>
                        </svg>
                        Espacio privado para tu empresa
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="h-5 w-5 text-amber-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M16.7 5.29a1 1 0 0 1 .01 1.42l-8 8a1 1 0 0 1-1.42 0l-4-4a1 1 0 0 1 1.42-1.42L8 12.59l7.29-7.3a1 1 0 0 1 1.41 0Z" clip-rule="evenodd"/>
                        </svg>
                        Configuración inmediata
                    </li>
                </ul>
            </div>

            <div class="relative mx-auto w-full max-w-2xl lg:mx-0">
                <div class="absolute -inset-5 rounded-3xl bg-gradient-to-tr from-amber-400/20 to-sky-400/10 blur-2xl"></div>
                <div class="relative overflow-hidden rounded-2xl border border-white/10 bg-slate-900 shadow-2xl shadow-black/40">
                    <div class="flex items-center gap-2 border-b border-white/10 px-4 py-3">
                        <span class="h-2.5 w-2.5 rounded-full bg-red-400"></span>
                        <span class="h-2.5 w-2.5 rounded-full bg-amber-400"></span>
                        <span class="h-2.5 w-2.5 rounded-full bg-emerald-400"></span>
                        <span class="ml-3 text-xs text-slate-500">mi-negocio.localhost/admin</span>
                    </div>

                    <div class="grid grid-cols-[64px_1fr] sm:grid-cols-[150px_1fr]">
                        <aside class="border-r border-white/10 bg-slate-950/70 p-3 sm:p-4">
                            <img src="{{ asset('images/inventary-mark.svg') }}" alt="" class="h-8 w-8 sm:hidden">
                            <img src="{{ asset('images/inventary-logo-dark.svg') }}" alt="{{ config('app.name') }}" class="hidden h-7 w-auto sm:block">
                            <div class="mt-7 grid gap-2 text-xs text-slate-400">
                                <div class="rounded-lg bg-amber-400/10 p-2 font-medium text-amber-300 sm:px-3">Resumen</div>
                                <div class="hidden rounded-lg p-2 sm:block sm:px-3">Productos</div>
                                <div class="hidden rounded-lg p-2 sm:block sm:px-3">Almacenes</div>
                                <div class="hidden rounded-lg p-2 sm:block sm:px-3">Compras</div>
                                <div class="hidden rounded-lg p-2 sm:block sm:px-3">Ventas</div>
                            </div>
                        </aside>

                        <div class="min-w-0 bg-slate-50 p-4 sm:p-6">
                            <div class="flex items-end justify-between gap-4">
                                <div>
                                    <p class="text-xs font-medium text-slate-500">Hoy</p>
                                    <p class="text-lg font-bold text-slate-900 sm:text-xl">Resumen de inventario</p>
                                </div>
                                <div class="hidden rounded-lg bg-amber-400 px-3 py-2 text-xs font-bold text-slate-900 sm:block">+ Producto</div>
                            </div>

                            <div class="mt-5 grid gap-3 sm:grid-cols-3">
                                <div class="rounded-xl border border-slate-200 bg-white p-3">
                                    <p class="text-[10px] uppercase tracking-wide text-slate-500">Productos</p>
                                    <p class="mt-1 text-xl font-bold text-slate-900">86</p>
                                    <p class="mt-1 text-[10px] text-emerald-600">84 disponibles</p>
                                </div>
                                <div class="rounded-xl border border-slate-200 bg-white p-3">
                                    <p class="text-[10px] uppercase tracking-wide text-slate-500">Existencias</p>
                                    <p class="mt-1 text-xl font-bold text-slate-900">1,248</p>
                                    <p class="mt-1 text-[10px] text-slate-500">en 2 almacenes</p>
                                </div>
                                <div class="rounded-xl border border-amber-200 bg-amber-50 p-3">
                                    <p class="text-[10px] uppercase tracking-wide text-amber-700">Por reponer</p>
                                    <p class="mt-1 text-xl font-bold text-amber-900">7</p>
                                    <p class="mt-1 text-[10px] text-amber-700">requieren atención</p>
                                </div>
                            </div>

                            <div class="mt-3 rounded-xl border border-slate-200 bg-white p-4">
                                <div class="flex items-center justify-between">
                                    <p class="text-xs font-semibold text-slate-700">Movimiento de existencias</p>
                                    <p class="text-[10px] text-slate-400">Últimos 7 días</p>
                                </div>
                                <div class="mt-4 flex h-24 items-end gap-2">
                                    <div class="h-2/5 flex-1 rounded-t bg-amber-200"></div>
                                    <div class="h-3/5 flex-1 rounded-t bg-amber-300"></div>
                                    <div class="h-1/3 flex-1 rounded-t bg-amber-200"></div>
                                    <div class="h-4/5 flex-1 rounded-t bg-amber-400"></div>
                                    <div class="h-1/2 flex-1 rounded-t bg-amber-300"></div>
                                    <div class="h-full flex-1 rounded-t bg-amber-500"></div>
                                    <div class="h-3/4 flex-1 rounded-t bg-amber-400"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="border-b border-slate-200 bg-white">
        <div class="mx-auto grid max-w-7xl grid-cols-2 divide-x divide-y divide-slate-200 px-4 sm:grid-cols-4 sm:divide-y-0 sm:px-6 lg:px-8">
            @foreach (['Catálogo', 'Almacenes', 'Compras', 'Ventas'] as $module)
                <div class="px-4 py-5 text-center text-sm font-semibold text-slate-500">{{ $module }}</div>
            @endforeach
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-20 sm:px-6 sm:py-24 lg:px-8">
        <div class="mx-auto max-w-3xl text-center">
            <p class="text-sm font-bold uppercase tracking-widest text-amber-600">Menos hojas de cálculo, más claridad</p>
            <h2 class="mt-3 text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">Todo lo que necesitas para mover tu inventario con confianza</h2>
            <p class="mt-4 text-lg text-slate-600">Conecta las áreas de tu operación y evita decisiones basadas en información atrasada.</p>
        </div>

        <div class="mt-12 grid gap-6 md:grid-cols-3">
            <article class="rounded-2xl border border-slate-200 bg-white p-7 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-100 text-amber-700">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 8.25-9-4.5-9 4.5m18 0-9 4.5m9-4.5v7.5l-9 4.5m0-7.5-9-4.5m9 4.5v7.5m-9-12v7.5l9 4.5"/>
                    </svg>
                </div>
                <h3 class="mt-5 text-xl font-bold text-slate-950">Existencias que sí cuadran</h3>
                <p class="mt-3 leading-7 text-slate-600">Conoce las unidades disponibles por almacén, registra ajustes y transfiere productos sin perder el rastro.</p>
            </article>

            <article class="rounded-2xl border border-slate-200 bg-white p-7 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-sky-100 text-sky-700">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13 5.4 5M7 13l-2 2h13m-9 4.5h.01m7.99 0h.01"/>
                    </svg>
                </div>
                <h3 class="mt-5 text-xl font-bold text-slate-950">Compras y ventas conectadas</h3>
                <p class="mt-3 leading-7 text-slate-600">Organiza proveedores, clientes y órdenes desde el mismo sistema donde controlas tus productos.</p>
            </article>

            <article class="rounded-2xl border border-slate-200 bg-white p-7 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2m7-10a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm13 10v-2a4 4 0 0 0-3-3.87m-2-12.26a4 4 0 0 1 0 7.75"/>
                    </svg>
                </div>
                <h3 class="mt-5 text-xl font-bold text-slate-950">Tu equipo, con el acceso correcto</h3>
                <p class="mt-3 leading-7 text-slate-600">Asigna roles para administración, almacén, compras, ventas o consulta y protege cada área del negocio.</p>
            </article>
        </div>
    </section>

    <section id="como-funciona" class="bg-white py-20 sm:py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid items-start gap-12 lg:grid-cols-[0.8fr_1.2fr]">
                <div class="lg:sticky lg:top-28">
                    <p class="text-sm font-bold uppercase tracking-widest text-amber-600">Listo en minutos</p>
                    <h2 class="mt-3 text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">De cero a tu primer control de inventario en tres pasos</h2>
                    <p class="mt-4 text-lg leading-8 text-slate-600">No necesitas configurar servidores ni compartir una cuenta genérica con todo el equipo.</p>
                    <a href="{{ route('tenant.register') }}" class="mt-7 inline-flex items-center gap-2 font-bold text-amber-700 hover:text-amber-600">
                        Crear mi espacio de trabajo
                        <span aria-hidden="true">→</span>
                    </a>
                </div>

                <ol class="grid gap-4">
                    <li class="flex gap-5 rounded-2xl border border-slate-200 bg-slate-50 p-6">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-slate-950 font-bold text-white">1</span>
                        <div>
                            <h3 class="text-lg font-bold text-slate-950">Crea la cuenta de tu empresa</h3>
                            <p class="mt-2 leading-7 text-slate-600">Indica el nombre del negocio y los datos del usuario administrador. No solicitamos tarjeta.</p>
                        </div>
                    </li>
                    <li class="flex gap-5 rounded-2xl border border-slate-200 bg-slate-50 p-6">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-slate-950 font-bold text-white">2</span>
                        <div>
                            <h3 class="text-lg font-bold text-slate-950">Recibe un espacio privado</h3>
                            <p class="mt-2 leading-7 text-slate-600">Creamos automáticamente el subdominio y la base de datos aislada de tu empresa.</p>
                        </div>
                    </li>
                    <li class="flex gap-5 rounded-2xl border border-amber-300 bg-amber-50 p-6">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-amber-400 font-bold text-slate-950">3</span>
                        <div>
                            <h3 class="text-lg font-bold text-slate-950">Carga productos y empieza a operar</h3>
                            <p class="mt-2 leading-7 text-slate-700">Agrega tu catálogo, crea almacenes e invita al equipo con permisos según sus responsabilidades.</p>
                        </div>
                    </li>
                </ol>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-20 sm:px-6 sm:py-24 lg:px-8">
        <div class="overflow-hidden rounded-3xl bg-slate-950 px-6 py-12 shadow-2xl sm:px-12 lg:grid lg:grid-cols-[1fr_auto] lg:items-center lg:gap-12 lg:px-16 lg:py-16">
            <div>
                <p class="text-sm font-bold uppercase tracking-widest text-amber-400">Plan {{ $freePlan['name'] }}</p>
                <h2 class="mt-3 max-w-2xl text-3xl font-bold tracking-tight text-white sm:text-4xl">Empieza a ordenar tu inventario hoy, sin compromisos.</h2>
                <p class="mt-4 max-w-2xl text-lg leading-8 text-slate-300">
                    Incluye hasta {{ $freePlan['max_users'] }} usuarios, {{ number_format($freePlan['max_products']) }} productos y {{ $freePlan['max_warehouses'] }} almacenes por ${{ number_format($freePlan['price']) }}/mes.
                </p>
            </div>
            <div class="mt-8 lg:mt-0">
                <a href="{{ route('tenant.register') }}"
                   class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-amber-400 px-7 py-4 text-base font-bold text-slate-950 shadow-lg shadow-amber-500/20 transition hover:-translate-y-0.5 hover:bg-amber-300 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:ring-offset-2 focus:ring-offset-slate-950">
                    Crear cuenta gratis
                    <span aria-hidden="true">→</span>
                </a>
                <p class="mt-3 text-center text-xs text-slate-400">Tu espacio estará listo al registrarte.</p>
            </div>
        </div>
    </section>
</x-layouts.marketing>
