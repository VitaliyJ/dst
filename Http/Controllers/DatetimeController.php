<?php

namespace Http\Controllers;

use Exception;
use Http\Response;
use Services\GetLocationTimeInfo;
use Services\ResetCache;

class DatetimeController
{
    private GetLocationTimeInfo $getTimeInfoService;
    private ResetCache $resetTimeInfoService;

    public function __construct()
    {
        $this->getTimeInfoService = new GetLocationTimeInfo;
        $this->resetTimeInfoService = new ResetCache;
    }

    public function toLocaltime(string $cityId, int $timestamp): Response
    {
        try {
            $response = new Response([
                'timestamp' => $this->getTimeInfoService->toLocalTime($cityId, $timestamp),
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
                'timestamp' => $this->getTimeInfoService->fromLocalTime($cityId, $timestamp),
            ]);
        } catch (Exception $e) {
            $response = new Response(['error_message' => $e->getMessage()]);
            $response->error($e->getCode() === 0 ? 500 : $e->getCode());
        }

        return $response;
    }

    public function resetInfo(): Response
    {
        try {
            $this->resetTimeInfoService->reset();
            $response = new Response([
                'success' => true,
            ]);
        } catch (Exception $e) {
            $response = new Response(['error_message' => $e->getMessage()]);
            $response->error($e->getCode() === 0 ? 500 : $e->getCode());
        }

        return $response;
    }
}