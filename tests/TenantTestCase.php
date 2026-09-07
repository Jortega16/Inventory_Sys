<?php

declare(strict_types=1);

namespace Tests;

use App\Models\Tenant;
use App\Models\User;
use Database\Seeders\TenantRolesSeeder;
use Illuminate\Support\Facades\DB;

/**
 * Base para tests que necesitan correr DENTRO del contexto de un tenant
 * (modelos de Catalog/Warehouse/Purchasing/Sales viven en la BD del tenant).
 *
 * Usa un único tenant de pruebas reutilizado entre tests (crear+migrar una
 * base de datos completa por test sería muy lento); cada test corre dentro
 * de una transacción sobre la conexión "tenant" que se revierte al terminar,
 * así los tests quedan aislados sin tener que recrear el tenant cada vez.
 */
abstract class TenantTestCase extends TestCase
{
    protected static ?Tenant $tenant = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate', ['--force' => true]);

        static::$tenant ??= $this->findOrCreateTestTenant();

        tenancy()->initialize(static::$tenant);

        DB::connection('tenant')->beginTransaction();
    }

    protected function tearDown(): void
    {
        DB::connection('tenant')->rollBack();

        tenancy()->end();

        parent::tearDown();
    }

    private function findOrCreateTestTenant(): Tenant
    {
        $tenant = Tenant::find('phpunit');

        if ($tenant) {
            return $tenant;
        }

        // Si un run anterior dejó la BD física del tenant de pruebas (p. ej.
        // porque ese run usó RefreshDatabase y borró la fila central pero no
        // la BD de Postgres), hay que tirarla antes de recrear el tenant.
        DB::statement('DROP DATABASE IF EXISTS tenantphpunit');

        $tenant = Tenant::create(['id' => 'phpunit', 'plan' => 'free']);
        $tenant->domains()->create(['domain' => 'phpunit.localhost']);

        return $tenant;
    }

    /**
     * Crea un usuario dentro del tenant actual con el rol dado.
     */
    protected function actingAsTenantUser(string $role = 'Administrador'): User
    {
        $user = User::factory()->create();
        $user->assignRole($role);

        $this->actingAs($user);

        return $user;
    }
}
