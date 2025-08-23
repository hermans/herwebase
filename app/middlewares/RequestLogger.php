<?php

/**
 * Copyright (c) 2025 hermans [at] taktikspace.com
 * https://github.com/hermans/herwebase
 * 
 */

namespace App\Middlewares;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;

class RequestLogger implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        error_log(sprintf("[%s] %s %s", date('Y-m-d H:i:s'), $request->getMethod(), $request->getUri()));
        return $handler->handle($request);
    }
}
