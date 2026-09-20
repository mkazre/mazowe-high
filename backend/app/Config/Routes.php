<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// ---------------- Public website ----------------
$routes->get('/', 'Web\SiteController::home');
$routes->post('/contact/submit', 'Web\SiteController::contactSubmit');
$routes->post('/admissions/apply/submit', 'Web\SiteController::applicationSubmit');

// ---------------- Admin panel ----------------
$routes->group('admin', ['namespace' => 'App\Controllers\Admin'], static function ($routes) {
    $routes->get('login', 'AuthController::login');
    $routes->post('login', 'AuthController::attemptLogin');
    $routes->get('logout', 'AuthController::logout');

    $routes->group('', ['filter' => 'adminAuth'], static function ($routes) {
        $routes->get('/', 'DashboardController::index');
        $routes->get('dashboard', 'DashboardController::index');

        $routes->get('pages', 'PageController::index');
        $routes->get('pages/(:num)/edit', 'PageController::edit/$1');
        $routes->post('pages/(:num)/edit', 'PageController::update/$1');
        $routes->post('pages/(:num)/blocks/(:num)', 'PageController::updateBlock/$1/$2');
        $routes->post('pages/(:num)/blocks/(:num)/delete', 'PageController::deleteBlock/$1/$2');
        $routes->post('pages/(:num)/blocks/add', 'PageController::addBlock/$1');
        $routes->post('pages/(:num)/blocks/reorder', 'PageController::reorderBlocks/$1');

        $routes->get('media', 'MediaController::index');
        $routes->post('media/upload', 'MediaController::upload');
        $routes->post('media/(:num)/delete', 'MediaController::delete/$1');

        $routes->get('settings', 'SettingsController::index');
        $routes->post('settings', 'SettingsController::update');

        $routes->get('enquiries', 'EnquiryController::index');
        $routes->get('enquiries/(:num)', 'EnquiryController::show/$1');
        $routes->post('enquiries/(:num)/status', 'EnquiryController::updateStatus/$1');

        $routes->get('notices', 'NoticeController::index');
        $routes->get('notices/create', 'NoticeController::create');
        $routes->post('notices/create', 'NoticeController::store');
        $routes->get('notices/(:num)/edit', 'NoticeController::edit/$1');
        $routes->post('notices/(:num)/edit', 'NoticeController::update/$1');
        $routes->post('notices/(:num)/delete', 'NoticeController::delete/$1');

        $routes->get('events', 'EventController::index');
        $routes->get('events/create', 'EventController::create');
        $routes->post('events/create', 'EventController::store');
        $routes->get('events/(:num)/edit', 'EventController::edit/$1');
        $routes->post('events/(:num)/edit', 'EventController::update/$1');
        $routes->post('events/(:num)/delete', 'EventController::delete/$1');

        $routes->get('posts', 'PostController::index');
        $routes->get('posts/create', 'PostController::create');
        $routes->post('posts/create', 'PostController::store');
        $routes->get('posts/(:num)/edit', 'PostController::edit/$1');
        $routes->post('posts/(:num)/edit', 'PostController::update/$1');
        $routes->post('posts/(:num)/delete', 'PostController::delete/$1');

        $routes->get('vacancies', 'VacancyController::index');
        $routes->get('vacancies/create', 'VacancyController::create');
        $routes->post('vacancies/create', 'VacancyController::store');
        $routes->get('vacancies/(:num)/edit', 'VacancyController::edit/$1');
        $routes->post('vacancies/(:num)/edit', 'VacancyController::update/$1');
        $routes->post('vacancies/(:num)/delete', 'VacancyController::delete/$1');

        $routes->get('users', 'UserController::index');
        $routes->get('users/create', 'UserController::create');
        $routes->post('users/create', 'UserController::store');
        $routes->get('users/(:num)/edit', 'UserController::edit/$1');
        $routes->post('users/(:num)/edit', 'UserController::update/$1');
        $routes->post('users/(:num)/delete', 'UserController::delete/$1');
    });
});

// ---------------- REST API (mobile app) ----------------
$routes->group('api/v1', ['namespace' => 'App\Controllers\Api\V1'], static function ($routes) {
    $routes->post('auth/login', 'AuthController::login');
    $routes->post('auth/refresh', 'AuthController::refresh');

    $routes->group('', ['filter' => 'apiAuth'], static function ($routes) {
        $routes->get('me', 'AuthController::me');
    });

    $routes->get('notices', 'ContentController::notices');
    $routes->get('events', 'ContentController::events');
    $routes->get('posts', 'ContentController::posts');
    $routes->get('pages/(:segment)/(:segment)', 'ContentController::page/$1/$2');
    $routes->post('contact', 'ContentController::contact');
    $routes->post('applications', 'ContentController::application');
});

// Generic public-page catch-all — must be LAST so it never shadows /admin or /api/v1.
$routes->get('/(:segment)/(:segment)', 'Web\SiteController::page/$1/$2');
