<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Tenant;
use Tests\TestCase;

class TenantRegistrationTest extends TestCase
{
    // Sin RefreshDatabase a propósito: registrar de verdad ejecuta
    // "CREATE DATABASE" contra Postgres, que NO puede correr dentro de una
    // transacción — y RefreshDatabase envuelve cada test en una. La limpieza
    // se hace a mano en tearDown, borrando el tenant (lo que dispara
    // DeleteDatabase) si el test llegó a crearlo.

    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate', ['--force' => true]);
    }

    protected function tearDown(): void
    {
        Tenant::find('empresa-de-prueba')?->delete();

        parent::tearDown();
    }

    public function test_landing_page_loads_on_the_central_domain(): void
    {
        $this->get('/')->assertOk()->assertSee('InventarySys');
    }

    public function test_registering_creates_tenant_domain_and_owner_with_admin_role(): void
    {
        $response = $this->post('/registro', [
            'company' => 'Empresa de Prueba',
            'name' => 'Ana Owner',
            'email' => 'ana@empresa-prueba.test',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $tenant = Tenant::find('empresa-de-prueba');

        $this->assertNotNull($tenant, 'El tenant debería haberse creado.');
        $this->assertSame('free', $tenant->plan);
        $this->assertSame('empresa-de-prueba.localhost', $tenant->domains()->first()->domain);

        $response->assertRedirect('http://empresa-de-prueba.localhost:8000/admin/login');

        tenancy()->initialize($tenant);
        $owner = \App\Models\User::where('email', 'ana@empresa-prueba.test')->first();
        $this->assertNotNull($owner);
        $this->assertTrue($owner->hasRole('Administrador'));
        tenancy()->end();
    }

    public function test_registration_rejects_a_duplicate_company_name(): void
    {
        $this->post('/registro', [
            'company' => 'Empresa de Prueba',
            'name' => 'Ana Owner',
            'email' => 'ana@empresa-prueba.test',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response = $this->post('/registro', [
            'company' => 'Empresa de Prueba',
            'name' => 'Otro Dueño',
            'email' => 'otro@empresa-prueba.test',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('company');
    }

    public function test_registration_rejects_a_reserved_slug(): void
    {
        $response = $this->post('/registro', [
            'company' => 'Admin',
            'name' => 'Alguien',
            'email' => 'alguien@test.test',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('company');
        $this->assertNull(Tenant::find('admin'));
    }
}
