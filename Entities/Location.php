<?php

namespace Entities;

use Exception;

class Location
{
    public const ERROR_INVALID_DATA = 'Invalid data received';

    private float $latitude;
    private float $longitude;

    /**
     * Location constructor.
     * @param float $latitude
     * @param float $longitude
     * @throws Exception
     */
    public function __construct(float $latitude, float $longitude)
    {
        if ($latitude > 90 || $latitude < -90) {
            throw new Exception(self::ERROR_INVALID_DATA);
        }

        if ($longitude > 180 || $longitude < -180) {
            throw new Exception(self::ERROR_INVALID_DATA);
        }

        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    public function latitude(): float
    {
        return $this->latitude;
    }

    public function longitude(): float
    {
        return $this->longitude;
    }
}