<?php
namespace App\Components;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Helpers
{
    public static $container;

    public static function Routes($app, array $routes)
    {
        foreach ($routes as $path => $definition) {
            // If method is not specified, default to GET
            if (is_array($definition) && count($definition) === 2) {
                [$controllerClass, $action] = $definition;
                $method = 'get';
            } elseif (is_array($definition) && count($definition) === 3) {
                [$method, $controllerClass, $action] = $definition;
                $method = strtolower($method);
            } else {
                throw new \InvalidArgumentException("Invalid route definition for path: $path");
            }

            $app->$method($path, function (ServerRequestInterface $request, ResponseInterface $response) use ($controllerClass, $action) {
                $controller = self::$container->get($controllerClass);
                $result = $controller->$action($request); 
                $response->getBody()->write($result);
                return $response;
            });
        }
    }
}