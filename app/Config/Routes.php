<?php

use App\Controllers\Api\V1\ProductApiController;
use App\Controllers\Home;
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->addPlaceholder('uuid', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}');
$routes->get('/', [Home::class, 'index']);

$routes->group('api/v1', function (RouteCollection $routes) {
  $routes->group('products', function (RouteCollection $routes) {
    $routes->post('/', [ProductApiController::class, 'store']);
    $routes->get('/', [ProductApiController::class, 'index']);
    $routes->get('(:uuid)', [ProductApiController::class, 'show/$1']);
  });
});
