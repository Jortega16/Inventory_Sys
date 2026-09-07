<?php

declare(strict_types=1);

namespace Modules\Purchasing\Filament\Resources;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Modules\Purchasing\Filament\Resources\SupplierResource\Pages\CreateSupplier;
use Modules\Purchasing\Filament\Resources\SupplierResource\Pages\EditSupplier;
use Modules\Purchasing\Filament\Resources\SupplierResource\Pages\ListSuppliers;
use Modules\Purchasing\Models\Supplier;

class SupplierResource extends Resource
{
    protected static ?string $model = Supplier::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?string $navigationGroup = 'Compras';

    protected static ?string $navigationLabel = 'Proveedores';

    protected static ?string $modelLabel = 'proveedor';

    protected static ?string $pluralModelLabel = 'proveedores';

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('purchasing.view') ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->can('purchasing.create') ?? false;
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user()?->can('purchasing.update') ?? false;
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->user()?->can('purchasing.delete') ?? false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('name')
                ->label('Nombre')
                ->required()
                ->maxLength(150),
            TextInput::make('contact_name')
                ->label('Persona de contacto')
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
                TextColumn::make('contact_name')->label('Contacto'),
                TextColumn::make('email')->label('Correo'),
                TextColumn::make('phone')->label('Teléfono'),
                IconColumn::make('is_active')->label('Activo')->boolean(),
            ])
            ->defaultSort('name');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSuppliers::route('/'),
            'create' => CreateSupplier::route('/create'),
            'edit' => EditSupplier::route('/{record}/edit'),
        ];
    }
}
