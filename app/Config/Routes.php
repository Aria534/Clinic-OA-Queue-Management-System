<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// ── PUBLIC — Walk-in queue (no login required) ────────────
$routes->get('/',               'Queue::index');       // booking form
$routes->post('book',           'Queue::book');        // submit → get number
$routes->get('ticket/(:num)',   'Queue::ticket/$1');   // show issued ticket

// ── AUTH ──────────────────────────────────────────────────
$routes->get('login',  'Auth::index');
$routes->post('login', 'Auth::login');
$routes->get('logout', 'Auth::logout');

// ── ADMIN (login required) ────────────────────────────────
$routes->group('admin', ['filter' => 'auth:admin'], function ($routes) {
    $routes->get('/',                           'Admin::index');
    $routes->get('queue',                       'Admin::queue');
    $routes->post('queue/next',                 'Admin::callNext');
    $routes->post('queue/skip',                 'Admin::skipCurrent');
    $routes->get('services',                    'Admin::services');
    $routes->post('services/store',             'Admin::storeService');
    $routes->post('services/toggle/(:num)',     'Admin::toggleService/$1');
});