<?php

$router = Router::getInstance();
$router->add('GET', '/', [SystemController::class, 'index']);
$router->add('GET', '/contact', [SystemController::class, 'contact']);
$router->add('GET', '/create', [SystemController::class, 'create']);
$router->add('POST', '/', [SystemController::class, 'store']);
$router->add('GET', '/users/{id}/profile', [SystemController::class, 'view']);
$router->add('GET', '/users/{id}/edit', [SystemController::class, 'edit']);
$router->add('PATCH', '/users/{id}/profile', [SystemController::class, 'update']);
$router->add('DELETE', '/users/{id}/delete', [SystemController::class, 'destroy']);