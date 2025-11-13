<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->post('login', 'AuthController::login');

$routes->get('profile', 'ProfileController::index', ['filter' => 'jwt']);
$routes->post('login', 'AuthController::login');
$routes->get('auth/verify', 'AuthController::verify');
$routes->post('/login', 'AuthController::login');
$routes->get('profile', 'ProfileController::index', ['filter' => 'jwt']);


// Product Endpoints
$routes->get('/products', 'ProductController::index');
$routes->get('/products/(:num)', 'ProductController::show/$1');
$routes->post('/products', 'ProductController::create');
$routes->delete('/products/(:num)', 'ProductController::delete/$1');
$routes->put('products/(:num)', 'ProductController::update/$1');

// Category
$routes->get('/categories', 'ProductController::getCategories');

$routes->get('products', 'ProductController::viewProducts');

$routes->get('login', 'LoginController::loginForm');
$routes->post('login', 'LoginController::login');
$routes->post('login/auth', 'LoginController::auth');
