<?php

declare(strict_types=1);

namespace Modules\Warehouse\Filament\Resources\StockTransferResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Modules\Warehouse\Filament\Resources\StockTransferResource;

class ListStockTransfers extends ListRecords
{
    protected static string $resource = StockTransferResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Nueva transferencia'),
        ];
    }
}
