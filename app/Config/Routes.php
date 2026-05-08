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

$routes->get('/register/step1', 'UserController::registerStep1');
$routes->post('/register/step1', 'UserController::storeStep1');
$routes->get('/register/step2', 'UserController::registerStep2');
$routes->post('/register/step2', 'UserController::storeStep2');
$routes->post('/user/check_email', 'UserController::checkEmail');

$routes->get('/dashboard', 'UserController::dashboard', ['filter' => 'role:user']);
$routes->get('/admin', 'AdminController::dashboard', ['filter' => 'role:admin']);
$routes->get('/admin/regimes', 'AdminController::regimes', ['filter' => 'role:admin']);
$routes->get('/admin/sports', 'AdminController::sports', ['filter' => 'role:admin']);
$routes->get('/admin/codes', 'AdminController::codes', ['filter' => 'role:admin']);
