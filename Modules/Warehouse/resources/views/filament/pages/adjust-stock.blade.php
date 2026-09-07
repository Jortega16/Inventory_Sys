<x-filament-panels::page>
    <form wire:submit="submit">
        {{ $this->form }}

        <x-filament::button type="submit" class="mt-6">
            Registrar ajuste
        </x-filament::button>
    </form>
</x-filament-panels::page>
