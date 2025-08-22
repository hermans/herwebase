<?php

/**
 * Copyright (C) 2025 by hermans [at] taktikspace.com
 * https://github.com/hermans/herwebase
 */

namespace App\Components;

use Psr\Container\ContainerInterface;
use Yiisoft\View\WebView;

class Controller
{
    protected ContainerInterface $container;
    protected WebView $view;

    protected $basePath;

    protected string $layout = 'layouts/main';

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->view = $container->get(WebView::class);
        $this->basePath = $this->container->get('basePath');
    }


    public function render(string $template, array $params = []): string
    {
        $content = $this->view->render($template, $params);        

        return $this->view->render($this->layout, [
            'content' => $content,
            'basePath'  => $this->basePath,
        ]);
    }
    
}
