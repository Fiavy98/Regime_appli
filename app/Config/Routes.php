<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'HomeController::index');
$routes->get('/regimes', 'HomeController::regimesPreview');
$routes->get('/sports', 'HomeController::sportsPreview');

$routes->get('/login', 'AuthController::login');
$routes->post('/login', 'AuthController::authenticate');
$routes->get('/logout', 'AuthController::logout');

$routes->get('/dashboard', 'UserController::dashboard', ['filter' => 'role:user']);
$routes->get('/admin', 'AdminController::dashboard', ['filter' => 'role:admin']);
