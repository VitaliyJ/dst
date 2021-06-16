<?php

namespace Http;

use Exception;

class Request
{
    private static self $instance;
    private string $route;
    private array $routeParams = [];

    protected function __construct()
    {
        $this->route = $_SERVER['REQUEST_URI'];
        $this->parseRouteParams();
    }

    private function parseRouteParams(): void
    {
        preg_match_all('~/(?<routeItems>\w+)/(?<routeParams>[A-z0-9]+)~', $this->route, $matches);
        if (!isset($matches['routeItems']) || !isset($matches['routeParams'])) {
            return;
        }

        foreach ($matches['routeItems'] as $key => $routeItem) {
            $this->routeParams[$routeItem] = $matches['routeParams'][$key];
        }
    }

    protected function __clone() {}

    /**
     * @throws Exception
     */
    public function __wakeup()
    {
        throw new Exception("Cannot unserialize a singleton.");
    }

    public static function getInstance(): Request
    {
        if (!isset(self::$instance)) {
            self::$instance = new static;
        }

        return self::$instance;
    }

    public function paramFromURI(string $name): ?string
    {
        return $this->routeParams[$name] ?? null;
    }
}