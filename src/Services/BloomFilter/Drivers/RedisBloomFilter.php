<?php

namespace Jacksmall\Bloomfilter\Services\BloomFilter\Drivers;

class RedisBloomFilter
{
    protected $redis;
    protected $keyPrefix;
    protected $size;
    protected $hashes;

    public function __construct($redis, $keyPrefix, $size, $hashes)
    {
        $this->redis = $redis;
        $this->keyPrefix = $keyPrefix;
        $this->size = $size;
        $this->hashes = $hashes;
    }

    /**
     * @param $value
     * @return bool
     */
    public function exists($value)
    {
        $key = $this->keyPrefix . 'main';
        $positions = $this->getBitPositions($value);

        $pipeline = $this->redis->pipeline();
        foreach ($positions as $position) {
            $pipeline->getBit($key, $position);
        }

        $results = $pipeline->execute();

        return !in_array(0, $results, true);
    }

    /**
     * @param $value
     * @return void
     */
    public function add($value)
    {
        $key = $this->keyPrefix.'main';
        $positions = $this->getBitPositions($value);

        $pipeline = $this->redis->pipeline();
        foreach ($positions as $position) {
            $pipeline->setBit($key, $position, 1);
        }

        $pipeline->execute();
    }

    /**
     * @param array $values
     * @return void
     */
    public function addMany(array $values)
    {
        $key = $this->keyPrefix.'main';
        $allPositions = [];

        foreach ($values as $value) {
            $positions = $this->getBitPositions($value);
            foreach ($positions as $position) {
                $allPositions[$position] = 1; // 去重
            }
        }

        $pipeline = $this->redis->pipeline();
        foreach (array_keys($allPositions) as $position) {
            $pipeline->setBit($key, $position, 1);
        }
        $pipeline->execute();
    }

    /**
     * @param $value
     * @return array
     */
    protected function getBitPositions($value)
    {
        $positions = [];
        $value = (string)$value;
        $hash1 = crc32($value);
        $hash2 = $this->fnv1a($value);

        for ($i = 0; $i < $this->hashes; $i++) {
            $positions[] = abs($hash1 + $i * $hash2) % $this->size;
        }

        return $positions;
    }

    /**
     * @param $value
     * @return int
     */
    protected function fnv1a($value)
    {
        $hash = 2166136261; // FNV offset_basis
        $len = strlen($value);

        for ($i = 0; $i < $len; $i++) {
            $hash ^= ord($value[$i]);
            $hash += ($hash << 1) + ($hash << 4) + ($hash << 7) + ($hash << 8) + ($hash << 24);
        }

        return $hash & 0xffffffff; // 32位限制
    }
}