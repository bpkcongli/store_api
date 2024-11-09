<?php

use App\Controllers\Api\V1\ProductApiController;
use App\Controllers\Api\V1\UserApiController;
use App\Controllers\Home;
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->addPlaceholder('uuid', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}');
$routes->get('/', [Home::class, 'index']);

$routes->group('api/v1', function (RouteCollection $routes) {
  $routes->group('users', function (RouteCollection $routes) {
    $routes->post('registration', [UserApiController::class, 'registration']);
  });

  $routes->group('products', function (RouteCollection $routes) {
    $routes->post('/', [ProductApiController::class, 'store']);
    $routes->get('/', [ProductApiController::class, 'index']);
    $routes->get('(:uuid)', [ProductApiController::class, 'show/$1']);
    $routes->put('(:uuid)', [ProductApiController::class, 'update/$1']);
    $routes->delete('(:uuid)', [ProductApiController::class, 'delete/$1']);
  });
});
