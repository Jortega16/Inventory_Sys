<?php

declare(strict_types=1);

namespace Modules\Sales\Filament\Resources\SaleOrderResource\Pages;

use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Validation\ValidationException;
use Modules\Sales\Filament\Resources\SaleOrderResource;

class EditSaleOrder extends EditRecord
{
    protected static string $resource = SaleOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('confirmSale')
                ->label('Confirmar venta')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->modalDescription('Esto descontará las cantidades del stock del almacén origen.')
                ->visible(fn () => $this->record->status !== 'confirmed')
                ->action(function () {
                    try {
                        $this->record->confirmSale();
                    } catch (ValidationException $exception) {
                        Notification::make()
                            ->title('No se pudo confirmar la venta')
                            ->body(collect($exception->errors())->flatten()->join(' '))
                            ->danger()
                            ->send();

                        return;
                    }

                    Notification::make()
                        ->title('Venta confirmada y stock actualizado')
                        ->success()
                        ->send();

                    $this->fillForm();
                }),
            DeleteAction::make()
                ->visible(fn () => $this->record->status !== 'confirmed'),
        ];
    }
}
