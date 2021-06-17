<?php

namespace Services;

use Repositories\Redis;

class CacheKeySet
{
    public const SET_KEY = 'location_time_info_keys';

    private Redis $repository;

    public function __construct()
    {
        $this->repository = Redis::getInstance();
    }

    public function addKey(string $key): void
    {
        $redis = $this->repository->connection();
        $redis->lPush(self::SET_KEY, $key);
    }

    public function keySet(): array
    {
        return $this->repository->connection()->lRange(self::SET_KEY, 0, -1);
    }
}