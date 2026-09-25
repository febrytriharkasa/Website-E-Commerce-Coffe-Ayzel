<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

if (!isset($routes)) {
    $routes = \Config\Services::routes(true);
}

// Data Produk
$routes->group('product', ['namespace' => 'Modules\Products\Controllers', 'filter' => 'authFilter'], static function ($routes) {
    $routes->get('/', 'Product::index');
    $routes->get('create', 'Product::create');
    $routes->post('store', 'Product::store');
    $routes->get('edit/(:num)', 'Product::edit/$1');
    $routes->post('update/(:num)', 'Product::update/$1');
    $routes->delete('delete/(:num)', 'Product::delete/$1');
});

// Manajemen varian dan stok produk
$routes->group('sizes-product', ['namespace' => 'Modules\Products\Controllers', 'filter' => 'authFilter'], static function ($routes) {
    $routes->get('/', 'SizeProduct::index');
    $routes->get('create', 'SizeProduct::create');
    $routes->post('store/', 'SizeProduct::store');
    $routes->get('edit/(:num)', 'SizeProduct::edit/$1');
    $routes->post('update/(:num)', 'SizeProduct::update/$1');
    $routes->delete('delete/(:num)', 'SizeProduct::delete/$1');
    $routes->post('update-stok/(:num)', 'SizeProduct::updateStok/$1');
});

// API
$routes->group('api', ['namespace' => 'Modules\Products\Controllers\Api'], static function ($routes) {
    $routes->get('produk', 'Produk::index');          // Mengambil produk & ukuran
});
