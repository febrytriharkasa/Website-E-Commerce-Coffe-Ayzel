<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

if (!isset($routes)) {
    $routes = \Config\Services::routes(true);
}

// RUTE PUBLIK (Bebas Akses)
$routes->get('/', '\Modules\Auth\Controllers\UserController::index');
$routes->post('/login-process', '\Modules\Auth\Controllers\UserController::loginProcess');
$routes->get('/logout', '\Modules\Auth\Controllers\UserController::logout');
