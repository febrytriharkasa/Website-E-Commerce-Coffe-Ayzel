<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// Dashboard
$routes->group('dashboard', ['namespace' => 'Modules\Dashboard\Controllers'], static function ($routes) {
    $routes->get('/', 'DashboardController::index');
});
