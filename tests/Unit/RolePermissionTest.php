<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\User;
use Database\Seeders\TenantRolesSeeder;
use Tests\TenantTestCase;

class RolePermissionTest extends TenantTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        (new TenantRolesSeeder())->run();
    }

    public function test_administrador_can_do_everything(): void
    {
        $user = User::factory()->create();
        $user->assignRole('Administrador');

        $this->assertTrue($user->can('catalog.create'));
        $this->assertTrue($user->can('staff.manage'));
        $this->assertTrue($user->can('sales.delete'));
    }

    public function test_almacenista_cannot_manage_staff_or_purchasing(): void
    {
        $user = User::factory()->create();
        $user->assignRole('Almacenista');

        $this->assertTrue($user->can('warehouse.transfer'));
        $this->assertTrue($user->can('catalog.view'));
        $this->assertFalse($user->can('staff.manage'));
        $this->assertFalse($user->can('purchasing.create'));
    }

    public function test_vendedor_can_sell_but_not_purchase_or_manage_warehouses(): void
    {
        $user = User::factory()->create();
        $user->assignRole('Vendedor');

        $this->assertTrue($user->can('sales.create'));
        $this->assertTrue($user->can('warehouse.view'));
        $this->assertFalse($user->can('warehouse.create'));
        $this->assertFalse($user->can('purchasing.view'));
    }

    public function test_solo_lectura_cannot_write_anywhere(): void
    {
        $user = User::factory()->create();
        $user->assignRole('Solo lectura');

        $this->assertTrue($user->can('catalog.view'));
        $this->assertFalse($user->can('catalog.create'));
        $this->assertFalse($user->can('catalog.update'));
        $this->assertFalse($user->can('warehouse.transfer'));
    }

    public function test_user_without_role_can_do_nothing(): void
    {
        $user = User::factory()->create();

        $this->assertFalse($user->can('catalog.view'));
    }
}
