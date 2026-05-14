<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// ── PUBLIC — Walk-in queue (no login required) ────────────
$routes->get('/',               'Queue::index');
$routes->post('book',           'Queue::book');
$routes->get('ticket/(:num)',   'Queue::ticket/$1');

// ── PUBLIC DISPLAY (TV screen — no login required) ────────
$routes->get('display',             'Admin::displayScreen');
$routes->get('api/display',         'Api::display');

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