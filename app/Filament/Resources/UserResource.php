<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages\CreateUser;
use App\Filament\Resources\UserResource\Pages\EditUser;
use App\Filament\Resources\UserResource\Pages\ListUsers;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Miembros del equipo';

    protected static ?string $navigationGroup = 'Configuración';

    protected static ?string $modelLabel = 'miembro del equipo';

    protected static ?string $pluralModelLabel = 'miembros del equipo';

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('staff.view') ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->can('staff.manage') ?? false;
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user()?->can('staff.manage') ?? false;
    }

    public static function canDelete(Model $record): bool
    {
        return ($auth = auth()->user())
            && $auth->can('staff.manage')
            && $auth->isNot($record);
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
                ->required()
                ->maxLength(150)
                ->unique(ignoreRecord: true),
            TextInput::make('password')
                ->label('Contraseña')
                ->password()
                ->revealable()
                ->required(fn (string $context) => $context === 'create')
                ->minLength(8)
                ->dehydrateStateUsing(fn (?string $state) => Hash::make($state))
                ->dehydrated(fn (?string $state) => filled($state))
                ->helperText(fn (string $context) => $context === 'edit' ? 'Déjalo vacío para no cambiarla.' : null),
            Select::make('roles')
                ->label('Rol')
                ->relationship('roles', 'name')
                ->multiple()
                ->preload()
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Nombre')->searchable()->sortable(),
                TextColumn::make('email')->label('Correo')->searchable()->sortable(),
                TextColumn::make('roles.name')
                    ->label('Rol')
                    ->badge()
                    ->separator(','),
                TextColumn::make('created_at')
                    ->label('Desde')
                    ->date('d/m/Y')
                    ->sortable(),
            ])
            ->defaultSort('name');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }
}
