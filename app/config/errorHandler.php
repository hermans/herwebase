<?php

use Yiisoft\ErrorHandler\ErrorHandler;
use Yiisoft\ErrorHandler\Renderer\HtmlRenderer;
use Yiisoft\ErrorHandler\Factory\ThrowableResponseFactory;
use Yiisoft\ErrorHandler\Middleware\ErrorCatcher;
use Psr\Log\NullLogger;
use Slim\App;

return function (App $app): void {
    $errorHandler = new ErrorHandler(new NullLogger(), new HtmlRenderer());
    $errorHandler->debug(true); // toggle based on env

    $responseFactory = new ThrowableResponseFactory(
        $app->getResponseFactory(),
        $errorHandler,
        $app->getContainer()
    );

    $app->add(new ErrorCatcher($responseFactory));

    
};
