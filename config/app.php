<?php

declare(strict_types=1);

return [
    'app_name' => 'NYCK',
    'timezone' => 'Asia/Shanghai',
    'default_short_code_length' => 6,
    'session_name' => 'short_url_admin',
    'storage_files' => [
        'users' => 'users.json',
        'links' => 'links.json',
        'logs' => 'logs.json',
        'config' => 'config.json',
    ],
    'defaults' => [
        'users' => [
            'items' => [],
        ],
        'links' => [
            'items' => [],
        ],
        'logs' => [
            'items' => [],
        ],
        'config' => [
            'site_name' => 'NYCK',
            'default_short_code_length' => 6,
        ],
    ],
    'bootstrap' => [
        'admin' => [
            'username' => 'admin',
            'password' => 'admin123456',
        ],
        'site_config' => [
            'site_name' => 'NYCK',
            'default_short_code_length' => 6,
        ],
    ],
];
