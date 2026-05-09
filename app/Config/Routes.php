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

$routes->get('/dashboard', 'UserController::profil', ['filter' => 'role:user']);
$routes->get('/objectif', 'ObjectifController::index', ['filter' => 'role:user']);
$routes->post('/objectif/choisir', 'ObjectifController::choisir', ['filter' => 'role:user']);
$routes->post('/profil/update', 'UserController::updatePoids', ['filter' => 'role:user']);
$routes->get('/dashboard/regimes', 'UserController::regimes', ['filter' => 'role:user']);
$routes->post('/dashboard/regimes/purchase', 'UserController::purchaseRegime', ['filter' => 'role:user']);
$routes->get('/dashboard/sports', 'UserController::sports', ['filter' => 'role:user']);
$routes->post('/dashboard/sports/start', 'UserController::startSport', ['filter' => 'role:user']);
$routes->get('/admin', 'AdminController::dashboard', ['filter' => 'role:admin']);
$routes->get('/admin/regimes', 'AdminController::regimes', ['filter' => 'role:admin']);
$routes->get('/admin/sports', 'AdminController::sports', ['filter' => 'role:admin']);
$routes->get('/admin/codes', 'AdminController::codes', ['filter' => 'role:admin']);
$routes->post('/admin/codes/create', 'AdminController::createCode', ['filter' => 'role:admin']);
$routes->post('/admin/codes/update', 'AdminController::updateCode', ['filter' => 'role:admin']);
$routes->post('/admin/codes/delete', 'AdminController::deleteCode', ['filter' => 'role:admin']);

$routes->post('/admin/categories/create', 'AdminController::createCategorie', ['filter' => 'role:admin']);
$routes->post('/admin/categories/update', 'AdminController::updateCategorie', ['filter' => 'role:admin']);
$routes->post('/admin/categories/delete', 'AdminController::deleteCategorie', ['filter' => 'role:admin']);

$routes->post('/admin/aliments/create', 'AdminController::createAliment', ['filter' => 'role:admin']);
$routes->post('/admin/aliments/update', 'AdminController::updateAliment', ['filter' => 'role:admin']);
$routes->post('/admin/aliments/delete', 'AdminController::deleteAliment', ['filter' => 'role:admin']);

$routes->post('/admin/programmes/create', 'AdminController::createProgramme', ['filter' => 'role:admin']);
$routes->post('/admin/programmes/update', 'AdminController::updateProgramme', ['filter' => 'role:admin']);
$routes->post('/admin/programmes/delete', 'AdminController::deleteProgramme', ['filter' => 'role:admin']);

$routes->post('/admin/compositions/create', 'AdminController::createComposition', ['filter' => 'role:admin']);
$routes->post('/admin/compositions/update', 'AdminController::updateComposition', ['filter' => 'role:admin']);
$routes->post('/admin/compositions/delete', 'AdminController::deleteComposition', ['filter' => 'role:admin']);
// Wallet & Gold (à la fin du fichier)
$routes->get('/wallet', 'WalletController::index', ['filter' => 'role:user']);
$routes->post('/wallet/appliquer_code', 'WalletController::applyCode', ['filter' => 'role:user']);
$routes->post('/wallet/devenir_gold', 'WalletController::becomeGold', ['filter' => 'role:user']);
$routes->get('/gold', 'WalletController::goldInfo', ['filter' => 'role:user']);

// Génération de codes en masse
$routes->post('/admin/codes/generate', 'AdminController::generateCodes', ['filter' => 'role:admin']);