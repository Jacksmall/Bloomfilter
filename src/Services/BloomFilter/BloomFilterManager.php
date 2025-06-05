<?php

namespace Jacksmall\Bloomfilter\Services\BloomFilter;

use Jacksmall\Bloomfilter\Services\BloomFilter\Drivers\RedisBloomFilter;

class BloomFilterManager
{
    protected $config;
    protected $drivers = [];

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function connection($name = null)
    {
        $name = $name ?: $this->config['default'];

        if (isset($this->drivers[$name])) {
            return $this->drivers[$name];
        }

        $config = $this->getConfig($name);
        $driver = $this->createDriver($config);

        return $this->drivers[$name] = $driver;
    }

    protected function getConfig($name)
    {
        if (!isset($this->config['connections'][$name])) {
            throw new \InvalidArgumentException("BloomFilter connection [{$name}] is not defined.");
        }

        return $this->config['connections'][$name];
    }

    protected function createDriver(array $config)
    {
        $driverMethod = 'create'.ucfirst($config['driver']).'Driver';
        if (!method_exists($this, $driverMethod)) {
            throw new \InvalidArgumentException("BloomFilter driver [{$config['driver']}] is not supported.");
        }

        return $this->{$driverMethod}($config);
    }

    protected function createRedisDriver(array $config)
    {
        return new RedisBloomFilter(
            app('redis')->connection($config['connection']),
            $config['key_prefix'],
            $config['size'],
            $config['hashes']
        );
    }

    public function __call($method, $parameters)
    {
        return $this->connection()->$method(...$parameters);
    }
}