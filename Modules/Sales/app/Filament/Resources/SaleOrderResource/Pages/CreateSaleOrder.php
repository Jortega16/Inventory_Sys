<?php

declare(strict_types=1);

namespace Modules\Sales\Filament\Resources\SaleOrderResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Sales\Filament\Resources\SaleOrderResource;

class CreateSaleOrder extends CreateRecord
{
    protected static string $resource = SaleOrderResource::class;
}
