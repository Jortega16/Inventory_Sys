<?php

declare(strict_types=1);

namespace App\Filament\Pages\Auth;

use Filament\Facades\Filament;
use Filament\Pages\Auth\Login as BaseLogin;

class Login extends BaseLogin
{
    public function mount(): void
    {
        if (Filament::auth()->check()) {
            redirect()->intended(Filament::getUrl());

            return;
        }

        // Cuando el "buscador de empresa" (workspace.find.store) nos redirige
        // aquí, viene con ?email=... — precargarlo evita que el usuario lo
        // tenga que escribir dos veces.
        $this->form->fill(
            request()->has('email') ? ['email' => request()->query('email')] : null,
        );
    }
}
