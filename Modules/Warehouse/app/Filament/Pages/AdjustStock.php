<?php

declare(strict_types=1);

namespace Modules\Warehouse\Filament\Pages;

use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Modules\Catalog\Models\Product;
use Modules\Warehouse\Models\StockLevel;
use Modules\Warehouse\Models\Warehouse;

class AdjustStock extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static ?string $navigationGroup = 'Almacenes';

    protected static ?string $navigationLabel = 'Ajustar stock';

    protected static ?string $title = 'Ajustar stock';

    protected static string $view = 'warehouse::filament.pages.adjust-stock';

    public ?array $data = [];

    public static function canAccess(): bool
    {
        return auth()->user()?->can('warehouse.transfer') ?? false;
    }

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('product_id')
                    ->label('Producto')
                    ->options(fn () => Product::query()->pluck('name', 'id'))
                    ->searchable()
                    ->required()
                    ->live(),
                Select::make('warehouse_id')
                    ->label('Almacén')
                    ->options(fn () => Warehouse::query()->where('is_active', true)->pluck('name', 'id'))
                    ->required()
                    ->live(),
                Placeholder::make('current_quantity')
                    ->label('Cantidad actual en sistema')
                    ->content(function (callable $get) {
                        if (! $get('product_id') || ! $get('warehouse_id')) {
                            return '—';
                        }

                        return (string) (StockLevel::query()
                            ->where('product_id', $get('product_id'))
                            ->where('warehouse_id', $get('warehouse_id'))
                            ->value('quantity') ?? 0);
                    }),
                TextInput::make('counted_quantity')
                    ->label('Cantidad contada físicamente')
                    ->numeric()
                    ->minValue(0)
                    ->required(),
                Textarea::make('reason')
                    ->label('Motivo del ajuste')
                    ->required()
                    ->rows(2),
            ])
            ->statePath('data');
    }

    public function submit(): void
    {
        $state = $this->form->getState();

        $current = StockLevel::query()
            ->where('product_id', $state['product_id'])
            ->where('warehouse_id', $state['warehouse_id'])
            ->value('quantity') ?? 0;

        $delta = (int) $state['counted_quantity'] - (int) $current;

        if ($delta === 0) {
            Notification::make()
                ->title('Sin cambios: la cantidad contada es igual a la del sistema')
                ->warning()
                ->send();

            return;
        }

        StockLevel::adjust(
            $state['product_id'],
            $state['warehouse_id'],
            $delta,
            'adjustment',
            null,
            $state['reason'],
        );

        Notification::make()
            ->title('Stock ajustado: ' . ($delta > 0 ? "+{$delta}" : $delta))
            ->success()
            ->send();

        $this->form->fill();
    }
}
