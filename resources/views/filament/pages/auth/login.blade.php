<x-filament-panels::page.simple
    heading="Bienvenido de nuevo"
    subheading="Ingresa para continuar en el espacio privado de tu empresa."
>
    <style>
        .fi-simple-layout {
            background-color: #f8fafc;
            background-image:
                radial-gradient(circle at 15% 15%, rgb(251 191 36 / 0.16), transparent 28rem),
                radial-gradient(circle at 85% 85%, rgb(14 165 233 / 0.08), transparent 30rem);
        }

        .fi-simple-main {
            position: relative;
            overflow: hidden;
            border-radius: 1.5rem;
            box-shadow: 0 24px 60px -24px rgb(15 23 42 / 0.28);
        }

        .fi-simple-main::before {
            position: absolute;
            inset: 0 0 auto;
            height: 0.25rem;
            background: linear-gradient(90deg, #f59e0b, #fbbf24, #0f172a);
            content: '';
        }

        .dark .fi-simple-layout {
            background-color: #020617;
            background-image:
                radial-gradient(circle at 15% 15%, rgb(245 158 11 / 0.13), transparent 28rem),
                radial-gradient(circle at 85% 85%, rgb(14 165 233 / 0.08), transparent 30rem);
        }

        .dark .fi-simple-main {
            box-shadow: 0 24px 60px -24px rgb(0 0 0 / 0.8);
        }
    </style>

    <div class="rounded-xl bg-primary-50 p-4 text-sm text-primary-800 ring-1 ring-inset ring-primary-200 dark:bg-primary-500/10 dark:text-primary-300 dark:ring-primary-500/20">
        <div class="flex gap-3">
            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-primary-100 font-bold text-primary-700 dark:bg-primary-500/20 dark:text-primary-300">
                ✓
            </div>
            <div>
                <p class="font-semibold">Estás en el espacio correcto</p>
                <p class="mt-1 text-xs leading-5 opacity-80">Tus productos, almacenes y operaciones están protegidos dentro de esta empresa.</p>
            </div>
        </div>
    </div>

    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::AUTH_LOGIN_FORM_BEFORE, scopes: $this->getRenderHookScopes()) }}

    <x-filament-panels::form id="form" wire:submit="authenticate">
        {{ $this->form }}

        <x-filament-panels::form.actions
            :actions="$this->getCachedFormActions()"
            :full-width="$this->hasFullWidthFormActions()"
        />
    </x-filament-panels::form>

    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::AUTH_LOGIN_FORM_AFTER, scopes: $this->getRenderHookScopes()) }}

    <div class="flex items-center gap-3 pt-1">
        <div class="h-px flex-1 bg-gray-200 dark:bg-white/10"></div>
        <span class="text-xs text-gray-400">Otra empresa</span>
        <div class="h-px flex-1 bg-gray-200 dark:bg-white/10"></div>
    </div>

    <p class="text-center text-sm text-gray-500 dark:text-gray-400">
        ¿Este no es tu espacio?
        <a href="{{ route('workspace.find') }}" class="font-semibold text-primary-600 hover:text-primary-500 dark:text-primary-400">
            Buscar mi empresa
        </a>
    </p>
</x-filament-panels::page.simple>
