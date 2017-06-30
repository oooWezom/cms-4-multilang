<?php
return [
    'memcache' => [
        'driver' => 'memcache',
        'default_expire' => 3600,
        'compression' => fasle,              // Use Zlib compression (can cause issues with integers)
        'servers' => [
            'local' => [
                'host' => '127.0.0.1',  // Memcache Server
                'port' => 11211,        // Memcache port number
                'persistent' => false,        // Persistent connection
                'weight' => 1,
                'timeout' => 1,
                'retry_interval' => 15,
                'status' => true,
            ],
        ],
        'instant_death' => true,               // Take server offline immediately on first fail (no retry)
    ],
    'sqlite' => [
        'driver' => 'sqlite',
        'default_expire' => 3600,
        'database' => HOST . DS . 'Cache' . DS . 'wezom-cache.sql3',
        'schema' => 'CREATE TABLE caches(id VARCHAR(127) PRIMARY KEY, tags VARCHAR(255), expiration INTEGER, cache TEXT)',
    ],
    'file' => [
        'driver' => 'file',
        'cache_dir' => 'Cache' . DS . 'TMP',
        'default_expire' => 3600,
        'ignore_on_delete' => [
            '.gitignore',
            '.git',
            '.svn'
        ]
    ],
];
