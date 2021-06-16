<?php
require_once './vendor/autoload.php';

use Repositories\Mysql;

$db = Mysql::getInstance();
$connection = $db->connection();

$sql = file_get_contents('./city.sql');
$connection->exec($sql);