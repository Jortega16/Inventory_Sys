<x-filament-panels::page>
    @php
        $plan = $this->getPlan();
        $usage = $this->getUsage();
    @endphp

    <div class="fi-section rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Plan actual</p>
                <h2 class="text-2xl font-bold text-gray-950 dark:text-white">{{ $plan['name'] }}</h2>
            </div>
            <div class="text-right">
                <p class="text-2xl font-bold text-gray-950 dark:text-white">
                    ${{ number_format($plan['price'], 0) }}<span class="text-sm font-normal text-gray-500">/mes</span>
                </p>
            </div>
        </div>

        <div class="mt-4 rounded-lg bg-amber-50 p-3 text-sm text-amber-800 dark:bg-amber-500/10 dark:text-amber-400">
            La actualización de plan y el cobro todavía no están disponibles — esta pantalla es informativa.
            Contacta al equipo si necesitas más capacidad.
        </div>
    </div>

    <div class="mt-6 grid gap-4 sm:grid-cols-3">
        @foreach ($usage as $item)
            @php
                $max = $item['max'];
                $current = $item['current'];
                $percent = $max ? min(100, (int) round(($current / max($max, 1)) * 100)) : 0;
                $isNearLimit = $max && $current >= $max;
            @endphp
            <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $item['label'] }}</p>
                <p class="mt-1 text-xl font-bold text-gray-950 dark:text-white">
                    {{ $current }}
                    <span class="text-sm font-normal text-gray-500">/ {{ $max ?? '∞' }}</span>
                </p>
                @if ($max)
                    <div class="mt-2 h-2 w-full rounded-full bg-gray-100 dark:bg-gray-800">
                        <div
                            class="h-2 rounded-full {{ $isNearLimit ? 'bg-danger-500' : 'bg-primary-500' }}"
                            style="width: {{ $percent }}%"
                        ></div>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</x-filament-panels::page>
