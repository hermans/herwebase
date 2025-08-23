<?php
/**
 * Copyright (C) 2025 by hermans [at] taktikspace.com
 * https://github.com/hermans/herwebase
 * 
 * Slim 4 Route Mapping Component
 *
 * This component defines and registers route mappings using Slim Framework v4.
 * Supports nested route groups, multiple HTTP methods, and optional middleware.
 *
 * Reference:
 * https://www.slimframework.com/docs/v4/objects/routing.html#how-to-create-routes
 *
 * Usage:
 * See App\Controllers and App\Middlewares for route targets and guards.
 * Route definitions are typically passed to Route::mapRoutes($app, [...]).
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

        foreach ($routes as $basePath => $definition) {
            // If the value is a nested array of subroutes
            if (is_array($definition) && $this->isNestedRouteGroup($definition)) {
                foreach ($definition as $subPath => $subDef) {
                    if (!is_array($subDef)) {
                        throw new \InvalidArgumentException("Invalid subroute definition for path: $basePath$subPath");
                    }

                    $fullPath = rtrim($basePath, '/') . '/' . ltrim($subPath, '/');
                    $this->mapSingleRoute($app, $fullPath, $subDef);
                }
            } elseif (is_array($definition)) {
                // Flat route
                $this->mapSingleRoute($app, $basePath, $definition);
            } else {
                throw new \InvalidArgumentException("Invalid route definition for path: $basePath");
            }
        }
    }

    private function isNestedRouteGroup(array $definition): bool
    {
        // Check if all values are arrays with 2 or 3 elements (controller/action or method/controller/action)
        foreach ($definition as $subDef) {
            if (!is_array($subDef) || count($subDef) < 2 || count($subDef) > 3) {
                return false;
            }
        }
        return true;
    }

    private function mapSingleRoute($app, string $path, array $definition): void
    {
        $container = $this->container;

        $methods = ['get']; // default
        $middleware = [];

        if (count($definition) === 2) {
            [$controllerClass, $action] = $definition;
        } elseif (count($definition) === 3) {
            [$first, $controllerClass, $action] = $definition;

            if (is_string($first)) {
                $methods = [strtolower($first)];
            } elseif (is_array($first)) {
                // Check if it's an array of HTTP methods
                $validMethods = ['get', 'post', 'put', 'delete', 'patch', 'options'];
                $lowered = array_map('strtolower', $first);
                if (count(array_intersect($lowered, $validMethods)) === count($lowered)) {
                    $methods = $lowered;
                } else {
                    $middleware = $first;
                }
            }
        
        } elseif (count($definition) === 4) {
            [$methodOrMethods, $controllerClass, $action, $middleware] = $definition;
            $methods = is_array($methodOrMethods)
                ? array_map('strtolower', $methodOrMethods)
                : [strtolower($methodOrMethods)];
        } else {
            throw new \InvalidArgumentException("Invalid route definition for path: $path");
        }

        if (count($methods) === 1) {

            $route = $app->{strtoupper($methods[0])}($path, function (
                ServerRequestInterface $request,
                ResponseInterface $response
            ) use ($controllerClass, $action, $container) {
                $controller = new $controllerClass($container);
                $result = $controller->$action($request);
                $response->getBody()->write($result);
                return $response;
            });

        } else {
            $methods = array_map('strtoupper', $methods);
            $route = $app->map($methods, $path, function ($request, $response, array $args) 
                    use ($controllerClass, $action, $container){
                $controller = new $controllerClass($container);
                $result = $controller->$action($request);
                $response->getBody()->write($result);
                return $response;
            });
        }

        foreach ($middleware as $mw) {
            $route->add($mw);
        }

    }

}
