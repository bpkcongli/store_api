<?php

use App\Controllers\Api\V1\ProductApiController;
use App\Controllers\Home;
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', [Home::class, 'index']);

$routes->group('api/v1', function (RouteCollection $routes) {
  $routes->group('products', function (RouteCollection $routes) {
    $routes->post('/', [ProductApiController::class, 'store']);
  });
});
