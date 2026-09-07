<?php

declare(strict_types=1);

namespace Modules\Warehouse\Filament\Resources\StockTransferResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Warehouse\Filament\Resources\StockTransferResource;

class CreateStockTransfer extends CreateRecord
{
    protected static string $resource = StockTransferResource::class;
}
