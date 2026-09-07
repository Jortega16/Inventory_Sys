<?php

declare(strict_types=1);

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // El formulario no pide contraseña al crear: se genera una temporal
        // y se le envía al usuario un enlace para que defina la suya (igual
        // que "olvidé mi contraseña"), en vez de pasarla en texto plano.
        $data['password'] = Hash::make(Str::random(40));

        return $data;
    }

    protected function afterCreate(): void
    {
        /** @var User $user */
        $user = $this->record;

        Password::sendResetLink(['email' => $user->email]);

        Notification::make()
            ->title('Invitación enviada')
            ->body("Se envió un correo a {$user->email} para que defina su contraseña.")
            ->success()
            ->send();
    }
}
