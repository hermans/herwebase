<?php
/**
 * Copyright (c) 2025 hermans@taktikspace.com
 */

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

use DI\Container;
use Slim\Factory\AppFactory;
use Yiisoft\Aliases\Aliases;
use Yiisoft\View\WebView;
use App\Controllers\SiteController;

require_once __DIR__ . '/../vendor/autoload.php';

// Buat Slim container
$container = new Container();
AppFactory::setContainer($container);

// Yii aliases
$aliases = new Aliases([
    '@root' => __DIR__ . '/',
    '@app' => __DIR__ . '/',
    '@views' => __DIR__ . '/views',
]);

// WebView
$container->set(WebView::class, fn() => new WebView($aliases->get('@views')));

// Controller
$container->set(SiteController::class, function($c) {
    return new SiteController($c->get(WebView::class));
});

// Create Slim app
$app = AppFactory::create();

// Routing
require_once __DIR__ . '/config/routes.php';

$app->run();
