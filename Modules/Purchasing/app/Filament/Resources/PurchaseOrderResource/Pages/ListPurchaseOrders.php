<?php

declare(strict_types=1);

namespace Modules\Purchasing\Filament\Resources\PurchaseOrderResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Modules\Purchasing\Filament\Resources\PurchaseOrderResource;

class ListPurchaseOrders extends ListRecords
{
    protected static string $resource = PurchaseOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Nueva orden'),
        ];
    }
}
