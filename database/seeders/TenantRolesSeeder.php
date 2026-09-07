<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class TenantRolesSeeder extends Seeder
{
    /**
     * Permisos base del staff, agrupados por módulo. Se amplía a medida
     * que se agregan más módulos (Warehouse, Purchasing, Sales, ...).
     */
    private const PERMISSIONS = [
        'catalog' => ['view', 'create', 'update', 'delete'],
        'warehouse' => ['view', 'create', 'update', 'delete', 'transfer'],
        'purchasing' => ['view', 'create', 'update', 'delete'],
        'sales' => ['view', 'create', 'update', 'delete'],
        'staff' => ['view', 'manage'],
    ];

    /**
     * Roles por defecto para cada empresa (tenant) nueva.
     */
    private const ROLES = [
        'Administrador' => '*', // todos los permisos
        'Almacenista' => [
            'catalog.view', 'catalog.update',
            'warehouse.view', 'warehouse.create', 'warehouse.update', 'warehouse.transfer',
        ],
        'Comprador' => [
            'catalog.view', 'catalog.create', 'catalog.update',
            'purchasing.view', 'purchasing.create', 'purchasing.update',
            'warehouse.view',
        ],
        'Vendedor' => [
            'catalog.view',
            'warehouse.view',
            'sales.view', 'sales.create', 'sales.update',
        ],
        'Solo lectura' => ['catalog.view', 'warehouse.view', 'purchasing.view', 'sales.view'],
    ];

    public function run(): void
    {
        $allPermissions = [];

        foreach (self::PERMISSIONS as $module => $actions) {
            foreach ($actions as $action) {
                $name = "{$module}.{$action}";
                Permission::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
                $allPermissions[] = $name;
            }
        }

        foreach (self::ROLES as $roleName => $permissions) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            $role->syncPermissions($permissions === '*' ? $allPermissions : $permissions);
        }
    }
}
