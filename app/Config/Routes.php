<?php

namespace Config;

use CodeIgniter\Router\RouteCollection;

$routes = Services::routes();

if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Members');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);


$routes->get('/', 'Members::index');
$routes->get('/members', 'Members::index');

$routes->get('/members/create', 'Members::create');
$routes->post('/members/store', 'Members::store');

$routes->get('/members/edit/(:num)', 'Members::edit/$1');      
$routes->post('/members/update/(:num)', 'Members::update/$1');

$routes->get('/members/delete/(:num)', 'Members::delete/$1');

$routes->post('/members/uploadAvatar/(:num)', 'Members::uploadAvatar/$1');

$routes->get('/members/(:num)', 'Members::show/$1');      
