@props([
    'title',
    'eyebrow',
    'heading',
    'description',
])

<x-layouts.marketing :title="$title">
    <section {{ $attributes->class(['relative overflow-hidden py-10 sm:py-16 lg:py-20']) }}>
        <div class="absolute inset-0 -z-10" aria-hidden="true">
            <div class="absolute -left-32 top-0 h-80 w-80 rounded-full bg-amber-200/60 blur-3xl"></div>
            <div class="absolute -right-32 bottom-0 h-80 w-80 rounded-full bg-sky-100 blur-3xl"></div>
        </div>

        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            <div class="grid overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-2xl shadow-slate-900/10 lg:grid-cols-[0.88fr_1.12fr]">
                <aside class="order-2 relative overflow-hidden bg-slate-950 px-6 py-10 text-white sm:px-10 lg:order-1 lg:px-12 lg:py-14">
                    <div class="absolute inset-0" aria-hidden="true">
                        <div class="absolute -right-16 -top-16 h-64 w-64 rounded-full bg-amber-500/20 blur-3xl"></div>
                        <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff08_1px,transparent_1px),linear-gradient(to_bottom,#ffffff08_1px,transparent_1px)] bg-[size:40px_40px]"></div>
                    </div>

                    <div class="relative flex h-full flex-col">
                        <img src="{{ asset('images/inventary-logo-dark.svg') }}" alt="{{ config('app.name') }}" class="h-10 w-auto self-start">

                        <div class="my-auto py-10">
                            {{ $aside }}
                        </div>

                        <p class="text-xs leading-5 text-slate-400">Tu información permanece separada y protegida dentro del espacio de tu empresa.</p>
                    </div>
                </aside>

                <main class="order-1 px-5 py-9 sm:px-10 sm:py-12 lg:order-2 lg:px-14 lg:py-14">
                    <div class="mx-auto max-w-lg">
                        <p class="text-sm font-bold uppercase tracking-widest text-amber-600">{{ $eyebrow }}</p>
                        <h1 class="mt-3 text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">{{ $heading }}</h1>
                        <p class="mt-3 leading-7 text-slate-600">{{ $description }}</p>

                        <div class="mt-8">
                            {{ $slot }}
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </section>
</x-layouts.marketing>
