<?php

namespace Http\Controllers;

use Http\Response;

class DatetimeController
{
    public function toLocaltime(string $cityId, int $timestamp): Response
    {
        return new Response(['city' => $cityId, 'timestamp' => $timestamp]);
    }

    public function fromLocaltime(string $cityId, int $timestamp): Response
    {
        return new Response(['city' => $cityId, 'timestamp' => $timestamp]);
    }
}