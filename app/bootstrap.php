<?php
/**
 * Copyright (c) 2025 hermans [at] taktikspace.com
 * https://github.com/hermans/herwebase
 * 
 */

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

use App\Components\Helpers;
use App\Components\Route;
use DI\Container;
use Slim\Factory\AppFactory;
use Yiisoft\Aliases\Aliases;
use Yiisoft\View\WebView;

require_once __DIR__ . '/../vendor/autoload.php';

$file_config =  __DIR__ . '/../app/config/web.php';
$config = [];
if(file_exists($file_config)){
    $config = include $file_config;
}

// Buat Slim container
$container = new Container();
AppFactory::setContainer($container);

// Register Aliases explicitly
$container->set(Aliases::class, function () {
    return new Aliases([
        '@root' => __DIR__ . '/',
        '@app' => __DIR__ . '/',
        '@views' => __DIR__ . '/views',
    ]);
});

// Register Route function
$container->set(Route::class, function ($container) {
    return new Route($container);
});

// Register Helpers with container access
$container->set(Helpers::class, function ($container) {
    return new Helpers($container);
});

// Register WebView using Aliases from container
$container->set(WebView::class, function ($container) {
    $aliases = $container->get(Aliases::class);
    $helpers = $container->get(Helpers::class);

    $view = new WebView($aliases->get('@views'));

    // Inject helpers globally
    $view->setParameter('helpers', $helpers);
    $view->setParameter('createUrl', fn($route, $params = []) => $helpers->createUrl($route, $params));

    return $view;
});

// Create Slim app
$app = AppFactory::create();

if (
    isset($config['basePath']) &&
    is_string($config['basePath']) &&
    $config['basePath'] !== '' &&
    $config['basePath'] !== '\\' &&
    $config['basePath'] !== '/'
) {
    $app->setBasePath($config['basePath']);
}

$container->set('basePath', fn() => $app->getBasePath());

// Routing
require_once __DIR__ . '/config/routes.php';

$app->run();
