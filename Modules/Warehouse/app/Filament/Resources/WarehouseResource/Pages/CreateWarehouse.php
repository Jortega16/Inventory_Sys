<?php

declare(strict_types=1);

namespace Modules\Warehouse\Filament\Resources\WarehouseResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Warehouse\Filament\Resources\WarehouseResource;

class CreateWarehouse extends CreateRecord
{
    protected static string $resource = WarehouseResource::class;
}
