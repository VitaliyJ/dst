<?php

namespace Repositories;

use Configs\Environment;
use PDO;
use Exception;

class Mysql
{
    private static self $instance;
    private string $database;
    private PDO $connection;

    private function __construct()
    {
        $env = Environment::getInstance();
        $this->database = $env->get('MYSQL_DATABASE');

        $this->connect(
            'mysql',
            $env->get('MYSQL_USER'),
            $env->get('MYSQL_PASSWORD'),
            $env->get('MYSQL_DATABASE'),
        );
    }

    private function connect(
        string $host,
        string $user,
        string $password,
        string $db
    ): void {
        $this->connection = new PDO("mysql:host=$host;dbname=$db", $user, $password);
    }

    protected function __clone() {}

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

    public function database(): string
    {
        return $this->database;
    }

    public function connection(): PDO
    {
        return $this->connection;
    }
}