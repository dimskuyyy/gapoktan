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

        $routes->group('post', static function ($routes) {
            $routes->get('/', 'Post::index');
            $routes->post('datatable', 'Post::getDatatable');
            $routes->post('list', 'Post::list');
            $routes->post('media', 'Post::getMedia');
            $routes->post('detail', 'Post::getDetailMedia');
            $routes->post('form', 'Post::form');
            $routes->post('save', 'Post::save');
            $routes->post('delete', 'Post::delete');
        });

        $routes->group('komentar', static function ($routes) {
            $routes->get('/', 'Komentar::index', ['filter' => 'isAdmin']);
            $routes->post('list', 'Komentar::list', ['filter' => 'isAdmin']);
            $routes->post('form', 'Komentar::form', ['filter' => 'isAdmin']);
            $routes->post('save', 'Komentar::save', ['filter' => 'isAdmin']);
            $routes->post('delete', 'Komentar::delete', ['filter' => 'isAdmin']);
        });

        $routes->group('user', static function ($routes) {
            $routes->get('/', 'UserController::index', ['filter' => 'isAdmin']);
            $routes->post('list', 'UserController::list', ['filter' => 'isAdmin']);
            $routes->post('form', 'UserController::form', ['filter' => 'isAdmin']);
            $routes->post('save', 'UserController::save', ['filter' => 'isAdmin']);
            $routes->post('delete', 'UserController::delete', ['filter' => 'isAdmin']);
        });

        $routes->group('product', static function ($routes){
            $routes->get('/','Product::index',['filter' => 'isAdmin']);
        });
    });
});
