<?php

namespace Http;

class Response
{
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function toJson(): void
    {
        header('Content-Type: application/json');
        echo json_encode($this->data);
    }

    public function error(int $code = 404): self
    {
        http_response_code($code);
        return $this;
    }
}