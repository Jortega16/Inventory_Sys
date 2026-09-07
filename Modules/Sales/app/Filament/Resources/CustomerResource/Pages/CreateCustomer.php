<?php

declare(strict_types=1);

namespace Modules\Sales\Filament\Resources\CustomerResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Sales\Filament\Resources\CustomerResource;

class CreateCustomer extends CreateRecord
{
    protected static string $resource = CustomerResource::class;
}
