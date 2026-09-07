<?php

declare(strict_types=1);

/**
 * Catálogo de planes del SaaS. Puramente informativo por ahora: no hay
 * cobro real ni enforcement automático de límites — se muestra al staff
 * en el panel (Configuración > Mi Plan) para dejar el terreno listo para
 * cuando se conecte un proveedor de pagos (Stripe u otro).
 */
return [
    'free' => [
        'name' => 'Gratis',
        'price' => 0,
        'max_users' => 5,
        'max_products' => 100,
        'max_warehouses' => 2,
    ],
    'pro' => [
        'name' => 'Pro',
        'price' => 29,
        'max_users' => 20,
        'max_products' => 2000,
        'max_warehouses' => 10,
    ],
    'business' => [
        'name' => 'Business',
        'price' => 79,
        'max_users' => null, // null = ilimitado
        'max_products' => null,
        'max_warehouses' => null,
    ],
];
