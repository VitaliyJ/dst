<?php
require_once './vendor/autoload.php';

use Http\Response;
use Bramus\Router\Router;
use Http\Controllers\DatetimeController;

$router = new Router;
$router->set404(function() {
    $data = ['error' => 'API method not found'];
    $response = new Response($data);
    $response->error()->toJson();
});

$router->get('/city/([A-z0-9\-]+)/to-localtime/(\d+)', function($cityId, $timestamp) {
    $response = (new DatetimeController)->toLocaltime($cityId, $timestamp);
    $response->toJson();
});

$router->get('/city/([A-z0-9\-]+)/from-localtime/(\d+)', function($cityId, $timestamp) {
    $response = (new DatetimeController)->fromLocaltime($cityId, $timestamp);
    $response->toJson();
});

$router->put('/time-info/reset', function() {
    $response = (new DatetimeController)->resetInfo();
    $response->toJson();
});

$router->run();