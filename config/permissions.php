<?php


return [
    'models' => [
        App\Models\Role::class,
        App\Models\User::class,
        App\Models\Shop::class,
    ],


    'prefixes' => [
        'view_any',
        'view',
        'create',
        'update',
        'restore',
        'delete',
        'force_delete',
    ],
];
