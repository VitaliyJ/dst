<?php

namespace Thirdparties;

use Configs\Environment;
use Entities\Location;
use Exception;

class TimeZoneDB
{
    public const ERROR_EMPTY_API_KEY = 'Empty API key';
    public const ERROR_API_REQUEST = 'API request error';
    public const ERROR_THIRD_PARTY = 'Third party error';
    public const STATUS_FAILED = 'FAILED';

    private string $apiPath = 'http://api.timezonedb.com/v2.1/get-time-zone?format=json&by=position&fields=status,message,gmtOffset,dst,zoneStart,zoneEnd';

    /**
     * TimeZoneDB constructor.
     * @throws Exception
     */
    public function __construct()
    {
        $env = Environment::getInstance();
        $apiKey = $env->get('TIMEZONE_DB_API_KEY');

        if ($apiKey === '') {
            throw new Exception(self::ERROR_EMPTY_API_KEY);
        }

        $this->apiPath .= '&key=' . $apiKey;
    }

    public function setCoordinates(Location $location): void
    {
        $this->apiPath .= '&lat=' . $location->latitude() . '&lng=' . $location->longitude();
    }

    /**
     * @return object
     * @throws Exception
     */
    public function locationTimeInfo(): object
    {
        $response = $this->request();
        $timeInfo = (object)json_decode($response);

        if (
            false === property_exists($timeInfo, 'status')
            || $timeInfo->status === self::STATUS_FAILED
        ) {
            throw new Exception(self::ERROR_THIRD_PARTY);
        }

        return (object)json_decode($response);
    }

    /**
     * @return string
     * @throws Exception
     */
    private function request(): string
    {
        $response = file_get_contents($this->apiPath);
        if ($response === false) {
            throw new Exception(self::ERROR_API_REQUEST);
        }

        return $response;
    }
}