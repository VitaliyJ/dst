<?php

namespace Services;

use Repositories\Redis;
use Entities\Location;
use Entities\Time;
use Exception;

class GetLocationTimeInfo
{
    private Redis $repository;

    public function __construct()
    {
        $this->repository = Redis::getInstance();
    }

    /**
     * @param string $cityId
     * @param int $timestamp
     * @return int
     * @throws Exception
     */
    public function toLocalTime(string $cityId, int $timestamp): int
    {
        return $this->time($cityId)->toLocal($timestamp);
    }

    /**
     * @param string $cityId
     * @param int $timestamp
     * @return int
     * @throws Exception
     */
    public function fromLocalTime(string $cityId, int $timestamp): int
    {
        return $this->time($cityId)->fromLocal($timestamp);
    }

    /**
     * @param string $cityId
     * @return Time
     * @throws Exception
     */
    private function time(string $cityId): Time
    {
        $city = (new GetCityInfo())->city($cityId);
        $cache = $this->fromCache($city->location());

        if (empty($cache)) {
            $service = new SetLocationTimeInfo;
            $service->exec($city);

            return $city->time();
        }

        return new Time(
            $cache['dst'],
            (int)$cache['gmtOffset'],
            $cache['dst'] ? (int)$cache['zoneStart'] : null,
            $cache['dst'] ? (int)$cache['zoneEnd'] : null,
        );
    }

    private function fromCache(Location $location): array
    {
        $key = SetLocationTimeInfo::locationTimeKey($location);
        $connection = $this->repository->connection();

        return $connection->hGetAll($key);
    }
}