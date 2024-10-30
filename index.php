<?php

include 'system/autoload.php';
header("Content-Type: application/json; charset=UTF-8");
set_error_handler('ErrorHandler::handleError');
set_exception_handler('ErrorHandler::handleException');

// Cookie processing if needed

$request = $_SERVER['REQUEST_URI'];

$path = parse_url($request, PHP_URL_PATH);
$router->dispatch($path);