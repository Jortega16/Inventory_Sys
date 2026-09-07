<?php

declare(strict_types=1);

namespace Modules\Purchasing\Filament\Resources\PurchaseOrderResource\Pages;

use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Modules\Purchasing\Filament\Resources\PurchaseOrderResource;

class EditPurchaseOrder extends EditRecord
{
    protected static string $resource = PurchaseOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('markAsReceived')
                ->label('Marcar como recibida')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->modalDescription('Esto sumará las cantidades de la orden al stock del almacén destino.')
                ->visible(fn () => $this->record->status !== 'received')
                ->action(function () {
                    $this->record->markAsReceived();

                    Notification::make()
                        ->title('Orden recibida y stock actualizado')
                        ->success()
                        ->send();

                    $this->fillForm();
                }),
            DeleteAction::make()
                ->visible(fn () => $this->record->status !== 'received'),
        ];
    }
}
