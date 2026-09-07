<?php

declare(strict_types=1);

namespace Modules\Sales\Filament\Resources\SaleOrderResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Modules\Sales\Filament\Resources\SaleOrderResource;

class ListSaleOrders extends ListRecords
{
    protected static string $resource = SaleOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Nueva venta'),
        ];
    }
}
