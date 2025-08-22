<?php
namespace App\Components;

use Yiisoft\Router\RouteCollection;
use Yiisoft\Router\Route;
use Yiisoft\Router\RouteCollectorInterface;
use Yiisoft\Router\MatchingResult;

class SimpleRouter
{
    private RouteCollection $routes;

    public function __construct(RouteCollectorInterface $collector)
    {
        $this->routes = new RouteCollection($collector);
    }

    public function addRoute(Route $route): void
    {
        $this->routes->addRoute($route);
    }

    public function match(string $method, string $path): MatchingResult
    {
        return $this->routes->match($method, $path);
    }
}
