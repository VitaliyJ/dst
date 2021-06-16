<?php

namespace Configs;

use Exception;

class Environment
{
    private static self $instance;
    private array $variables = [];

    private function __construct()
    {
        $this->setVariables();
    }

    private function setVariables(): void
    {
        $matches = $this->parseEnvFile();

        if (!isset($matches['variables']) || !isset($matches['values'])) {
            return;
        }

        foreach ($matches['variables'] as $key => $variable) {
            $this->variables[$variable] = $matches['values'][$key] ?? '';
        }
    }

    private function parseEnvFile(): array
    {
        $enfPath = __DIR__ . '/../.env';
        if (file_exists($enfPath) === false) {
            return [];
        }

        $envContent = file_get_contents($enfPath);
        preg_match_all('/(?<variables>[A-z_]+)=(?<values>[A-z0-9]*)/', $envContent, $matches);

        return $matches;
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

    public function get(string $variable): string
    {
        return $this->variables[$variable] ?? '';
    }
}