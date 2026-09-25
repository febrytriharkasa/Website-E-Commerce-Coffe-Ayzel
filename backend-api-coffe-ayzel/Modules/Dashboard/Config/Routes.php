<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

if (!isset($routes)) {
    $routes = \Config\Services::routes(true);
}

// Dashboard
$routes->group('dashboard', ['namespace' => 'Modules\Dashboard\Controllers', 'filter' => 'authFilter'], static function ($routes) {
    $routes->get('/', 'DashboardController::index');
});
