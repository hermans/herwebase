<?php
/**
 * Copyright (C) 2025 by hermans [at] taktikspace.com
 */

namespace App\Components;

use Psr\Container\ContainerInterface;

class Helpers
{
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function createUrl(string $route = '', array $params = []): string
    {
        $url = rtrim($this->container->get('basePath'), '/') . '/' . ltrim($route, '/');

        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }

        return $url;
    }
}
