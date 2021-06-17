<?php

namespace Entities;

use Exception;

class City
{
    public const ERROR_INVALID_ID_LENGTH = 'Invalid city ID length';

    private string $id;
    private string $countryISO;
    private string $name;
    private Location $location;

    /**
     * City constructor.
     * @param string $id
     * @param string $countryISO
     * @param string $name
     * @param Location $location
     * @throws Exception
     */
    public function __construct(
        string $id,
        string $countryISO,
        string $name,
        Location $location
    ) {
        if (strlen($id) !== 36) {
            throw new Exception(self::ERROR_INVALID_ID_LENGTH);
        }

        $this->id = $id;
        $this->countryISO = $countryISO;
        $this->name = $name;
        $this->location = $location;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function countryISO(): string
    {
        return $this->countryISO;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function location(): Location
    {
        return $this->location;
    }
}