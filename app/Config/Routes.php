<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// $routes->get('/', 'Home::index');
$routes->get('media/(:segment)', '\App\Controllers\Front\MediaAccess::viewMedia/$1');

$routes->group('admin', ['namespace' => 'App\Controllers\Back'], static function ($routes) {

    $routes->get('logout', 'Auth::logout');
    $routes->get('login', 'Auth::login');
    $routes->post('login-do', 'Auth::doLogin');

    $routes->group('/', ['filter' => 'isLoggedIn', 'namespace' => 'App\Controllers\Back'], static function ($routes) {
        $routes->get('/', 'Dashboard::index');

        $routes->group('kategori', static function ($routes) {
            $routes->get('/', 'Kategori::index', ['filter' => 'isAdmin']);
            $routes->post('list', 'Kategori::list', ['filter' => 'isAdmin']);
            $routes->post('form', 'Kategori::form', ['filter' => 'isAdmin']);
            $routes->post('save', 'Kategori::save', ['filter' => 'isAdmin']);
            $routes->post('delete', 'Kategori::delete', ['filter' => 'isAdmin']);
        });

        $routes->group('media', static function ($routes) {
            $routes->get('/', 'Media::index');
            $routes->post('list', 'Media::list');
            $routes->post('form', 'Media::form');
            $routes->post('save', 'Media::save');
            $routes->post('delete', 'Media::delete');
        });
    });
});
