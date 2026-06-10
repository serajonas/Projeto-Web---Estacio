<?php

require_once __DIR__ . '/app/bootstrap.php';

use App\Controllers\ApiController;

$controller = new ApiController();
$controller->handleRequest();
