<?php

namespace Http\Controllers;

use Exception;
use Http\Response;
use Services\GetLocationTimeInfo;

class DatetimeController
{
    private GetLocationTimeInfo $service;

    public function __construct()
    {
        $this->service = new GetLocationTimeInfo;
    }

    public function toLocaltime(string $cityId, int $timestamp): Response
    {
        try {
            $response = new Response([
                'timestamp' => $this->service->toLocalTime($cityId, $timestamp),
            ]);
        } catch (Exception $e) {
            $response = new Response(['error_message' => $e->getMessage()]);
            $response->error($e->getCode() === 0 ? 500 : $e->getCode());
        }

        return $response;
    }

    public function fromLocaltime(string $cityId, int $timestamp): Response
    {
        try {
            $response = new Response([
                'timestamp' => $this->service->fromLocalTime($cityId, $timestamp),
            ]);
        } catch (Exception $e) {
            $response = new Response(['error_message' => $e->getMessage()]);
            $response->error($e->getCode() === 0 ? 500 : $e->getCode());
        }

        return $response;
    }
}