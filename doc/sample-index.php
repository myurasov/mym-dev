<?php

/**
 * Front controller
 */

use Symfony\Component\HttpFoundation\Request;
use ymF\Router\DefaultRouter;

require __DIR__ . '/../modules/bootstrap.php';

// Create request
$request = Request::createFromGlobals();

// Route
$router = new DefaultRouter();
$router->route($request);
$controller = $router->getController();
$action = $router->getAction();

// Call controller
$controller = new $controller;
$response = $controller->$action($request);

// Send response
$response->send();