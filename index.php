<?php
require_once './vendor/autoload.php';

use Http\Request;
use Http\Response;
use Helpers\RoutesHelper;
use Http\Controllers\DatetimeController;

$request = Request::getInstance();

if (RoutesHelper::isGet('/city/{city}/to-localtime/{time}')) {
    $response = (new DatetimeController)->localtime($request);
    $response->toJson();
}

if (RoutesHelper::isGet('/city/{city}/from-localtime/{time}')) {
    $response = (new DatetimeController)->localtime($request);
    $response->toJson();
}

$data = ['error' => 'API method not found'];
$response = new Response($data);
$response->error()->toJson();