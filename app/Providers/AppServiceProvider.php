<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Los enlaces de "restablecer contraseña" (invitación de staff y
        // "olvidé mi contraseña") deben apuntar a la página de Filament,
        // no a la ruta genérica de Laravel (que no existe en este proyecto).
        ResetPassword::createUrlUsing(function ($notifiable, string $token) {
            return URL::temporarySignedRoute('filament.admin.auth.password-reset.reset', now()->addMinutes(60), [
                'email' => $notifiable->getEmailForPasswordReset(),
                'token' => $token,
            ]);
        });
    }
}
