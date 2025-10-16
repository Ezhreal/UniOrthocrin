<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Queue Override Configuration
    |--------------------------------------------------------------------------
    |
    | Configurações para sobrescrever o .env e forçar uso de filas assíncronas
    |
    */

    'default' => 'database',
    
    'connections' => [
        'database' => [
            'driver' => 'database',
            'table' => 'jobs',
            'queue' => 'default',
            'retry_after' => 600, // 10 minutos
            'after_commit' => false,
        ],
    ],
];
