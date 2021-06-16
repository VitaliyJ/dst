<?php

namespace Services;

use Entities\Location;
use Exception;
use PDOStatement;
use Entities\City;
use Repositories\Mysql;

class GetCityInfo
{
    public const ERROR_QUERY_FAILED = 'City not found';

    private Mysql $repository;

    public function __construct()
    {
        $this->repository = Mysql::getInstance();
    }

    /**
     * @param string $id
     * @return City
     * @throws Exception
     */
    public function city(string $id): City
    {
        $queryResult = $this->select($id);
        $location = $this->getLocation($queryResult);

        return new City(
            $queryResult->id,
            $queryResult->country_iso3,
            $queryResult->name,
            $location,
        );
    }

    /**
     * @param string $id
     * @return object
     * @throws Exception
     */
    private function select(string $id): object
    {
        $statement = $this->prepareQuery();
        $statement->execute([
            ':id' => $id,
        ]);

        $result = $statement->fetchObject();
        if ($result === false) {
            throw new Exception(self::ERROR_QUERY_FAILED, 400);
        }

        return $result;
    }

    private function prepareQuery(): PDOStatement
    {
        $connection = $this->repository->connection();
        return $connection->prepare('SELECT * FROM ' . $this->repository->database() . '.city WHERE id = :id');
    }

    /**
     * @param object $data
     * @return Location
     * @throws Exception
     */
    private function getLocation(object $data): Location
    {
        return new Location($data->latitude, $data->longitude);
    }
}