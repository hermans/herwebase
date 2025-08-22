<?php

/**
 * Copyright (c) 2025 hermans [at] taktikspace.com
 * 
 * Route definitions
 *
 * Usage:
 * Each route is defined as:
 *   'path' => [ControllerClass::class, 'method']
 *     → Defaults to GET
 *
 * To specify HTTP method explicitly (e.g. POST, PUT), use:
 *   'path' => ['method', ControllerClass::class, 'method']
 *     → method must be lowercase: 'post', 'put', 'delete', etc.
 *
 * Examples:
 *   '/'        => [SiteController::class, 'index']              // GET
 *   '/submit'  => ['post', FormController::class, 'submit']     // POST
 *   '/update'  => ['put', FormController::class, 'update']      // PUT
 */

use App\Components\Route;
use App\Controllers\SiteController;

$container = $app->getContainer();
$route = $container->get(Route::class);

$route->mapRoutes($app, [
    '/'        => [SiteController::class, 'index'],
    '/about'   => [SiteController::class, 'about'],
    '/contact' => [SiteController::class, 'contact'],
    //'/submit'  => ['post', FormController::class, 'submit'],    // POST
    //'/update'  => ['put', FormController::class, 'update'],     // PUT
]);
