<?php
return [
    'default' => 'default',
    'connections' => [
        'default' => [
            'driver' => 'redis',
            'connection' => 'default', // Laravel Redis 连接名
            'key_prefix' => 'bloomfilter:', // redis 键前缀
            'size' => 10000000, // 位数组大小（bits）
            'hashes' => 5 // 哈希函数数量
        ],
        'users' => [
            'driver' => 'redis',
            'connection' => 'default',
            'key_prefix' => 'bloomfilter:users:',
            'size' => 5000000,
            'hashes' => 6
        ]
    ]
];