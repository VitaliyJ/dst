<?php

namespace Repositories;

use Configs\Environment;
use Redis as RedisExtension;
use Exception;

class Redis
{
    private static self $instance;
    private RedisExtension $connection;

    private function __construct()
    {
        $env = Environment::getInstance();
        $this->connect(
            $env->get('REDIS_PORT'),
            $env->get('REDIS_PASSWORD'),
        );
    }

    private function connect(string $port, string $password): void
    {
        $this->connection = new RedisExtension;
        $this->connection->connect('redis');
        $this->connection->auth($password);
    }

    private function __clone() {}

    /**
     * @throws Exception
     */
    public function __wakeup()
    {
        throw new Exception("Cannot unserialize a singleton.");
    }

    public static function getInstance(): self
    {
        if (!isset(self::$instance)) {
            self::$instance = new static;
        }

        return self::$instance;
    }

    public function connection(): RedisExtension
    {
        return $this->connection;
    }
}