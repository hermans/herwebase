<?php
/**
 * Copyright (c) 2025 hermans [at] taktikspace.com
 * https://github.com/hermans/herwebase
 * 
 * Route definitions for Slim 4
 *
 * Usage:
 * Each route is defined as:
 *   'path' => [ControllerClass::class, 'method']
 *     → Defaults to GET
 *
 * To specify HTTP methods explicitly (e.g. POST, PUT), use:
 *   'path' => [['GET', 'POST'], ControllerClass::class, 'method']
 *     → Methods must be uppercase: 'GET', 'POST', 'PUT', 'DELETE', etc.
 *
 * To attach middleware to a route, use:
 *   'path' => [['GET', 'POST'], ControllerClass::class, 'method', [Middleware1, Middleware2]]
 *     → Middleware must be PSR-15 compatible instances or callables
 *
 * You can also group routes by prefix using nested arrays:
 *   '/prefix' => [
 *       '/sub1' => [ControllerClass::class, 'method1'],
 *       '/sub2' => [['POST'], ControllerClass::class, 'method2'],
 *   ]
 *     → This will register '/prefix/sub1' and '/prefix/sub2'
 *
 * Examples:
 *   '/'         => [SiteController::class, 'index']                             // GET
 *   '/submit'   => [['POST'], FormController::class, 'submit']                 // POST
 *   '/update'   => [['PUT'], FormController::class, 'update']                  // PUT
 *   '/account'  => [['GET', 'POST'], SiteController::class, 'secure', [LoginCheck]] // GET & POST with middleware
 *   '/utils'    => [
 *       '/date' => [UtilsController::class, 'date'],                           // GET
 *       '/time' => [UtilsController::class, 'time'],                           // GET
 *   ]
 * 
 * ⚠️ Important:
 * If the same path is defined multiple times, only the last one will be used.
 * For example:
 *   '/about' => [SiteController::class, 'about'],
 *   '/about' => [['POST'], SiteController::class, 'about'],
 * → Only the POST route will be registered; the GET will be overridden.
 *
 * Reference:
 * https://www.slimframework.com/docs/v4/objects/routing.html#how-to-create-routes
 */




use App\Components\Route;
use App\Controllers\SiteController;
use App\Middlewares\LoginCheck;

$container = $app->getContainer();
$route = $container->get(Route::class);
$responseFactory = $app->getResponseFactory();


$route->mapRoutes($app, [
    '/'        => [SiteController::class, 'index'],
    '/about'   => [SiteController::class, 'about'],
    '/contact' => [SiteController::class, 'contact'],

    '/utils' => [
        '/date' => [SiteController::class, 'date'],
        '/time' => [SiteController::class, 'time'],
    ],

    '/account' => [['GET','POST'], SiteController::class, 'secure',[new LoginCheck()]],
    

    //'/test' => ['post', SiteController::class, 'secure'],

    
    //'/account' => ['post', SiteController::class, 'secure', [new CsrfGuard($responseFactory)]],

    //'/submit'  => ['post', FormController::class, 'submit'],    // POST
    //'/update'  => ['put', FormController::class, 'update'],     // PUT
]);

