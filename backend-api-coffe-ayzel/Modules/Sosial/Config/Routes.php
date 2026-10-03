<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

if (!isset($routes)) {
    $routes = \Config\Services::routes(true);
}

// API
$routes->get('api/settings', '\Modules\Sosial\Controllers\Api\SettingsController::index');

// Dashboard
$routes->group('settings', ['namespace' => 'Modules\Sosial\Controllers', 'filter' => 'authFilter'], static function ($routes) {
    $routes->get('/', 'SosialMediaController::index');
    $routes->post('store', 'SosialMediaController::store');
    $routes->post('update', 'SosialMediaController::update');
});
