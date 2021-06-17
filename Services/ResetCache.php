<?php

namespace Services;

use Repositories\Redis;

class ResetCache
{
    private Redis $repository;
    private CacheKeySet $keySetService;

    public function __construct()
    {
        $this->repository = Redis::getInstance();
        $this->keySetService = new CacheKeySet;
    }

    public function reset(): void
    {
        $keys = $this->keySetService->keySet();

        foreach ($keys as $key) {
            $this->repository->connection()->del($key);
        }

        $this->repository->connection()->del(CacheKeySet::SET_KEY);
    }
}