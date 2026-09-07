<?php

declare(strict_types=1);

namespace Modules\Purchasing\Filament\Resources\PurchaseOrderResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Purchasing\Filament\Resources\PurchaseOrderResource;

class CreatePurchaseOrder extends CreateRecord
{
    protected static string $resource = PurchaseOrderResource::class;
}
