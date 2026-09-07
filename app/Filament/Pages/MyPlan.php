<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Models\User;
use Filament\Pages\Page;
use Modules\Catalog\Models\Product;
use Modules\Warehouse\Models\Warehouse;

class MyPlan extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-sparkles';

    protected static ?string $navigationGroup = 'Configuración';

    protected static ?string $navigationLabel = 'Mi plan';

    protected static ?string $title = 'Mi plan';

    protected static string $view = 'filament.pages.my-plan';

    public static function canAccess(): bool
    {
        return auth()->user()?->can('staff.view') ?? false;
    }

    public function getPlan(): array
    {
        $key = tenant('plan') ?? 'free';

        return array_merge(
            ['key' => $key],
            config("plans.{$key}", config('plans.free')),
        );
    }

    public function getUsage(): array
    {
        return [
            'users' => [
                'label' => 'Usuarios',
                'current' => User::count(),
                'max' => $this->getPlan()['max_users'],
            ],
            'products' => [
                'label' => 'Productos',
                'current' => Product::count(),
                'max' => $this->getPlan()['max_products'],
            ],
            'warehouses' => [
                'label' => 'Almacenes',
                'current' => Warehouse::count(),
                'max' => $this->getPlan()['max_warehouses'],
            ],
        ];
    }
}
