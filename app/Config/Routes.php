<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// Admin Routes
$routes->group('admin', function($routes) {
    $routes->get('/', 'Admin\Dashboard::index');
    $routes->get('login', 'Admin\Auth::login');
    $routes->post('login', 'Admin\Auth::authenticate');
    $routes->get('logout', 'Admin\Auth::logout');
    
    // Products
    $routes->get('products', 'Admin\Products::index');
    $routes->get('products/create', 'Admin\Products::create');
    $routes->post('products/store', 'Admin\Products::store');
    $routes->get('products/edit/(:num)', 'Admin\Products::edit/$1');
    $routes->post('products/update/(:num)', 'Admin\Products::update/$1');
    $routes->get('products/delete/(:num)', 'Admin\Products::delete/$1');
    

    // Orders
    $routes->get('orders', 'Admin\Orders::index');
    $routes->get('orders/view/(:num)', 'Admin\Orders::view/$1');
    $routes->post('orders/update-status/(:num)', 'Admin\Orders::updateStatus/$1');
    
    // Users (Admin only)
    $routes->get('users', 'Admin\Users::index');
    $routes->get('users/create', 'Admin\Users::create');
    $routes->post('users/store', 'Admin\Users::store');
    $routes->get('users/edit/(:num)', 'Admin\Users::edit/$1');
    $routes->post('users/update/(:num)', 'Admin\Users::update/$1');
    $routes->get('users/delete/(:num)', 'Admin\Users::delete/$1');
    
    // Catalogs (Admin only)
    $routes->get('catalogs', 'Admin\Catalogs::index');
    $routes->get('catalogs/create', 'Admin\Catalogs::create');
    $routes->post('catalogs/store', 'Admin\Catalogs::store');
    $routes->get('catalogs/edit/(:num)', 'Admin\Catalogs::edit/$1');
    $routes->post('catalogs/update/(:num)', 'Admin\Catalogs::update/$1');
    $routes->get('catalogs/delete/(:num)', 'Admin\Catalogs::delete/$1');
    $routes->get('catalogs/download/(:num)', 'Admin\Catalogs::download/$1');
    
    // Divisions (Admin only)
    $routes->get('divisions', 'Admin\Divisions::index');
    $routes->get('divisions/create', 'Admin\Divisions::create');
    $routes->post('divisions/store', 'Admin\Divisions::store');
    $routes->get('divisions/edit/(:num)', 'Admin\Divisions::edit/$1');
    $routes->post('divisions/update/(:num)', 'Admin\Divisions::update/$1');
    $routes->get('divisions/delete/(:num)', 'Admin\Divisions::delete/$1');
});

// Customer Routes
$routes->get('product/(:num)', 'Shop::product/$1');
$routes->post('cart/add', 'Cart::add');
$routes->get('cart', 'Cart::index');
$routes->get('catalog/download/(:num)', 'Catalog::download/$1');
