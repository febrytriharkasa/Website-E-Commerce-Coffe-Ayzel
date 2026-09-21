<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// Data Transaksi
$routes->group('transaksi', ['namespace' => 'Modules\Transactions\Controllers'], static function ($routes) {
    $routes->get('/', 'TransaksiController::index');
    $routes->get('create', 'TransaksiController::create');
    $routes->post('store', 'TransaksiController::store');
    // $routes->get('edit/(:num)', 'TransaksiController::edit/$1');
    // $routes->post('update/(:num)', 'TransaksiController::update/$1');
    // $routes->delete('delete/(:num)', 'TransaksiController::delete/$1');
});
