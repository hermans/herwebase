<?php

use App\Controllers\SiteController;

$app->get('/', function ($request, $response) use ($container) {
    $controller = $container->get(SiteController::class);
    $html = $controller->index();
    $response->getBody()->write($html);
    return $response;
});


$app->get('/about', function ($request, $response) use ($container) {
    $controller = $container->get(SiteController::class);
    $html = $controller->about();
    $response->getBody()->write($html);
    return $response;
});

$app->get('/contact', function ($request, $response) use ($container) {
    $controller = $container->get(SiteController::class);
    $html = $controller->contact();
    $response->getBody()->write($html);
    return $response;
});
