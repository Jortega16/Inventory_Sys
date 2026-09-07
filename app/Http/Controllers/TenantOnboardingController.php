<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TenantOnboardingController extends Controller
{
    /**
     * Palabras reservadas que no pueden usarse como subdominio de un tenant.
     */
    private const RESERVED_SLUGS = ['www', 'admin', 'app', 'api', 'localhost', 'central'];

    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'company' => ['required', 'string', 'max:80'],
            'name' => ['required', 'string', 'max:80'],
            'email' => ['required', 'email', 'max:150'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $slug = Str::slug($validated['company']);

        if (in_array($slug, self::RESERVED_SLUGS, true) || $slug === '') {
            return back()->withErrors(['company' => 'Elige otro nombre de empresa.'])->withInput();
        }

        if (Tenant::find($slug)) {
            return back()->withErrors(['company' => 'Ese nombre de empresa ya está en uso.'])->withInput();
        }

        $domain = "{$slug}.localhost";

        // Crear el tenant dispara CreateDatabase + MigrateDatabase (ver TenancyServiceProvider).
        // Toda cuenta nueva arranca en el plan gratuito (ver config/plans.php).
        $tenant = Tenant::create(['id' => $slug, 'plan' => 'free']);
        $tenant->domains()->create(['domain' => $domain]);

        tenancy()->initialize($tenant);

        $owner = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // TenantRolesSeeder ya corrió (ver TenancyServiceProvider) y creó el rol Administrador.
        $owner->assignRole('Administrador');

        tenancy()->end();

        $port = $request->getPort();
        $portSuffix = in_array($port, [80, 443], true) ? '' : ":{$port}";

        return redirect("http://{$domain}{$portSuffix}/admin/login")
            ->with('status', "¡Cuenta creada! Tu espacio de trabajo es {$domain}");
    }

    public function showLogin(): View
    {
        return view('auth.find-workspace');
    }

    public function login(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:150'],
        ]);

        $tenant = $this->findTenantForEmail($validated['email']);

        if (! $tenant) {
            return back()
                ->withErrors(['email' => 'No encontramos ninguna cuenta con ese correo.'])
                ->withInput();
        }

        $domain = $tenant->domains()->first()?->domain;
        $port = $request->getPort();
        $portSuffix = in_array($port, [80, 443], true) ? '' : ":{$port}";

        return redirect("http://{$domain}{$portSuffix}/admin/login?email=" . urlencode($validated['email']));
    }

    /**
     * Busca en qué tenant vive un correo, recorriendo cada base de datos.
     * Sencillo y correcto para la escala actual (decenas de tenants); si el
     * número de empresas crece mucho, conviene mantener un índice central
     * (tabla correo → tenant_id) actualizado al crear/invitar usuarios en
     * vez de recorrerlos todos en cada login.
     */
    private function findTenantForEmail(string $email): ?Tenant
    {
        foreach (Tenant::all() as $tenant) {
            tenancy()->initialize($tenant);

            $exists = User::where('email', $email)->exists();

            tenancy()->end();

            if ($exists) {
                return $tenant;
            }
        }

        return null;
    }
}
