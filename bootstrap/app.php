<?php

use Filament\Http\Middleware\AuthenticateSession;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Identifica el tenant por dominio en TODAS las peticiones web (incluye
        // livewire/update), no solo las rutas del panel. Ver TenancyServiceProvider
        // para el onFail que deja pasar sin tenant cuando el dominio es central.
        $middleware->web(append: [
            InitializeTenancyByDomain::class,
            AuthenticateSession::class,
        ]);

        // La API vive en el dominio de cada tenant (empresa.localhost/api/...) y
        // exige tenant identificado — a diferencia del grupo web, aquí SÍ se
        // rechaza el dominio central (no hay "modo API" sin empresa).
        $middleware->api(prepend: [
            InitializeTenancyByDomain::class,
            PreventAccessFromCentralDomains::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();
