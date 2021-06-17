<?php

namespace Services;

use Entities\Location;
use Entities\Time;
use Repositories\Redis;
use Thirdparties\TimeZoneDB;
use Exception;

class SetLocationTimeInfo
{
    public const CACHE_EXPIRY_TIME = 60 * 60 * 24 * 7; // 1 week

    private Redis $redisRepository;

    public function __construct()
    {
        $this->redisRepository = Redis::getInstance();
    }

    /**
     * @param Location $location
     * @return Time
     * @throws Exception
     */
    public function updatedTime(Location $location): Time
    {
        $timeInfo = $this->getTime($location);
        $this->storeInRedis($location, $timeInfo);

        return $timeInfo;
    }

    /**
     * @param Location $location
     * @return Time
     * @throws Exception
     */
    private function getTime(Location $location): Time
    {
        $timeZoneDB = new TimeZoneDB;
        $timeZoneDB->setCoordinates($location);
        $data = $timeZoneDB->locationTimeInfo();

        return new Time(
            $data->dst,
            $data->gmtOffset,
            $data->zoneStart,
            $data->zoneEnd,
        );
    }

    private function storeInRedis(Location $location, Time $time): void
    {
        $redis = $this->redisRepository->connection();
        $key = self::locationTimeKey($location);
        $redis->hset($key, 'dst', $time->dst());
        $redis->hset($key, 'gmtOffset', $time->gmtOffset());
        $redis->hset($key, 'zoneStart', $time->dstStart());
        $redis->hset($key, 'zoneEnd', $time->dstEnd());
        $redis->expire($key, self::CACHE_EXPIRY_TIME);
    }

    public static function locationTimeKey(Location $location): string
    {
        return 'location_key_' . $location->latitude() . $location->longitude();
    }
}