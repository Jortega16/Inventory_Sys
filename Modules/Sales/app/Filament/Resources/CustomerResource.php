<?php

declare(strict_types=1);

namespace Modules\Sales\Filament\Resources;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Modules\Sales\Filament\Resources\CustomerResource\Pages\CreateCustomer;
use Modules\Sales\Filament\Resources\CustomerResource\Pages\EditCustomer;
use Modules\Sales\Filament\Resources\CustomerResource\Pages\ListCustomers;
use Modules\Sales\Models\Customer;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Ventas';

    protected static ?string $navigationLabel = 'Clientes';

    protected static ?string $modelLabel = 'cliente';

    protected static ?string $pluralModelLabel = 'clientes';

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('sales.view') ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->can('sales.create') ?? false;
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user()?->can('sales.update') ?? false;
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->user()?->can('sales.delete') ?? false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('name')
                ->label('Nombre')
                ->required()
                ->maxLength(150),
            TextInput::make('email')
                ->label('Correo')
                ->email()
                ->maxLength(150),
            TextInput::make('phone')
                ->label('Teléfono')
                ->tel()
                ->maxLength(30),
            TextInput::make('address')
                ->label('Dirección')
                ->maxLength(255)
                ->columnSpanFull(),
            Toggle::make('is_active')
                ->label('Activo')
                ->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Nombre')->searchable()->sortable(),
                TextColumn::make('email')->label('Correo'),
                TextColumn::make('phone')->label('Teléfono'),
                IconColumn::make('is_active')->label('Activo')->boolean(),
            ])
            ->defaultSort('name');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCustomers::route('/'),
            'create' => CreateCustomer::route('/create'),
            'edit' => EditCustomer::route('/{record}/edit'),
        ];
    }
}
