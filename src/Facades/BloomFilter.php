<?php

namespace Jacksmall\Bloomfilter\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static bool exists(string $value)
 * @method static void add(string $value)
 * @method static void addMany(array $values)
 * @method static mixed connection(string $name)
 */
class BloomFilter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'BloomFilter';
    }
}