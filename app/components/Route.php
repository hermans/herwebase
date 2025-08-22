<?php
/**
 * Copyright (C) 2025 by hermans [at] taktikspace.com
 * https://github.com/hermans/herwebase
 */

namespace App\Components;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Container\ContainerInterface;

class Route
{
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function mapRoutes($app, array $routes): void
    {
        $container = $this->container; 

        foreach ($routes as $path => $definition) {
            if (is_array($definition) && count($definition) === 2) {
                [$controllerClass, $action] = $definition;
                $method = 'get';
            } elseif (is_array($definition) && count($definition) === 3) {
                [$method, $controllerClass, $action] = $definition;
                $method = strtolower($method);
            } else {
                throw new \InvalidArgumentException("Invalid route definition for path: $path");
            }

            $app->$method($path, function (ServerRequestInterface $request, ResponseInterface $response) use ($controllerClass, $action, $container) {
                $controller = new $controllerClass($container);
                $result = $controller->$action($request);
                $response->getBody()->write($result);
                return $response;
            });
        }
    }

}
