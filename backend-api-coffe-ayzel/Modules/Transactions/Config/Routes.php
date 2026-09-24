<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// Data Transaksi
$routes->group('transaksi', ['namespace' => 'Modules\Transactions\Controllers'], static function ($routes) {
    $routes->get('/', 'TransaksiController::index');
    $routes->get('create', 'TransaksiController::create');
    $routes->post('store', 'TransaksiController::store');
    $routes->get('edit/(:num)', 'TransaksiController::edit/$1');
    $routes->post('update/(:num)', 'TransaksiController::update/$1');
    $routes->delete('delete/(:num)', 'TransaksiController::delete/$1');
    // Sesuaikan nama controller-nya
});

// Approvel Transaksi
$routes->group('transaksi-approvel', ['namespace' => 'Modules\Transactions\Controllers'], static function ($routes) {
    $routes->get('/', 'ApprovelTransaksiController::index');
    $routes->post('approvel-accept/(:num)', 'ApprovelTransaksiController::approvelTransaksiAccept/$1');
    $routes->post('approvel-reject/(:num)', 'ApprovelTransaksiController::approvelTransaksiReject/$1');
    // Sesuaikan nama controller-nya
});

// API
$routes->group('api', ['namespace' => 'Modules\Transactions\Controllers\Api'], static function ($routes) {
    // Gunakan match untuk mengizinkan POST dan OPTIONS ke fungsi store
    $routes->match(['post', 'options'], 'transaksi', 'Transaksi::store');
});